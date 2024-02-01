<?php

namespace App\Filament\Company\Resources\MemberQuizResource\Pages;

use App\Filament\Company\Resources\MemberResource;
use App\Filament\Company\Widgets\ExamStatisticWidget;
use App\Filament\Company\Widgets\MemberStatisticWidget;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMemberQuiz extends ViewRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

   

}
