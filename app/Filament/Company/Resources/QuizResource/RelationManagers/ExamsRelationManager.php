<?php
namespace App\Filament\Company\Resources\QuizResource\RelationManagers;
use App\Jobs\MemberSubscribedToPrivateExam;
use App\Models\Member;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Exceptions\Halt;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
class ExamsRelationManager extends RelationManager
{
    protected static string $relationship = 'exams';
    protected static ?string $label = 'invitations';
    public function form(Form $form): Form
    {
        initTenant();
        return $form
            ->schema([
                Forms\Components\Select::make('membersIds')
                    ->label('Members')
                    ->getSearchResultsUsing(
                        function (string $search): array {
                            return Member::query()
                                ->limit(50)
                                ->where('email', 'like', "%{$search}%")
                                ->whereDoesntHave('invitations', function ($query) {
                                    $query->where('quiz_id', $this->getOwnerRecord()->id);
                                })
                                ->pluck('email', 'id')
                                ->toArray();
                        }
                    )
                    ->optionsLimit(50)
                    ->preload()
                    ->multiple()
                    ->required(),
            ])->columns(1);
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('score')
            ->columns([
                Tables\Columns\TextColumn::make('member.name'),
                Tables\Columns\TextColumn::make('member.email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('score'),
                Tables\Columns\TextColumn::make('time_taken'),
                Tables\Columns\TextColumn::make('total_attempts'),
                Tables\Columns\IconColumn::make('is_successful')->boolean()->placeholder('empty'),
                Tables\Columns\TextColumn::make('created_at')->sortable()->since()->label('Started since'),
            ])
            ->filters([
                SelectFilter::make('is_successful')
                    ->options([
                        1 => 'Successful',
                        0 => 'Failed',
                    ])
                    ->attribute('is_successful')
            ])
            ->actions([])
            ->bulkActions([])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New exam invitations')
                    ->action(function (array $data,  $record): void {
                        $this->saveData($data);
                    })
                    ->createAnother(false)
                    ->modalSubmitActionLabel('Send invitations')
                    ->modalHeading('Choose invited members')
            ]);
    }
    public function saveData($data)
    {
        $quiz = $this->getOwnerRecord();
        $membersIds =  $data['membersIds'];
        $chunkSize = 100;
        initTenant();
        $baseUrl = tenant()->domains[0]->domain;
        Member::select('id', 'name', 'email', 'tenant_id')
            ->whereIn('id', $membersIds)
            ->chunk($chunkSize, function ($members) use ($quiz, $baseUrl) {
                dispatch(new MemberSubscribedToPrivateExam($members, $quiz, $baseUrl));
            });
        Notification::make()
            ->title("Members subscribed to exam successfully.")
            ->icon('heroicon-o-document-text')
            ->iconColor('success')
            ->send();
        return new Halt;
    }
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->exams->count();
    }
}
