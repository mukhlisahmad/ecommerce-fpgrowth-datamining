<?php

namespace App\Filament\Resources\EcommerceResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
class CustomWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan Bulanan',
                    'data' => [5000, 7000, 8000, 6000, 9000, 10000],
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.3)',
                ],
            ],
            'labels' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
