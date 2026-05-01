<?php

namespace App\Filament\Default\Resources\Roles\Pages;

use App\Filament\Default\Resources\Roles\RoleResource;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles as BaseListRoles;

class ListRoles extends BaseListRoles
{
    protected static string $resource = RoleResource::class;
}
