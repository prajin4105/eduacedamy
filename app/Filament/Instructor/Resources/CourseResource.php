<?php

namespace App\Filament\Instructor\Resources;

use App\Filament\Instructor\Resources\CourseResource\Pages;
use App\Filament\Instructor\Resources\CourseResource\RelationManagers\VideosRelationManager;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use UnitEnum;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;



class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?int $navigationSort = 1;
     public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Course Information')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->rules(['alpha_dash']),
                        Section::make('Categories')
    ->schema([
        Select::make('categories')
            ->label('Assign Categories')
            ->multiple()
            ->preload()
            ->relationship('categories', 'name') // ✅ Magic: auto uses belongsToMany
            ->required(),
    ])
    ->columns(1),
    //instructor role==insturctor_id
    Section::make('Instructor')
    ->schema([
        Select::make('instructor_id')
            ->label('Select Instructor')
            ->required()
            ->preload()
            ->relationship('instructor', 'name') // ✅ Magic: auto uses belongsToMany
            ->searchable()
            ->placeholder('Select an instructor'),
    ])
    ->columns(1),

                    RichEditor::make('description')
                        ->required()
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'bulletList',
                            'orderedList',
                            'link',
                        ]),
                ])
                ->columns(2),

            Section::make('Course Details')
                ->schema([
                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        ->prefix('$')
                        ->minValue(0)
                        ->required()
                        ->default(0),

                    Forms\Components\Select::make('level')
                        ->options([
                            'beginner' => 'Beginner',
                            'intermediate' => 'Intermediate',
                            'advanced' => 'Advanced',
                        ])
                        ->required()
                        ->default('beginner'),

                    Forms\Components\Select::make('language')
                        ->options([
                            'english' => 'English',
                            'spanish' => 'Spanish',
                            'french' => 'French',
                            'german' => 'German',
                            'chinese' => 'Chinese',
                            'japanese' => 'Japanese',
                        ])
                        ->required()
                        ->default('english'),

                    Forms\Components\TextInput::make('duration_in_minutes')
                        ->label('Duration (minutes)')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->suffix('minutes'),
                ])
                ->columns(2),

            Section::make('Course Content')
                ->schema([
                    Forms\Components\Textarea::make('what_you_will_learn')
                        ->label('What students will learn')
                        ->rows(4)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('requirements')
                        ->label('Course requirements')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),

            Section::make('Media & Publishing')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Course Image')
                        ->image()
                        ->imageEditor()
                        ->directory('courses')
                        ->visibility('public'),

                    Forms\Components\Toggle::make('is_published')
                        ->label('Publish this course')
                        ->onColor('success')
                        ->offColor('danger')
                        ->default(false),

                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Publication Date')
                        ->visible(fn ($get) => $get('is_published'))
                        ->default(now()),
                ])
                ->columns(2),
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

                Tables\Columns\TextColumn::make('approval_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            VideosRelationManager::class,
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
