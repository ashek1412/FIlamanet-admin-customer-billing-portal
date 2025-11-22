<?php

namespace App\Filament\App\Resources\ChangeRequestResource\Pages;

use App\Filament\App\Resources\ChangeRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChangeRequest extends CreateRecord
{
    protected static string $resource = ChangeRequestResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(), // Keep only the "Create" button
            $this->getCancelFormAction()
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['user_id'] = auth()->id();
        $data['status'] = 'submitted';

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        // Redirect to a custom route
        return route(ChangeRequestResource::getUrl('index'));
    }
}
