<?php

namespace App\Filament\Instructor\Resources;

use App\Filament\Instructor\Resources\CourseResource\Pages;
use App\Models\Course;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

//import section



class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    // protected static ?string $navigationGroup = 'Courses';
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
            ->query(Course::with('instructor'))
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->size(50),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('instructor.name')
                    ->label('Instructor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'beginner' => 'success',
                        'intermediate' => 'warning',
                        'advanced' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_in_minutes')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} min" : 'Not set')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ]),
                TernaryFilter::make('is_published')
                    ->label('Published Status')
                    ->boolean()
                    ->trueLabel('Published courses')
                    ->falseLabel('Draft courses')
                    ->native(false),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
