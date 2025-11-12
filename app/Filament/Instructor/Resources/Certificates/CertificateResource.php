<?php

namespace App\Filament\Instructor\Resources\Certificates;

use App\Filament\Instructor\Resources\Certificates\Pages\CreateCertificate;
use App\Filament\Instructor\Resources\Certificates\Pages\EditCertificate;
use App\Filament\Instructor\Resources\Certificates\Pages\ListCertificates;
use App\Filament\Instructor\Resources\Certificates\Schemas\CertificateForm;
use App\Filament\Instructor\Resources\Certificates\Tables\CertificatesTable;
use App\Models\Certificate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use UnitEnum;
use Filament\Actions\DeleteBulkAction;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;




    public static function form(Schema $schema): Schema
    {
         return $schema->schema([
            Forms\Components\Select::make('user_id')->relationship('user', 'name')->required(),
            Forms\Components\Select::make('course_id')->relationship('course', 'title')->required(),
            Forms\Components\TextInput::make('certificate_number')->required(),
            Forms\Components\TextInput::make('certificate_path'),
            Forms\Components\DateTimePicker::make('issued_at')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
       return $table->columns([
            Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
            Tables\Columns\TextColumn::make('course.title')->label('Course')->searchable(),
            Tables\Columns\TextColumn::make('certificate_number')->searchable(),
            Tables\Columns\TextColumn::make('issued_at')->dateTime(),
        ])->actions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
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
            'index' => ListCertificates::route('/'),
            'create' => CreateCertificate::route('/create'),
            'edit' => EditCertificate::route('/{record}/edit'),
        ];
    }
}
