<?php

namespace App\Filament\Default\Resources\Roles\Pages;

use App\Filament\Default\Resources\Roles\RoleResource;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\CreateRole as BaseCreateRole;

class CreateRole extends BaseCreateRole
{
    protected static string $resource = RoleResource::class;
}
