<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // user should send one of the fields or both  not empty
        // title required if text is not sent
        // text required if title is not sent
        return [
            'title' => ['nullable', 'string', 'required_without:text','unique:notes'],
            'text' => ['nullable', 'string', 'required_without:title'],
        ];
    }
}
