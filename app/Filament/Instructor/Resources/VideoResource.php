<?php

namespace App\Filament\Instructor\Resources;

use App\Filament\Instructor\Resources\VideoResource\Pages;
use App\Models\Video;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use Filament\Tables;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;
    // protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
           Section::make('Video Information')
                ->schema([
                   Select::make('course_id')
                        ->relationship('course', 'title')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->default(request('course_id'))
                        ->helperText('Select the course this video belongs to'),

                   TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Enter video title'),

                   Textarea::make('description')
                        ->rows(3)
                        ->placeholder('Describe what this video covers'),
                ])
                ->columns(2),

           Section::make('Video Content')
                ->schema([
                   FileUpload::make('video_url')
    ->label('Video File')
    ->directory('videos')
    ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/webm'])
    ->required()
    ->maxSize(1024 * 500)
    ->helperText('Max size set to: ' . (1024 * 500) . ' KB')
    ->rules(['max:' . (1024 * 500)]) // Explicitly set validation rule
    ->columnSpanFull(),


                   FileUpload::make('thumbnail_url')
                        ->label('Thumbnail')
                        ->image()
                        ->directory('video-thumbnails')
                        ->imageEditor()
                        ->helperText('Upload a thumbnail image for the video'),

                   TextInput::make('duration_seconds')
                        ->label('Duration (seconds)')
                        ->numeric()
                        ->minValue(0)
                        ->helperText('Enter video duration in seconds'),

                   TextInput::make('sort_order')
                        ->label('Sort Order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Order of this video in the course (0 = first)'),
                ])
                ->columns(2),
           Section::make('Publishing')
                ->schema([
                   Toggle::make('is_published')
                        ->label('Publish this video')
                        ->default(true)
                        ->onColor('success')
                        ->offColor('danger')
                        ->helperText('Unpublished videos will not be visible to students'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Video::with('course'))
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->circular()
                    ->size(50),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('duration_seconds')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state ? gmdate('H:i:s', $state) : 'Not set')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published Status')
                    ->boolean()
                    ->trueLabel('Published videos')
                    ->falseLabel('Draft videos')
                    ->native(false),
            ])
            ->actions([
               ViewAction::make(),
               EditAction::make(),
               DeleteAction::make(),
            ])
            ->bulkActions([
               BulkActionGroup::make([
                   DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
