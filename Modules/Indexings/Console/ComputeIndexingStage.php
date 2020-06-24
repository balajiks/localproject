<?php

namespace Modules\Indexings\Console;

use Illuminate\Console\Command;

class ComputeIndexingStage extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'indexings:calcstage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates totals for each indexing stage.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \App\Entities\Category::indexings()->chunk(
            200,
            function ($stages) {
                foreach ($stages as $stage) {
                    \App\Entities\Computed::updateOrCreate(
                        ['key' => 'indexings_stage_' . $stage->id],
                        ['value' => $stage->indexingsValue()]
                    );
                }
            }
        );

        $this->info('Indexing Stages calculated successfully');
    }
}
