<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PostsChart extends ChartWidget
{
    protected static ?string $heading = 'Post chart';

    protected static string $color = 'info';

    protected static ?int $sort = 1;

    public ?string $filter = 'today';
    
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This week',
            'month' => 'This month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        switch ($activeFilter) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                break;
            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                break;
            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                break;
        }

        $data = Trend::model(Post::class)
            ->between(
                start: $start,
                end: $end,
            )
            ->perDay()
            ->count();
            
        return [
            'datasets' => [
                [
                    'label' => 'Posts created',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
