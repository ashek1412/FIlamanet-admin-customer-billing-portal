<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;


class EditNotification extends EditRecord
{
    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
          //  Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Notification updated';
    }

    protected function getFormActions(): array
    {

        return [
             Action::make('save')->label('Update')->disabled($this->getRecord()->status=='sent')
            ->visible(Auth::user()->is_admin),
            Action::make('cancel')
                ->label('Cancel')
                ->url(NotificationResource::getUrl('index')) // Replace with your desired URL
                ->color('gray') // Change the color
                ->outlined() // Make it outlined
        ];

    }





}
