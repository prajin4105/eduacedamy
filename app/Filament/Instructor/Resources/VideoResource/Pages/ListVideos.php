<?php

namespace App\Filament\Instructor\Resources\VideoResource\Pages;

use App\Filament\Instructor\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVideos extends ListRecords
{
    protected static string $resource = VideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Video')
                ->icon('heroicon-o-plus'),
        ];
    }
}
