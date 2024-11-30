<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('User', User::all()->count())
                ->description('Total users')
                ->descriptionIcon('heroicon-s-user-circle')
                ->color('success'),
            Stat::make('Public', Post::query()->where('status', '1')->count())
                ->description('Number of published post')
                ->descriptionIcon('heroicon-o-newspaper')
                ->color('info'),
            Stat::make('Draft', Post::query()->where('status', '2')->count())
                ->description('Number of draft post')
                ->descriptionIcon('heroicon-o-newspaper')
                ->color('secondary'),
            Stat::make('Pending', Post::query()->where('status', '3')->count())
                ->description('Number of pending post')
                ->descriptionIcon('heroicon-o-newspaper')
                ->color('danger'),
        ];
    }
}
