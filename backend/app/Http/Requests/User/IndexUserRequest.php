<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class IndexUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'filter' => 'nullable|array',
            'filter.name' => 'nullable|string|max:255',

            'sort' => 'nullable|array',
            'sort.*' => 'in:asc,desc',
        ];
    }

    public function filters(): Collection
    {
        return collect($this->input('filter', []));
    }

    public function perPage(): int|string
    {
        return $this->input('per_page', 'all');
    }

    public function sort(): Collection
    {
        return collect($this->input('sort', []));
    }
}
