<?php

namespace Modules\Indexings\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Indexings\Entities\Indexing;

class IndexingsExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function view(): View
    {
        return view(
            'indexings::exports.indexings', [
            'indexings' => Indexing::orderBy('id', 'desc')->get(),
            ]
        );
    }
}
