<?php

namespace App\Filament\Instructor\Resources;

use App\Filament\Instructor\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use UnitEnum;
use Filament\Actions;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static UnitEnum|string|null $navigationGroup = 'Content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Course Details')
                ->schema([
                    Hidden::make('instructor_id')
                        ->default(fn () => auth()->id())
                        ->dehydrated(true),

                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('slug')
                        ->maxLength(255)
                        ->helperText('Optional; will be generated if left empty'),

                    Textarea::make('description')
                        ->rows(4),

                    TextInput::make('price')
                        ->numeric()
                        ->minValue(0)
                        ->step('0.01'),
                ])->columns(2),

            Section::make('Media & Publishing')
                ->schema([
                    FileUpload::make('image')
                        ->directory('course-images')
                        ->image()
                        ->imageEditor(),

                    Toggle::make('is_published')
                        ->label('Published')
                        ->default(false),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getEloquentQuery())
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('usd')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published'),
            ])
            ->actions([
               Actions\Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Course $record) => static::getUrl('edit', ['record' => $record])),
               Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('instructor_id', auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}


