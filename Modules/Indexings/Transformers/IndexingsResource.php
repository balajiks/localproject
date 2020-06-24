<?php

namespace Modules\Indexings\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class IndexingsResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => \Modules\Indexings\Transformers\IndexingResource::collection($this->collection),
        ];
    }
}
