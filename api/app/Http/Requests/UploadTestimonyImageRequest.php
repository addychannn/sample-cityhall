<?php

namespace App\Http\Requests;

use Veelasky\LaravelHashId\Rules\ExistsByHash;
use App\Models\Testimony;
use Illuminate\Foundation\Http\FormRequest;

class UploadTestimonyImageRequest extends FormRequest
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
            'file' => 'file|mimes:jpeg,png,jpg,gif',   
            'timestamp' => 'nullable',
            'name' => 'string|nullable',
            'ext' => 'required|string',
            'hash' => ['required', new ExistsByHash(Testimony::class)]
        ];
    }
}