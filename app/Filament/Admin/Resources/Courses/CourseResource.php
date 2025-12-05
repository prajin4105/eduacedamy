<?php

namespace App\Filament\Admin\Resources\Courses;

// use App\Filament\Instructor\Resources\CourseResource\Pages;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\Courses\Pages\ListCourses;
use App\Filament\Admin\Resources\Courses\Pages\CreateCourse;
use App\Filament\Admin\Resources\Courses\Pages\EditCourse;
use App\Filament\Admin\Resources\Courses\Pages\ViewCourse;
use App\Filament\Admin\Resources\Courses\Schemas\CourseInfolist;
use Filament\Forms\Components\Select;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkAction;
use Filament\Actions\Action;




class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Approved Courses';

    // Add this method to filter only approved courses
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereRaw("approval_status = 'approved'")
            ->with(['instructor', 'categories']);
    }

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

    public static function infolist(Schema $schema): Schema
    {
        return CourseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('approved_at', 'desc')
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
                    Tables\Columns\TagsColumn::make('categories.name')
    ->label('Categories')
    ->separator(', ')
    ->limit(3), // shows only first 3

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
                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Approved')
                    ->dateTime()
                    ->sortable()
                    ->since(),
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
                ViewAction::make(),
                Action::make('unpublish')
                    ->label('Unpublish')
                    ->icon('heroicon-o-eye-slash')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Unpublish Course')
                    ->modalDescription('Are you sure you want to unpublish this course? It will no longer be visible to students.')
                    ->action(function (Course $record) {
                        $record->update([
                            'is_published' => false,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Course Unpublished')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Course $record) => $record->is_published),
                Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Publish Course')
                    ->modalDescription('Are you sure you want to publish this course? It will be visible to students.')
                    ->action(function (Course $record) {
                        $record->update([
                            'is_published' => true,
                            'published_at' => now(),
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Course Published')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Course $record) => !$record->is_published),
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
        return [
            //
        ];
    }

public static function getPages(): array
{
    return [
        'index' => ListCourses::route('/'),
        'create' => CreateCourse::route('/create'),
        'view' => ViewCourse::route('/{record}'),
        'edit' => EditCourse::route('/{record}/edit'),
    ];
}

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->whereRaw("approval_status = 'approved'")
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // Show badge with count in navigation
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereRaw("approval_status = 'approved'")->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
