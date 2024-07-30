<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Veelasky\LaravelHashId\Rules\ExistsByHash;
use App\Models\AboutSection;

class UpdateSectionDetailsRequest extends FormRequest
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
            'hash'=>['required', new ExistsByHash(AboutSection::class)],
            'title'=>'nullable|string',
            'content'=>'nullable|string',

        ];
    }
}
