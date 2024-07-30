<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Veelasky\LaravelHashId\Rules\ExistsByHash;
use App\Models\Event;

class ShowEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    // merge hash to validation input -hash will be from route
    protected function prepareForValidation(){
        $this->merge(['hash'=> $this->route('hash')]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // 'hash' => ['required', new ExistsByHash(Event::class)]
            'hash' => 'required'
        ];
    }
}
