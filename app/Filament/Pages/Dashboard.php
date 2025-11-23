<?php
namespace App\Filament\Pages;



use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Support\Enums\ActionSize;


class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersAction;

    public function getColumns(): int|string|array
    {
        return 12;
    }
    public static function canAccess(): bool
    {
        return auth()->user()->is_admin;
    }



 

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\UserActivityChart::class,
        ];
    }
}
