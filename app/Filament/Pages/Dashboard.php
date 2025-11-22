<?php
namespace App\Filament\Pages;



use Filament\Pages\Dashboard\Concerns\HasFiltersAction;


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

        ];
    }
}
