<?php

namespace App\Filament\App\Resources\ChangeRequestResource\Pages;

use App\Filament\App\Resources\ChangeRequestResource;
use Filament\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditChangeRequest extends EditRecord
{
    protected static string $resource = ChangeRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
          //  Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Request updated';
    }

    protected function getFormActions(): array
    {

        return [
            Action::make('save')->label('Update')
                ->visible(Auth::user()->is_admin),
            Action::make('cancel')
                ->label('Cancel')
                ->url(ChangeRequestResource::getUrl('index')) // Replace with your desired URL
                ->color('gray') // Change the color
                ->outlined() // Make it outlined
        ];

    }

}
