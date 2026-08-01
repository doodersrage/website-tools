<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'domain' => ['nullable', 'string', 'max:255'],
            'keywords' => ['required', 'string'],
            'exact_srch' => ['sometimes', 'boolean'],
            'email_address' => ['nullable', 'email', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'exact_srch' => $this->boolean('exact_srch'),
            'domain' => trim((string) $this->input('domain', '')),
        ]);
    }
}
