<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexAgreement extends FormRequest
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
            'page' => ['integer', 'min:1'],
            'perPage' => ['integer', 'min:5', 'max:20'],
            'search' => ['string', 'nullable', 'max:128'],
            'filterBySeen' => ['integer', 'min:-1', 'max:1'],
            'filterByAccepted' => ['integer', 'min:-1', 'max:1'],
            'sortField' => ['string', 'in:id,client_name,subject,seen_at,accepted_at'],
            'sortOrder' => ['string', 'in:asc,desc']
        ];
    }
}
