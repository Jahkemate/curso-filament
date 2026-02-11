<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\City;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Collection;



use App\Models\State;
use Dom\Text;
use Symfony\Component\Console\Input\Input;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Info')
            ->columns(3)
            ->schema([
                   TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->hiddenOn('edit')
                    ->required(),   
            ]),

              Section::make('Address Info')
            ->columns(3)
            ->schema([
                Select::make('country_id')
                    ->relationship(name: 'country', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Set $set){
                        $set('state_id', null);
                        $set('city_id', null);
                    })
                    ->required(),
                Select::make('state_id')
                    ->options(fn (Get $get): Collection => State::query()
                        ->where('country_id', $get('country_id'))
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function (Set $set){
                        $set('city_id', null);
                    })
                    ->live()
                    ->required(),
                Select::make('city_id')
                    ->options(fn (Get $get): Collection =>  City::query()
                        ->where('state_id', $get('state_id'))
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                TextInput::make('address')
                    ->required(),
                TextInput::make('postal_code')
                    ->required(),

            ])
            ]);
    }
}
