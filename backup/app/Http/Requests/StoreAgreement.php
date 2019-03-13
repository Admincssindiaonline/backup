<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgreement extends FormRequest
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
            'client_name' => ['string', 'required', 'max:64'],
            'subject' => ['string', 'required', 'max:128'],
            'initial_text' => ['string', 'required', 'max:1024'],
            'options' => ['array', 'required']
        ];
    }
}
