<?php

namespace App\Filament\Personal\Resources\Timesheets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class TimesheetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('calendar.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable()
                    ->searchable(),
                TextColumn::make('day_in')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('day_out')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'work' => 'Working',
                        'pause' => 'In Pause',
                     ])
            ])
            ->recordActions([ //Esto es para los botones de accion de Editas y Eliminar
                EditAction::make(),
                DeleteAction::make(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make('table')->exports([
                        ExcelExport::make('table')->fromTable()
                        ->withFilename('Timesheet_'.date('Y-m-d' . '_export'))
                        ->withColumns([
                            Column::make('User'),
                            Column::make('created_at'),
                            Column::make('deleted_at'),
                        ]),
                        ExcelExport::make('form')->fromForm()
                        //para ponerele nombre y el tido de archivo a guardar
                        ->askForFilename()
                        ->askForWriterType(),
                    ])
                ]),
            ]);
    }
}
