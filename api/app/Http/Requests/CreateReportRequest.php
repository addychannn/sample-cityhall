<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class CreateReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $today = Carbon::tomorrow()->toFormattedDateString();
        return [
            'first_name' => 'nullable',
            'middle_name' => 'nullable',
            'last_name' => 'nullable',
            'suffix' => 'nullable',
            'contact_number' => 'required',
            'email' => 'nullable',
            'violator_name' => 'nullable',
            'violator_location' => 'required',
            'barangay_code' => 'required',
            'violation_date' => 'required|date|before:'.$today, 
            'landmark' => 'required',
            'violation_description' => 'required'
        ];
    }
}
