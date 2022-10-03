<?php

namespace Devvly\Ffl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFflForm extends FormRequest
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
            'business_hours'              => 'required|max:255',
            'city'                        => 'required|max:255',
            'company_name'                => 'required|max:255',
            'contact_name'                => 'required|max:255',
            'email'                       => 'required|email:rfc|max:255',
            'hand_gun'                    => 'required',
            'long_gun'                    => 'required',
            'nics'                        => 'required',
            'mailing_state'               => 'required|integer',
            'license_image.file'          => 'required',
            'license_image.name'          => 'required',
            'license_number'              => ['required'],
            'license_number_parts.first'  => [
                'required', 'max:1', function ($attribute, $value, $fail) {
                    /**  Region  */
                    $allowed = ['1', '2', '3', '4', '5', '6', '8', '9'];
                    if (!in_array($value, $allowed)) {
                        $fail('License region not valid');
                    }
                },
            ],
            'license_number_parts.second' => ['required', 'max:2',],
            'license_number_parts.third'  => ['required', 'max:3',],
            'license_number_parts.fourth' => [
                'required', 'min:2', 'max:2', function ($attribute, $value, $fail) {
                    /** Type */
                    $allowed = ["01", "02", "03", "06", "07", "08", "09", "10", "11",];
                    if (!in_array($value, $allowed)) {
                        $fail('License type not valid');
                    }
                },
            ],
            'license_number_parts.fifth'  => [
                'required', 'max:2', function ($attribute, $value, $fail) {
                    /** Expire */
                    $months = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M',];
                    $years = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',];
                    $split = str_split($value);
                    if (!in_array($split[0], $years) || !in_array($split[1], $months)) {
                        $fail('License type not valid');
                    }

                },
            ],
            'license_number_parts.sixth'  => 'required|max:6',
            'payment'                     => 'required|max:255',
            'importer_exporter'           => 'required|boolean',
            'retail_store'                => 'required|boolean',
            'zip_code'                    => 'required|numeric',
            'phone'                       => 'required|max:20',
            'street_address'              => 'required|max:255',
            'position.lat'                => 'required',
            'position.lng'                => 'required',
        ];
    }
}
