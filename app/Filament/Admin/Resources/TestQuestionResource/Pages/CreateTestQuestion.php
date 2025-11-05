<?php

namespace App\Filament\Admin\Resources\TestQuestionResource\Pages;

use App\Filament\Admin\Resources\TestQuestionResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateTestQuestion extends CreateRecord
{
    protected static string $resource = TestQuestionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Handle pre-filled test_id from URL
        if (request()->has('test_id') && !isset($data['test_id'])) {
            $data['test_id'] = request()->get('test_id');
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('view-test', ['test' => $this->record->test_id]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Cancel')
                ->url(fn (): string => request()->has('test_id')
                    ? static::getResource()::getUrl('view-test', ['test' => request()->get('test_id')])
                    : static::getResource()::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    protected function afterCreate(): void
    {
        // You can add any logic here after creating the question
    }
}
