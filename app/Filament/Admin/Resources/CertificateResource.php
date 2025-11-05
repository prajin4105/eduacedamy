<?php

namespace App\Filament\Admin\Resources;

use App\Models\Certificate;
use App\Filament\Admin\Enums\NavigationGroup;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
// use Filament\Resources\Table;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\CertificateResource\Pages;
use Filament\Schemas\Schema;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;
    // protected static ?string $navigationIcon = 'heroicon-o-badge-check';
    // protected static ?NavigationGroup $navigationGroup = NavigationGroup::COURSES;

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificates::route('/'),
        ];
    }
}
