<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
        $rules = [
            'parent_id' => ['nullable'],
            'name' => ['required', 'string'],
        ];

        if (request()->isMethod('put')) {
            if ($this->parent_id && $this->parent_id == $this->category->id) {
                $rules['parent_id'][] = Rule::notIn([$this->category->id]);
                // $rules['parent_id'][] = 'not_in:'.$this->category->id;
            }
            
            $rules['name'][] = Rule::unique('categories', 'name')->ignore($this->category->id);
        }

        // if (request()->isMethod('put')) {
        //     $rules['name'][] = 'unique:categories,name,' . $this->category->id;
        // }

        return $rules;
    }

    public function messages()
    {
        return [
            'parent_id.not_in' => 'The parent_id cannot be the same as the category_id.',
        ];
    }
}