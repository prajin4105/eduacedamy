<?php

namespace App\Filament\Instructor\Widgets;

use App\Models\Course;
use App\Models\Enrollment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InstructorStats extends BaseWidget
{
    protected function getStats(): array
    {
        $instructorId = auth()->id();

        $coursesCount = Course::where('instructor_id', $instructorId)->count();
        $enrollmentsCount = Enrollment::whereHas('course', function ($q) use ($instructorId) {
            $q->where('instructor_id', $instructorId);
        })->count();

        $completionRate = 0;
        if ($coursesCount > 0) {
            $completed = Enrollment::whereHas('course', function ($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            })->where('progress_percentage', '>=', 100)->count();
            $completionRate = round(($completed / max($enrollmentsCount, 1)) * 100, 1);
        }

        return [
            Stat::make('My Courses', (string) $coursesCount)
                ->icon('heroicon-m-rectangle-stack')
                ->color('info'),

            Stat::make('Total Enrollments', (string) $enrollmentsCount)
                ->icon('heroicon-m-user-group')
                ->color('success'),

            // Stat::make('Avg Completion', $completionRate . '%')
            //     ->icon('heroicon-m-check-circle')
            //     ->color('primary'),
        ];
    }
}


