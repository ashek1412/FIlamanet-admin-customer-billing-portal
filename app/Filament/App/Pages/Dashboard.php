<?php

namespace App\Filament\App\Pages;



class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.app.pages.dashboard-page';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Dashboard';

    protected $tracking;
    protected $trackingReesult = null;
    public $statementOfAccount;
    public $statusFilter = '';
    public $typeFilter = '';
    public $filteredValue = '';
    protected $cus_code;

    public function getHeading(): string
    {
        $company = (auth()->user()->is_admin) ? "Admin Dashboard" : auth()->user()->name . "'s Dashboard";
        return $company;
    }

    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\App\Widgets\AccountStatement::class
        ];
    }

    public function getColumnSpan(): int | string | array
    {
        // Return the desired column span, for example:
        return 'full'; // or whatever value/array is appropriate for your layout
    }
    public function getColumnStart(): int|string|null
    {
        // Return the desired column start value, or null if not needed
        return null;
    }
}
