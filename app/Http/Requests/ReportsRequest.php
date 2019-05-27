<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ReportsRequest extends Request
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
            'report_title' => 'required',
            'report_para1' => 'required',
            'status' => 'required'
        ];
    }
}
