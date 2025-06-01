<?php

namespace App\Http\Requests\Cabinet\Adverts;

use App\Models\Adverts\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
                $items['attributes'][$attribute['id']] = $rules;
            }

        }

        return array_merge([
            'category_id' => ['required', 'exists:advert_categories,id'],
            'title' => 'required|string',
            'content' => 'required|string',
            'price' => 'required|integer',
            'address' => 'required|string',
            'images' => 'nullable|array',
        ], $items);
    }

    public function messages(): array
    {
        return [
            'category_id.required' => __('adverts.category_id_required'),
            'category_id.exists' => __('adverts.category_id_exists'),
            'region_id.required' => __('adverts.region_id_required'),
            'region_id.exists' => __('adverts.region_id_exists'),
            'title.required' => __('adverts.title_required'),
            'title.string' => __('adverts.title_string'),
            'content.required' => __('adverts.content_required'),
            'content.string' => __('adverts.content_string'),
            'price.required' => __('adverts.price_required'),
            'price.integer' => __('adverts.price_integer'),
            'address.required' => __('adverts.address_required'),
            'address.string' => __('adverts.address_string'),
        ];
    }

    private function loadCategory(): void
    {
        if (! $this->category) {
            $this->category = Category::find($this->input('category_id'));
        }
    }
}
