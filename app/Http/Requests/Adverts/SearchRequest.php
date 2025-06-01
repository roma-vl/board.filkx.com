<?php

namespace App\Http\Requests\Adverts;

use App\Models\Adverts\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchRequest extends FormRequest
{
    private ?Category $category = null;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $this->loadCategory();

        $items = [];
        if ($this->category) {
            foreach ($this->category->allArrayAttributes() as $attribute) {
                $rules = [
                    $attribute['is_required'] ? 'required' : 'nullable',
                ];
                if ($attribute['type'] === 'integer') {
                    $rules[] = 'integer';
                } elseif ($attribute['type'] === 'float') {
                    $rules[] = 'numeric';
                } else {
                    $rules[] = 'string';
                    $rules[] = 'max:255';
                }
                if (! empty($attribute['variants'])) {
                    $rules[] = Rule::in($attribute['variants']);
                }
                [$attribute['id']] = $rules;
            }

        }

        return array_merge([
            'query' => 'nullable|string|max:25',
        ], $items);
    }

    private function loadCategory(): void
    {
        if (! $this->category) {
            $this->category = Category::find($this->input('category_id'));
        }
    }
}
