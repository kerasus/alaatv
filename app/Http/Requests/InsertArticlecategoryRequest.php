<?php

namespace App\Http\Requests;

use App\Traits\CharacterCommon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class InsertArticlecategoryRequest extends FormRequest
{
    use CharacterCommon;

    public function authorize()
    {
        if (Auth()
            ->user()
            ->can(Config::get('constants.INSERT_ARTICLECATEGORY_ACCESS'))) {
            return true;
        }
        
        return false;
    }

    public function rules()
    {
        return [
            'name'  => 'required',
            'order' => 'integer|min:0',
        ];
    }
    
    public function prepareForValidation()
    {
        $this->replaceNumbers();
        parent::prepareForValidation();
    }
    
    protected function replaceNumbers()
    {
        $input = $this->request->all();
        if (isset($input["order"])) {
            $input["order"] = preg_replace('/\s+/', '', $input["order"]);
            $input["order"] = $this->convertToEnglish($input["order"]);
        }
        $this->replace($input);
    }
}
