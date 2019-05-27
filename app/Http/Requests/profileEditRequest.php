<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class profileEditRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'user_full_name' => 'required', // Name
            'user_DOB' => 'required', //Date of Birth
            'user_phone' => 'required', //Mobile Number
        ];
    }

    public function messages() {
        return [
            'user_full_name.required' => 'Name field is required',
            'user_DOB.required' => 'Date of Birth field is required',
            'user_phone.required' => 'Mobile Number field is required',
        ];
    }

}
