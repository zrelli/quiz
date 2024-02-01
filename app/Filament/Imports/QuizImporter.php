<?php

namespace App\Filament\Imports;

use App\Models\Quiz;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class QuizImporter extends Importer
{
    protected static ?string $model = Quiz::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title'),
            ImportColumn::make('description'),
            ImportColumn::make('max_attempts')->numeric(),
            ImportColumn::make('duration')->numeric(),
            ImportColumn::make('is_published'),
            ImportColumn::make('test_type'),
            ImportColumn::make('started_at'),
            ImportColumn::make('tenant_id'),
            ImportColumn::make('expired_at'),

        ];
    }







    public function resolveRecord(): ?Quiz
    {
        // return Quiz::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Quiz();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your quiz import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
