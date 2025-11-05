<?php

namespace App\Filament\Admin\Resources;

use App\Models\UserTest;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\UserTestResource\Pages;
use Filament\Schemas\Schema;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class UserTestResource extends Resource
{
    protected static ?string $model = UserTest::class;
   protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static \UnitEnum|string|null $navigationGroup = 'Courses';

     public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('user_id')->relationship('user', 'name')->required(),
            Forms\Components\Select::make('test_id')->relationship('test', 'title')->required(),
            Forms\Components\TextInput::make('score')->numeric()->required(),
            Forms\Components\Toggle::make('passed')->required(),
            Forms\Components\DateTimePicker::make('attempted_at')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
            Tables\Columns\TextColumn::make('test.title')->label('Test')->searchable(),
            Tables\Columns\TextColumn::make('score'),
            Tables\Columns\IconColumn::make('passed')->boolean(),
            Tables\Columns\TextColumn::make('attempted_at')->dateTime(),
        ])->actions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserTests::route('/'),
        ];
    }
}


