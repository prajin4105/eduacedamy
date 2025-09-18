<?php

namespace App\Filament\Admin\Resources\Courses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('instructor_id')
                    ->relationship('instructor', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('is_published')
                    ->required(),
                DateTimePicker::make('published_at'),
                Textarea::make('what_you_will_learn')
                    ->columnSpanFull(),
                Textarea::make('requirements')
                    ->columnSpanFull(),
                TextInput::make('level')
                    ->required()
                    ->default('beginner'),
                TextInput::make('language')
                    ->required()
                    ->default('english'),
                TextInput::make('duration_in_minutes')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
