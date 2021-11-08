<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class SubscriptionScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(['subscription_schedule_create','subscription_schedule_edit']);
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
            'trigger_time'       => [
                'required'
            ],
            'month'  => [
                'required'
            ],
            'start'  => [
                'required'
            ],
            'until'  => [
                'required'
            ],
        ];
    }
}
