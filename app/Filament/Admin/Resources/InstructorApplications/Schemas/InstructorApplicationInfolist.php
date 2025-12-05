<?php

namespace App\Filament\Admin\Resources\InstructorApplications\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Actions\Action;

class InstructorApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Applicant Information')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Name'),
                        TextEntry::make('user.email')
                            ->label('Email'),
                        TextEntry::make('applied_at')
                            ->label('Applied At')
                            ->dateTime()
                            ->placeholder('Not set'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'suspended' => 'gray',
                                default => 'gray',
                            }),
                    ])
                    ->columns(2),
                Section::make('Application Details')
                    ->schema([
                        TextEntry::make('bio')
                            ->label('Bio')
                            ->columnSpanFull()
                            ->placeholder('No bio provided'),
                        TextEntry::make('portfolio_url')
                            ->label('Portfolio URL')
                            ->url(fn ($record) => $record->portfolio_url, shouldOpenInNewTab: true)
                            ->placeholder('No portfolio URL provided'),
                        TextEntry::make('document_path')
                            ->label('Document')
                            ->formatStateUsing(fn ($state) => $state ? 'Document Available' : 'No Document')
                            ->suffixAction(
                                Action::make('download')
                                    ->label('Download')
                                    ->icon('heroicon-o-arrow-down-tray')
                                    ->url(fn ($record) => $record->document_path
                                        ? route('instructor-applications.download', $record->id)
                                        : null
                                    )
                                    ->openUrlInNewTab()
                                    ->visible(fn ($record) => !empty($record->document_path))
                            ),
                    ])
                    ->columns(2),
                Section::make('Review Information')
                    ->schema([
                        TextEntry::make('reviewed_at')
                            ->label('Reviewed At')
                            ->dateTime()
                            ->placeholder('Not reviewed yet'),
                        TextEntry::make('reviewer.name')
                            ->label('Reviewed By')
                            ->placeholder('N/A'),
                        TextEntry::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->placeholder('N/A')
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->status === 'rejected'),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record->reviewed_at !== null),
            ]);
    }
}
