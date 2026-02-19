<?php

namespace App\Filament\Personal\Resources\Timesheets;

use App\Filament\Personal\Resources\Timesheets\Pages\CreateTimesheets;
use App\Filament\Personal\Resources\Timesheets\Pages\EditTimesheets;
use App\Filament\Personal\Resources\Timesheets\Pages\ListTimesheets;
use App\Filament\Personal\Resources\Timesheets\Schemas\TimesheetsForm;
use App\Filament\Personal\Resources\Timesheets\Tables\TimesheetsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\Timesheet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TimesheetsResource extends Resource
{
    protected static ?string $model = Timesheet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::TableCells;

     public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id)->orderBy('day_in', 'desc');
    }
    protected static ?string $recordTitleAttribute = 'Timesheets';

    public static function form(Schema $schema): Schema
    {
        return TimesheetsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TimesheetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTimesheets::route('/'),
            'create' => CreateTimesheets::route('/create'),
            'edit' => EditTimesheets::route('/{record}/edit'),
        ];
    }
}
