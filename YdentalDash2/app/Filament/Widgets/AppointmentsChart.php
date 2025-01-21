<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AppointmentsChart extends ChartWidget
{
    protected static ?string $heading = 'مخطط الحجوزات  ';
    protected static string $color = 'warning';
    protected static ?int $sort=1;
    public ?string $filter = 'today';
        public function getDescription(): ?string
    {
        return 'معدل الحجوزات خلال السنه شهرياً .';
    }


    protected function getData(): array
    {


        $data = Trend::model(Appointment::class)

        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();


    return [
        'datasets' => [
            [
                'label' => 'الحجوزات',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];;

    }

    protected function getType(): string
    {
        return 'line';
    }
}
