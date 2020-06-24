<?php

namespace Modules\Analytics\Console;

use Illuminate\Console\Command;
use Modules\Indexings\Entities\Indexing;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ComputeIndexings extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'analytics:indexings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to calculate indexings reports.';

    /**
     * Indexing model
     *
     * @var \Modules\Indexings\Entities\Indexing
     */
    protected $indexing;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Indexing $indexing)
    {
        parent::__construct();
        $this->indexing = $indexing;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->total();
        $this->indexingValue();
        $this->converted();
        $this->indexingsToday();
        $this->indexingsThisWeek();
        $this->thisMonth();
        $this->lastMonth();
        $this->calcQuaters();
        $this->info('Indexings reports calculated successfully');
    }

    protected function total()
    {
        \App\Entities\Computed::updateOrCreate(
            ['key' => 'indexings_total'],
            ['value' => $this->indexing->count()]
        );
    }

    protected function indexingValue()
    {
        $amount = 0;
        $this->indexing->chunk(
            200,
            function ($indexings) use (&$amount) {
                foreach ($indexings as $key => $indexing) {
                    $amount += $indexing->indexing_value;
                };
            }
        );
        \App\Entities\Computed::updateOrCreate(
            ['key' => 'indexings_value'],
            ['value' => $amount]
        );
    }

    protected function converted()
    {
        \App\Entities\Computed::updateOrCreate(
            ['key' => 'indexings_converted'],
            ['value' => $this->indexing->converted()->count()]
        );
    }

    protected function indexingsToday()
    {
        \App\Entities\Computed::updateOrCreate(
            ['key' => 'indexings_today'],
            ['value' => $this->indexing->whereDate('created_at', today())->count()]
        );
    }

    protected function indexingsThisWeek()
    {
        \App\Entities\Computed::updateOrCreate(
            ['key' => 'indexings_this_week'],
            ['value' => $this->indexing->whereBetween('created_at', [now()->startOfWeek(),now()->endOfWeek()])->count()]
        );
    }

    protected function thisMonth()
    {
        \App\Entities\Computed::updateOrCreate(
            ['key' => 'indexings_this_month'],
            ['value' => $this->indexing->whereYear('created_at', now()->format('Y'))->whereMonth('created_at', now()->format('n'))->count()]
        );
    }

    protected function lastMonth()
    {
        $dt = now()->subMonth(1);
        \App\Entities\Computed::updateOrCreate(
            ['key' => 'indexings_last_month'],
            ['value' => $this->indexing->whereYear('created_at', $dt->format('Y'))->whereMonth('created_at', $dt->format('n'))->count()]
        );
    }
    protected function calcQuaters()
    {
        $year = chartYear();
        for ($i = 1; $i < 13; $i++) {
            \App\Entities\Computed::updateOrCreate(
                ['key' => 'indexings_'.$i.'_'.$year],
                ['value' => $this->indexing->whereYear('created_at', $year)->whereMonth('created_at', (string)$i)->count()]
            );
        }
    }
}
