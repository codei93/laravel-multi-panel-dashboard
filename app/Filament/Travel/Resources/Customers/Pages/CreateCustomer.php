<?php

namespace App\Filament\Travel\Resources\Customers\Pages;

use App\Filament\Travel\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
