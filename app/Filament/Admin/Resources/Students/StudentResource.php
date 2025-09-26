<?php

namespace App\Filament\Admin\Resources\Students;

use App\Filament\Admin\Resources\Students\Pages\CreateStudent;
use App\Filament\Admin\Resources\Students\Pages\EditStudent;
use App\Filament\Admin\Resources\Students\Pages\ListStudents;
use App\Filament\Admin\Resources\Students\Schemas\StudentForm;
use App\Filament\Admin\Resources\Students\Tables\StudentsTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Students';

    protected static ?string $modelLabel = 'Student';

    protected static ?string $pluralModelLabel = 'Students';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        // Only show users who are students
        return parent::getEloquentQuery()->where('role', 'student');
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
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }
}
