<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $cardId = $this->route('card')?->id ?? null;

        return [
            'title' => ['nullable','string','max:120'],
            'slug' => ['required','string','max:160','alpha_dash', Rule::unique('cards','slug')->ignore($cardId)],
            'name' => ['nullable','string','max:120'],
            'job_title' => ['nullable','string','max:120'],
            'company' => ['nullable','string','max:160'],
            'phone' => ['nullable','string','max:30'],
            'email' => ['nullable','email','max:160'],
            'website' => ['nullable','url','max:200'],
            'bio' => ['nullable','string','max:1000'],
            'theme' => ['nullable','string','max:60'],
            'avatar' => ['nullable','image','mimes:jpeg,png,jpg,webp','max:3072'],
            'cover' => ['nullable','image','mimes:jpeg,png,jpg,webp','max:4096'],
            'social_links' => ['nullable','array'],
            'social_links.*' => ['nullable','url'],
            'data' => ['nullable','array'],
        ];
    }
}
