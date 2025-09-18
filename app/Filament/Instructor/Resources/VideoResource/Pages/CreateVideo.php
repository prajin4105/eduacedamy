<?php

namespace App\Filament\Instructor\Resources\VideoResource\Pages;

use App\Filament\Instructor\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVideo extends CreateRecord
{
    protected static string $resource = VideoResource::class;
    
    protected function afterCreate(): void
    {
        // Any logic to run after video creation
    }
}
