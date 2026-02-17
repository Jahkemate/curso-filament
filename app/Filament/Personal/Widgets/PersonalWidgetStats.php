<?php

namespace App\Filament\Personal\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PersonalWidgetStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Holidays', $this->getPendingHoliday(Auth::user())),
            Stat::make('Approved Holiday', $this->getApprovedHoliday(Auth::user())),
            Stat::make('Total Work', $this->getTotalWork(Auth::user())),
            Stat::make('Total Pause', $this->getTotalPause(Auth::user())),
        ];
    }

    //Esta funcon muestra en el widget los Holiday que estan en pending
    protected function getPendingHoliday( User $user){
        $totalPendingHolidays = Holiday::where('user_id',$user->id)
        ->where('type','pending')->get()->count();

        return $totalPendingHolidays;
    }

    //Esta funcon muestra en el widget los Holiday que estan en pending
    protected function getApprovedHoliday( User $user){
        $totalApprovedHolidays = Holiday::where('user_id',$user->id)
        ->where('type','approved')->get()->count();

        return $totalApprovedHolidays;
    }

    protected function getTotalWork(User $user){
        $timesheets = Timesheet::where('user_id', $user->id)
        ->where('type', 'work')->whereDate('created_at', Carbon::today())->get();
        $sumSeconds = 0;

            foreach($timesheets as $timesheet){
                $startTime = Carbon::parse($timesheet->day_in);
                $finishTime = Carbon::parse($timesheet->day_out);

                $totalDuration = $finishTime->diffInSeconds($startTime);
                $sumSeconds = $sumSeconds - $totalDuration;
            }
            $tiempoFormato = gmdate ("H:i:s", $sumSeconds);
        return $tiempoFormato;
    }

    // Para mostrar el tiempo total de pausa 
    protected function getTotalPause(User $user){
        $timesheets = Timesheet::where('user_id', $user->id)
        ->where('type', 'pause')->whereDate('created_at', Carbon::today())->get();
        $sumSeconds = 0;

            foreach($timesheets as $timesheet){
                $startTime = Carbon::parse($timesheet->day_in);
                $finishTime = Carbon::parse($timesheet->day_out);

                $totalDuration = $finishTime->diffInSeconds($startTime);
                $sumSeconds = $sumSeconds - $totalDuration;
            }
            $tiempoFormato = gmdate ("H:i:s", $sumSeconds);
        return $tiempoFormato;
    }

}
