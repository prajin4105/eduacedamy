<?php

namespace App\Filament\Instructor\Resources\CourseResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions;

class VideosRelationManager extends RelationManager
{
    protected static string $relationship = 'videos'; // assumes Course::videos() exists

     public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('title')
                ->label('Video Title')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->maxLength(500),

            Forms\Components\TextInput::make('video_url')
                ->label('Video URL')
                ->required(),

            Forms\Components\TextInput::make('duration_in_seconds')
                ->label('Duration (seconds)')
                ->numeric(),

            Forms\Components\FileUpload::make('thumbnail_url')
                ->label('Thumbnail')
                ->image()
                ->directory('video-thumbnails'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('duration_in_seconds')->label('Duration'),
                Tables\Columns\TextColumn::make('video_url')->limit(30)->label('Video URL'),
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }
}
