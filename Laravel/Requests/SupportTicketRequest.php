<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class SupportTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(['support_ticket_create','support_ticket_edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id'               => [
                'required'
            ],
            'company_id'         => [
                'required'
            ],
            'subject'       => [
                'required'
            ],
            'message'  => [
                'required'
            ],
            'department_id'  => [
                'required'
            ],
        ];
    }
}
