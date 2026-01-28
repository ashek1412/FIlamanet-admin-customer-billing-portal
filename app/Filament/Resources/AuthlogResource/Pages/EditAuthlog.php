<?php

namespace App\Filament\Resources\AuthlogResource\Pages;

use App\Filament\Resources\AuthlogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuthlog extends EditRecord
{
    protected static string $resource = AuthlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
