<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserActivityChart extends ChartWidget
{
    protected static ?string $heading = 'User Login Activity (Last 7 Days)';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        // Get top 10 users by login count in last 7 days
        $userLogins = DB::table('authentication_log')
            ->join('users', 'authentication_log.authenticatable_id', '=', 'users.id')
            ->select('users.name', DB::raw('COUNT(*) as login_count'))
            ->where('authentication_log.login_at', '>=', now()->subDays(7))
            ->where('authentication_log.login_successful', true)
            ->where('authentication_log.authenticatable_type', 'App\\Models\\User')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('login_count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Login Count',
                    'data' => $userLogins->pluck('login_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(199, 199, 199, 0.7)',
                        'rgba(83, 102, 255, 0.7)',
                        'rgba(255, 99, 255, 0.7)',
                        'rgba(50, 205, 50, 0.7)',
                    ],
                    'borderColor' => [
                        'rgb(54, 162, 235)',
                        'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 206, 86)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)',
                        'rgb(199, 199, 199)',
                        'rgb(83, 102, 255)',
                        'rgb(255, 99, 255)',
                        'rgb(50, 205, 50)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $userLogins->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
