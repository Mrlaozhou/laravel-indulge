<?php

namespace Mrlaozhou\Indulge\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FieldsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'table'         =>  'bail|required|alpha_dash|max:20',
            'name'          =>  'bail|required|alpha_dash|max:20',
            'label'         =>  'bail|max:30',
            'type'          =>  'bail|alpha|max:30',
            'form_type'     =>  'bail|required|alpha|max:30',
            'option_id'     =>  'bail|number',
            'require'       =>  'bail|max:60',
            'showable'      =>  'bail|between:0,1',
            'writeable'     =>  'bail|between:0,1',
        ];
    }
}