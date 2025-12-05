<?php

namespace App\Filament\Admin\Resources\Courses;

use App\Models\Course;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Schemas\Components\Section;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\Courses\Pages\ListPendingCourses;
use App\Filament\Admin\Resources\Courses\Pages\ViewPendingCourse;
use App\Filament\Admin\Resources\Courses\Schemas\CourseInfolist;
// Add this if you have an ApprovalStatus enum
// use App\Enums\ApprovalStatus;

class PendingCourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Pending Courses';

    protected static ?string $modelLabel = 'Pending Course';

    protected static ?string $pluralModelLabel = 'Pending Courses';

    // protected static ?string $navigationGroup = 'Course Management';

    protected static ?int $navigationSort = 1;

    // Set the slug for the URL
    protected static ?string $slug = 'pending-courses';

    // Only show pending courses in this resource
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereRaw("approval_status = 'pending'")
            ->with(['instructor', 'categories']);
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return CourseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->size(50),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('instructor.name')
                    ->label('Instructor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TagsColumn::make('categories.name')
                    ->label('Categories')
                    ->separator(', ')
                    ->limit(3),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->description(fn ($record) => $record->created_at->format('M d, Y')),
            ])
            ->filters([
                SelectFilter::make('level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ]),
                SelectFilter::make('instructor_id')
                    ->label('Instructor')
                    ->relationship('instructor', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                ViewAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Course')
                    ->modalDescription('Are you sure you want to approve this course? It will be published and visible to students.')
                    ->action(function (Course $record) {
                        $record->update([
                            'approval_status' => 'approved',
                            'is_published' => true,
                            'approved_at' => now(),
                            'approved_by' => auth()->id(),
                            'published_at' => now(),
                        ]);

                        // Send notification to instructor
                        $record->instructor->notify(new \App\Notifications\CourseApprovedNotification($record));

                        \Filament\Notifications\Notification::make()
                            ->title('Course Approved')
                            ->body("'{$record->title}' has been approved and published.")
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('approval_reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->maxLength(1000)
                            ->rows(4)
                            ->placeholder('Please explain why this course is being rejected...'),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Reject Course')
                    ->modalDescription('Please provide a reason for rejection. The instructor will be notified.')
                    ->action(function (Course $record, array $data) {
                        $record->update([
                            'approval_status' => 'rejected',
                            'approval_reason' => $data['approval_reason'],
                            'is_published' => false,
                            'approved_at' => now(),
                            'approved_by' => auth()->id(),
                        ]);

                        // Send notification to instructor
                        $record->instructor->notify(new \App\Notifications\CourseRejectedNotification($record));

                        \Filament\Notifications\Notification::make()
                            ->title('Course Rejected')
                            ->body("'{$record->title}' has been rejected.")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Approve Selected Courses')
                        ->modalDescription('Are you sure you want to approve the selected courses? They will be published and visible to students.')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each(function (Course $course) {
                                $course->update([
                                    'approval_status' => 'approved',
                                    'is_published' => true,
                                    'approved_at' => now(),
                                    'approved_by' => auth()->id(),
                                    'published_at' => now(),
                                ]);

                                // Send notification to instructor
                                $course->instructor->notify(new \App\Notifications\CourseApprovedNotification($course));
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Courses Approved')
                                ->body(count($records) . ' course(s) have been approved.')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('reject')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Textarea::make('approval_reason')
                                ->label('Rejection Reason')
                                ->required()
                                ->maxLength(1000)
                                ->rows(4)
                                ->placeholder('Please explain why these courses are being rejected...'),
                        ])
                        ->requiresConfirmation()
                        ->modalHeading('Reject Selected Courses')
                        ->modalDescription('Please provide a reason for rejection. All instructors will be notified.')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data) {
                            $records->each(function (Course $course) use ($data) {
                                $course->update([
                                    'approval_status' => 'rejected',
                                    'approval_reason' => $data['approval_reason'],
                                    'is_published' => false,
                                    'approved_at' => now(),
                                    'approved_by' => auth()->id(),
                                ]);

                                // Send notification to instructor
                                $course->instructor->notify(new \App\Notifications\CourseRejectedNotification($course));
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Courses Rejected')
                                ->body(count($records) . ' course(s) have been rejected.')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateHeading('No Pending Courses')
            ->emptyStateDescription('There are no courses waiting for approval.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPendingCourses::route('/'),
            'view' => ViewPendingCourse::route('/{record}'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->whereRaw("approval_status = 'pending'")
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // Show badge with count in navigation
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereRaw("approval_status = 'pending'")->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
