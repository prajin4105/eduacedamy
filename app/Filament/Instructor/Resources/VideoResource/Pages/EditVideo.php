<?php

namespace App\Filament\Instructor\Resources\VideoResource\Pages;

use App\Filament\Instructor\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideo extends EditRecord
{
    protected static string $resource = VideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),T
        ];
    }

    protected function afterSave(): void
    {
        // Any logic to run after video update
    }
}
