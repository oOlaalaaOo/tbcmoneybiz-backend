<?php

namespace Modules\MembershipModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PayRequest extends FormRequest
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
            'transaction_hash' => 'required|unique:memberships,transaction_hash',
            'btc_value' => 'required',
            'membership_id' => 'required'
        ];
    }
}
