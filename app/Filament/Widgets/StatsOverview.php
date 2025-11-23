<?php

namespace App\Filament\Widgets;

use App\Models\ChangeRequest;
use App\Models\User;
use App\Models\UserNotification;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            
            Stat::make('Change Requests', ChangeRequest::count())
                ->description('Total change requests')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning'),
            
            Stat::make('Notifications', UserNotification::count())
                ->description('Total notifications sent')
                ->descriptionIcon('heroicon-m-bell')
                ->color('info'),
        ];
    }
}
