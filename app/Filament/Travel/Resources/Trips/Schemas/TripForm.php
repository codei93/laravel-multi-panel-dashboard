<?php

namespace App\Filament\Travel\Resources\Trips\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TripForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
            ]);
    }
}
