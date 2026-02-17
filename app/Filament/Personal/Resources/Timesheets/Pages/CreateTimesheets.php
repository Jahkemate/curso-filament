<?php

namespace App\Filament\Personal\Resources\Timesheets\Pages;

use App\Filament\Personal\Resources\Timesheets\TimesheetsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTimesheets extends CreateRecord
{
    protected static string $resource = TimesheetsResource::class;
     protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['user_id'] = Auth::id();

    return $data;
}
}
