<?php

namespace Devvly\Ffl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFflImportForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** TODO here add condition for super admin */
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
            'data' => 'required|file'
        ];
    }
}
