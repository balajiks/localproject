<?php

namespace App\Widgets\Chats;

use Arrilot\Widgets\AbstractWidget;

class WhatsApp extends AbstractWidget
{

    /**
     * The number of seconds before each reload.
     *
     * @var int|float
     */
    public $reloadTimeout = 15;

    /**
      * The configuration array.
      *
      * @var array
      */
    protected $config = [
        'chatable_type' => '',
        'chatable_id' => '',
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $model = classByName($this->config['chatable_type'])->find($this->config['chatable_id']);
        $data['chats'] = $model->chats;
        return view(
            'widgets.chats.whats_app',
            [
            'config' => $this->config,
            ]
        )->with($data);
    }

    public function placeholder()
    {
        return 'Loading...';
    }
}
