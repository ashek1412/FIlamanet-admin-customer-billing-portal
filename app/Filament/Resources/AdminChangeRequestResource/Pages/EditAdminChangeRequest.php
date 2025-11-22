<?php

namespace App\Filament\Resources\AdminChangeRequestResource\Pages;

use App\Filament\App\Resources\ChangeRequestResource;
use App\Filament\Resources\AdminChangeRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminChangeRequest extends EditRecord
{
    protected static string $resource = AdminChangeRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        // Redirect to a custom route
        return $this->getResource()::getUrl('index');

    }
}
