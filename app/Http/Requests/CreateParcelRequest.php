<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateParcelRequest extends FormRequest
{
    
    public function rules()
    {
        return [
            'name' => 'required',
            'pick_up' => 'required',
            'drop_of' => 'required',
        ];
    }
}
