<?php

namespace Modules\Indexings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'stage_id' => 'sometimes|numeric',
            'source' => 'required|numeric',
            'sales_rep' => 'required|numeric',
            'indexing_score' => 'sometimes|integer',
            'website' => 'sometimes|url',
        ];
    }
}
