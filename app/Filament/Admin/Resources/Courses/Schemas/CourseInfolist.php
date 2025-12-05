<?php

namespace App\Filament\Admin\Resources\Courses\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;


class CourseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Course Information')
                    ->schema([
                        ImageEntry::make('image')
                            ->label('Course Image')
                            ->circular(),
                        TextEntry::make('title')
                            ->label('Title')
                            ->size('lg')
                            ->weight('bold'),
                        TextEntry::make('slug')
                            ->label('Slug'),
                        TextEntry::make('instructor.name')
                            ->label('Instructor'),
                        TextEntry::make('description')
                            ->label('Description')
                            ->columnSpanFull()
                            ->html(),
                        TextEntry::make('price')
                            ->label('Price')
                            ->money('USD'),
                        TextEntry::make('level')
                            ->label('Level')
                            ->badge()
                            ->color(fn (string $state): string => match (strtolower($state)) {
                                'beginner' => 'success',
                                'intermediate' => 'warning',
                                'advanced' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('language')
                            ->label('Language'),
                        TextEntry::make('duration_in_minutes')
                            ->label('Duration')
                            ->formatStateUsing(fn ($state) => $state ? "{$state} minutes" : 'Not set'),
                    ])
                    ->columns(2),
                Section::make('Course Content')
                    ->schema([
                        TextEntry::make('what_you_will_learn')
                            ->label('What You Will Learn')
                            ->columnSpanFull()
                            ->placeholder('Not provided'),
                        TextEntry::make('requirements')
                            ->label('Requirements')
                            ->columnSpanFull()
                            ->placeholder('Not provided'),
                    ]),
                Section::make('Approval Status')
                    ->schema([
                        TextEntry::make('approval_status')
                            ->label('Approval Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),
                       IconEntry::make('is_published')
    ->boolean(),
                        TextEntry::make('approved_at')
                            ->label('Approved At')
                            ->dateTime()
                            ->placeholder('Not approved yet'),
                        TextEntry::make('approver.name')
                            ->label('Approved By')
                            ->placeholder('N/A'),
                        TextEntry::make('approval_reason')
                            ->label('Rejection Reason')
                            ->columnSpanFull()
                            ->placeholder('N/A')
                            ->visible(fn ($record) => $record->approval_status === 'rejected'),
                    ])
                    ->columns(2),
            ]);
    }
}

