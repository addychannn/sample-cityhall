<?php

namespace App\Http\Requests;

use App\Models\Testimony;
use Veelasky\LaravelHashId\Rules\ExistsByHash;

use Illuminate\Foundation\Http\FormRequest;

class DeleteTestimonyRequest extends FormRequest
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
            'id'=> ['required', new ExistsByHash(Testimony::class)],
        ];
    }
}