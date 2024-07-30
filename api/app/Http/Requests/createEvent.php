<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createEvent extends FormRequest
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
        return [
            'event_title'=> 'required',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ];
    }
}
