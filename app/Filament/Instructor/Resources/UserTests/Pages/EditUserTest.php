<?php

namespace App\Filament\Instructor\Resources\UserTests\Pages;

use App\Filament\Instructor\Resources\UserTests\UserTestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUserTest extends EditRecord
{
    protected static string $resource = UserTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
