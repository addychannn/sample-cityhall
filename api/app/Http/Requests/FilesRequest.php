<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Veelasky\LaravelHashId\Rules\ExistsByHash;
use App\Models\Files;

class FilesRequest extends FormRequest
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
            // replace with dynamic validation
            'file' => 'file|mimes:pdf,doc,docx,txt',   
            // 'uuid' => 'uuid',
            'timestamp' => 'nullable',
            'name' => 'string|nullable',
            'ext' => 'nullable',
            // 'hash' => ['required', new ExistsByHash(Files::class)],
            // 'is_last_pic' => 'required|boolean',
        ];
    }
}
