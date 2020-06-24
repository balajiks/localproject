<?php

namespace App\Widgets\Indexings;

use Arrilot\Widgets\AbstractWidget;

class YearlyOverview extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $year = chartYear();

        $metrics = new \App\Helpers\Report;
 
        $stats = [
            'jan' => $metrics->indexingsByMonth(1, $year),
            'feb' => $metrics->indexingsByMonth(2, $year),
            'mar' => $metrics->indexingsByMonth(3, $year),
            'apr' => $metrics->indexingsByMonth(4, $year),
            'may' => $metrics->indexingsByMonth(5, $year),
            'jun' => $metrics->indexingsByMonth(6, $year),
            'jul' => $metrics->indexingsByMonth(7, $year),
            'aug' => $metrics->indexingsByMonth(8, $year),
            'sep' => $metrics->indexingsByMonth(9, $year),
            'oct' => $metrics->indexingsByMonth(10, $year),
            'nov' => $metrics->indexingsByMonth(11, $year),
            'dec' => $metrics->indexingsByMonth(12, $year),
        ];

        return view(
            'widgets.indexings.yearly_overview', [
            'config' => $this->config,
            'year' => $year,
            'stats' => $stats,
            ]
        );
    }
}
