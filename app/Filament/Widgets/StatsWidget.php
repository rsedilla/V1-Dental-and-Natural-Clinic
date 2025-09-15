<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class StatsWidget extends ChartWidget
{
    protected ?string $heading = 'Stats Widget';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
