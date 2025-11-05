<?php

namespace App\Filament\Instructor\Resources\Tests\Pages;

use App\Filament\Instructor\Resources\Tests\TestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTest extends EditRecord
{
    protected static string $resource = TestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
