<?php

namespace App\Filament\Personal\Resources\Timesheets\Pages;

use App\Filament\Imports\TimesheetImporter;
use App\Filament\Personal\Resources\Timesheets\TimesheetsResource;
use App\Models\Timesheet;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetsResource::class;

    protected function getHeaderActions(): array
    {
        $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->orderBy('id','desc')->first();
        if($lastTimesheet == null){
            return [
                Action::make('inwork')
                ->label('Entrar a trabajar')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (){
                    $user = Auth::user();
                    $timesheet = new Timesheet();
                    $timesheet->calendar_id = 1;
                    $timesheet->user_id = $user->id;
                    $timesheet->day_in = Carbon::now();

                    $timesheet->type = 'work';
                    $timesheet->save();
                    
                }),
                CreateAction::make(),
            ];
        }
        return [
        Action::make('inwork')
            ->label('Entrar a trabajar')
            ->color('success')
            // Para habilitar y desabilidatr los botones, dependiendo de la condicion
            ->visible(!$lastTimesheet->day_out == null)
            ->disabled($lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function (){
                $user = Auth::user();
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = $user->id;
                $timesheet->day_in = Carbon::now();

                $timesheet->type = 'work';
                $timesheet->save();

                 Notification::make()
                    ->title('Has entrado a trabajar')
                    ->body('Has comenzado atrabajar a las:' .Carbon::now())
                    ->color('success')
                    ->success()
                    ->send();
            }),
        Action::make('stopWork')
            ->label('Parar de trabajar')
            ->color('success')
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type !='pause') 
            ->disabled(!$lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function () use($lastTimesheet){
                $lastTimesheet->day_out = Carbon::now();
                $lastTimesheet->save();

                
                 Notification::make()
                    ->title('Has Parado de Trabajar')
                    ->color('success')
                    ->success()
                    ->send();
                
            }),
        Action::make('inPause')
            ->label('Comenzar Pausa')
            ->color('info')
            ->requiresConfirmation()
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type !='pause') 
            ->disabled(!$lastTimesheet->day_out == null)
            ->action(function () use($lastTimesheet){
                $lastTimesheet->day_out = Carbon::now();
                $lastTimesheet->save();
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = Auth::user()->id;
                $timesheet->day_in = Carbon::now();
                $timesheet->type = 'pause';
                $timesheet->save();

                
                 Notification::make()
                    ->title('Comienzas tu Pausa')
                    ->color('info')
                    ->info()
                    ->send();
            }),
        Action::make('stopPause')
            ->label('Parar Pausa')
            ->color('info')
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type =='pause') 
            ->disabled(!$lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function () use($lastTimesheet){
                $lastTimesheet->day_out = Carbon::now();
                $lastTimesheet->save();
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = Auth::user()->id;
                $timesheet->day_in = Carbon::now();
                $timesheet->type = 'work';
                $timesheet->save();

                
                 Notification::make()
                    ->title('Comienzas de Nuevo a Trabajar')
                    ->color('info')
                    ->info()
                    ->send();
            }),
        CreateAction::make(),
        ImportAction::make()
            ->importer(TimesheetImporter::class)
            ->label("Import")
            ->color('primary')
        
           
        ];
    }
}
