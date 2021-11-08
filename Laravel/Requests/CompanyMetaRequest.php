<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class CompanyMetaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(['company_meta_create', 'company_meta_edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id'         => [
                'required'
            ],
            'company_logo'         => [
                'nullable', 
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
            ],
            'default_invoice_title'         => [
                'required', 
                'string', 
                'max:255'
            ],
            'default_subheading'         => [
                'required', 
                'string', 
                'max:255'
            ],
            'default_footer'         => [
                'required', 
                'string', 
                'max:255'
            ],
            'default_notes'         => [
                'required', 
                'string', 
                'max:255' 
            ],
        ];
    }
}
