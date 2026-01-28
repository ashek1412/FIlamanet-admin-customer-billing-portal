<?php

namespace App\Filament\Resources\AuthlogResource\Pages;

use App\Filament\Resources\AuthlogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuthlogs extends ListRecords
{
    protected static string $resource = AuthlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //  Actions\CreateAction::make(),
        ];
    }
}
