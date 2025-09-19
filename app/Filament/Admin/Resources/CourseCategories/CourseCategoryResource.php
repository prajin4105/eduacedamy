<?php

namespace App\Filament\Admin\Resources\CourseCategories;

use App\Filament\Admin\Resources\CourseCategories\Pages\CreateCourseCategory;
use App\Filament\Admin\Resources\CourseCategories\Pages\EditCourseCategory;
use App\Filament\Admin\Resources\CourseCategories\Pages\ListCourseCategories;
use App\Filament\Admin\Resources\CourseCategories\Schemas\CourseCategoryForm;
use App\Filament\Admin\Resources\CourseCategories\Tables\CourseCategoriesTable;
use App\Models\Category;
use Filament\Forms;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section; // ✅ Schemas Section
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;

// use Filament\Forms\Components\Strk;

class CourseCategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;



       public static function form(Schema $schema): Schema
    {
        return $schema->schema([

                TextInput::make('name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', \Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('description'),
                FileUpload::make('image')->image()->directory('categories'),
                Toggle::make('is_active')->label('Active')->default(true),
                TextInput::make('sort_order')->numeric()->default(0),
            ]);
    }
  public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('sort_order')->sortable(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
          ->actions([
    EditAction::make(),
    DeleteAction::make(),
    RestoreAction::make(),
])
->bulkActions([
    DeleteBulkAction::make(),
    RestoreBulkAction::make(),
]);
    }
    public static function getRelations(): array
    {
        return [
            // Define any relations here if needed

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourseCategories::route('/'),
            'create' => CreateCourseCategory::route('/create'),
            'edit' => EditCourseCategory::route('/{record}/edit'),
        ];
    }
}
