<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateProduct extends FormRequest
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
            
            'title'         =>  'required',
            'slug'          =>  'required',
            'description'   =>  'required',
            'price'         =>  'required|numeric',
            'status'        =>  'required|numeric',
            'thumbnail'     =>  'required|mimes:jpeg,bmp,png|max:2048',
            'category_id'   =>  'required',

        ];
    }
}
