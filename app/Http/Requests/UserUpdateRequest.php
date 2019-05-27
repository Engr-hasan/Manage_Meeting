<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Libraries\Encryption;

class UserUpdateRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        //dd($_POST);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $id = null;
        $segment = $this->segment(3) ? $this->segment(3) : '';
        if ($segment) {
            $id = Encryption::decodeId($segment);
        }

        return [
            'user_full_name' => 'required',
//            'user_nid' => 'digits_between:13,17',
            'user_DOB' => 'required',
            'user_phone' => 'required'
        ];
    }

    public function messages() {
        return [
            'user_full_name.required' => 'Name field is required',
            'user_nid.required' => 'National ID No. field is required',
            'user_nid.numeric' => 'National ID No. must be numeric',
//            'user_nid.digits_between' => 'National ID No. must be 17 digits',
            'user_DOB.required' => 'Date of Birth field is required',
            'user_phone.required' => 'Mobile Number field is required',
            'user_email.required' => 'Email Address field is required',
            'user_email.unique' => 'Email Address must be unique',
            'user_username.required' => 'User Name field is required'
        ];
    }

}
