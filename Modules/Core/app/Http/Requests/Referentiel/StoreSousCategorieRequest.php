<?php

namespace Modules\Core\Http\Requests\Referentiel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSousCategorieRequest extends FormRequest
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
            'categorie_id' => 'required|exists:referentiel_categories,id',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('referentiel_sous_categories', 'code')
                    ->where('categorie_id', $this->categorie_id),
            ],
            'libelle' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'categorie_id.required' => 'La catégorie est obligatoire.',
            'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'code.required' => 'Le code est obligatoire.',
            'code.unique' => 'Ce code existe déjà pour cette catégorie.',
            'code.max' => 'Le code ne peut pas dépasser 50 caractères.',
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.max' => 'Le libellé ne peut pas dépasser 255 caractères.',
        ];
    }
}
