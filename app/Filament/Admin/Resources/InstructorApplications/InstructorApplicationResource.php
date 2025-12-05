<?php

namespace App\Filament\Admin\Resources\InstructorApplications;

use App\Filament\Admin\Resources\InstructorApplications\Pages\CreateInstructorApplication;
use App\Filament\Admin\Resources\InstructorApplications\Pages\EditInstructorApplication;
use App\Filament\Admin\Resources\InstructorApplications\Pages\ListInstructorApplications;
use App\Filament\Admin\Resources\InstructorApplications\Pages\ViewInstructorApplication;
use App\Filament\Admin\Resources\InstructorApplications\Schemas\InstructorApplicationForm;
use App\Filament\Admin\Resources\InstructorApplications\Schemas\InstructorApplicationInfolist;
use App\Filament\Admin\Resources\InstructorApplications\Tables\InstructorApplicationsTable;
use App\Models\InstructorApplication;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InstructorApplicationResource extends Resource
{
    protected static ?string $model = InstructorApplication::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return InstructorApplicationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InstructorApplicationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstructorApplicationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInstructorApplications::route('/'),
            'create' => CreateInstructorApplication::route('/create'),
            'view' => ViewInstructorApplication::route('/{record}'),
            'edit' => EditInstructorApplication::route('/{record}/edit'),
        ];
    }
}
