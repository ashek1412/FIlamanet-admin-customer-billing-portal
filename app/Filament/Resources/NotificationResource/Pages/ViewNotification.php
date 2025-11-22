<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;


class ViewNotification extends ViewRecord
{
    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancel')
                ->label('Back')
                ->url(NotificationResource::getUrl('index')) // Replace with your desired URL
                // Change the color

        ];
    }


    public function getFormActions(): array
    {

        return [
             Action::make('save')->label('Update')->disabled($this->getRecord()->status=='sent')
            ->visible(Auth::user()->is_admin),

        ];

    }





}
