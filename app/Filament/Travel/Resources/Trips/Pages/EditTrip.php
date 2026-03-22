<?php

namespace App\Filament\Travel\Resources\Trips\Pages;

use App\Filament\Travel\Resources\Trips\TripResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrip extends EditRecord
{
    protected static string $resource = TripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
