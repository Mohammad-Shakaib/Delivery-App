<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PickParcelRequest extends FormRequest
{
    public function rules()
    {
        return [
            'parcel_id' => 'required'
        ];
    }
}
