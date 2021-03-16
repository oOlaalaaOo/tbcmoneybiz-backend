<?php

namespace Modules\AuthModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            // 'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:5',
            'referral_id' => 'required|exists:memberships,referral_link',
            // 'transaction_hash' => 'required|unique:memberships,transaction_hash',
            // 'btc_value' => 'required',
        ];
    }
}
