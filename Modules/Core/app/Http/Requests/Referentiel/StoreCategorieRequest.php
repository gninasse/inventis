<?php

namespace Modules\Core\Http\Requests\Referentiel;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategorieRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:referentiel_categories,code',
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Le code est obligatoire.',
            'code.unique' => 'Ce code existe déjà.',
            'code.max' => 'Le code ne peut pas dépasser 50 caractères.',
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.max' => 'Le libellé ne peut pas dépasser 255 caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
        ];
    }
}
