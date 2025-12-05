<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStats extends BaseWidget
{
    protected function getStats(): array
    {
        $studentsCount = User::where('role', 'student')->count();
        $coursesCount = Course::count();
        $enrollmentsCount = Enrollment::count();

        return [
            Stat::make('Students', (string) $studentsCount)
                ->icon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Courses', (string) $coursesCount)
                ->icon('heroicon-m-rectangle-stack')
                ->color('info'),

            Stat::make('Enrollments', (string) $enrollmentsCount)
                ->icon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('pending approvals', (string) Course::where('approval_status', 'pending')->count())
                ->icon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('rejected courses', (string) Course::where('approval_status', 'rejected')->count())
                ->icon('heroicon-m-x-circle')
                ->color('danger'),
            Stat::make('approved courses', (string) Course::where('approval_status', 'approved')->count())
                ->icon('heroicon-m-check-circle')
                ->color('success'),
        
        ];
    }
}


