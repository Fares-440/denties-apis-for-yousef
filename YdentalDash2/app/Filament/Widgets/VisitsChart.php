<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class VisitsChart extends ChartWidget
{
    protected static ?string $heading = 'مخطط الزيارات';
    protected static string $color = 'info';
        public function getDescription(): ?string
    {
        return 'معدل الزيارات خلال الشهر .';
    }
    protected function getData(): array
    {
        $data = Trend::model(Visit::class)

        ->between(
            start: now()->startOfMonth(),
            end: now()->endOfMonth(),
        )
        ->perDay()
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'الزيارات',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];;

    }


    protected function getType(): string
    {
        return 'bar';
    }
}
