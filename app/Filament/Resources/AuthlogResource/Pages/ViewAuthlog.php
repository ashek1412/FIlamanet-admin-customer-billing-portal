<?php

namespace App\Filament\Resources\AuthlogResource\Pages;


use App\Filament\Resources\AuthlogResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAuthlog extends ViewRecord
{
    protected static string $resource = AuthlogResource::class;

    protected function getActions(): array
    {
        return [
            //  Actions\EditAction::make(),
        ];
    }
}
