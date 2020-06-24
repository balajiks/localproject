<?php

namespace App\Widgets\Indexings; 

use Arrilot\Widgets\AbstractWidget;

class ShowSections extends AbstractWidget
{

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'indexingsections' => [],
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $data['indexingsections'] = $this->config['indexingsections'];

        return view(
            'widgets.indexing.show_sections', [
            'config' => $this->config,
            ]
        )->with($data);
    }
}
