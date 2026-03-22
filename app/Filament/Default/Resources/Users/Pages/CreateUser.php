<?php

namespace App\Filament\Default\Resources\Users\Pages;

use App\Filament\Default\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
