<?php

namespace App\Filament\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalEmployees = User::all()->count();
        $totalHolidyas = Holiday::where('type','pending')->count();
        $totalTimesheet = Timesheet::all()->count();

        return [
            Stat::make('Employees', $totalEmployees),
            Stat::make('Pending Holidays', $totalHolidyas),
            Stat::make('Timesheets', $totalTimesheet),
        ];
    }
}
