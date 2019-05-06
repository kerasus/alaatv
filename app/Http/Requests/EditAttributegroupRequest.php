<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Http\FormRequest;

class EditAttributegroupRequest extends FormRequest
{
    public function authorize()
    {
        if (Auth()
            ->user()
            ->can(config('constants.EDIT_ATTRIBUTEGROUP_ACCESS'))) {
            return true;
        }
    
        return false;
    }

    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }
}
