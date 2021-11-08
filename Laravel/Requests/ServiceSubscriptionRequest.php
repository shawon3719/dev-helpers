<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class ServiceSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(['service_subscription_create','service_subscription_edit']);
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
            'service_id'       => [
                'required'
            ],
            'effective_from'  => [
                'required'
            ],
        ];
    }
}
