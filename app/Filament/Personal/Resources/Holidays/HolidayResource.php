<?php

namespace App\Filament\Personal\Resources\Holidays;

use App\Filament\Personal\Resources\Holidays\Pages\CreateHoliday;
use App\Filament\Personal\Resources\Holidays\Pages\EditHoliday;
use App\Filament\Personal\Resources\Holidays\Pages\ListHolidays;
use App\Filament\Personal\Resources\Holidays\Schemas\HolidayForm;
use App\Filament\Personal\Resources\Holidays\Tables\HolidaysTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Holiday;
use Illuminate\Support\Facades\Auth;

class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;

    protected static ?string $navigationLabel = 'Vacaciones';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

            //para mstrar el umero de vacaciones pendientes
            public static function getNavigationBadge(): ?string
                {

                    return parent::getEloquentQuery()->where('user_id', Auth::user()->id)->where('type', 'pending')->count();
                }
            //para cambiar el color del numero
            public static function getNavigationBadgeColor(): ?string
                {
                    return parent::getEloquentQuery()->where('user_id', Auth::user()->id)->where('type', 'pending')->count() > 0 ? 'warning' : 'primary';
                }

        public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id);
    }

            //para mostra un mensajeal pasar por el numero de vacaciones pendientes
            public static function getNavigationBadgeTooltip(): ?string
                    {
                        return 'The number of pending holidays ';
                    }

    protected static ?string $recordTitleAttribute = 'Holiday';

    public static function form(Schema $schema): Schema
    {
        return HolidayForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HolidaysTable::configure($table);
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
            'index' => ListHolidays::route('/'),
            'create' => CreateHoliday::route('/create'),
            'edit' => EditHoliday::route('/{record}/edit'),
        ];
    }
}
