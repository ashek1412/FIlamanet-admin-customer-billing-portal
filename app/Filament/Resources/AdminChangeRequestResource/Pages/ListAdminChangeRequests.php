<?php

namespace App\Filament\Resources\AdminChangeRequestResource\Pages;

use App\Filament\Resources\AdminChangeRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminChangeRequests extends ListRecords
{
    protected static string $resource = AdminChangeRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
