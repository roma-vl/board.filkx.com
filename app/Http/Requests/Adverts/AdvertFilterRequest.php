<?php

namespace App\Http\Requests\Adverts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdvertFilterRequest extends FormRequest
{
    public const SORTABLE_FIELDS = ['title', 'price', 'created_at', 'id'];

    public const SORT_DIRECTIONS = ['asc', 'desc'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'sort_by' => ['sometimes', Rule::in(self::SORTABLE_FIELDS)],
            'sort_order' => ['sometimes', Rule::in(self::SORT_DIRECTIONS)],
            'name' => 'sometimes|string|max:255',
            'search' => 'sometimes|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'per_page.integer' => __('validation.custom.per_page.integer'),
            'per_page.min' => __('validation.custom.per_page.min'),
            'per_page.max' => __('validation.custom.per_page.max'),
            'sort_by.in' => __('validation.custom.sort_by.in'),
            'sort_order.in' => __('validation.custom.sort_order.in'),
            'name.string' => __('validation.custom.name.string'),
            'name.max' => __('validation.custom.name.max'),
            'email.string' => __('validation.custom.email.string'),
            'email.max' => __('validation.custom.email.max'),
            'status.in' => __('validation.custom.status.in'),
        ];
    }

    public function validatedWithDefaults(): array
    {
        $validated = array_filter($this->validated(), fn ($value) => ! is_null($value));

        return array_merge([
            'per_page' => 10,
            'sort_by' => 'created_at',
            'sort_order' => 'desc',
            'search' => '',
        ], $validated);
    }
}
