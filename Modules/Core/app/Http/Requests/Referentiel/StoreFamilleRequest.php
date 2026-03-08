<?php

namespace Modules\Core\Http\Requests\Referentiel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFamilleRequest extends FormRequest
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
            'sous_categorie_id' => 'required|exists:referentiel_sous_categories,id',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('referentiel_familles', 'code')
                    ->where('sous_categorie_id', $this->sous_categorie_id),
            ],
            'libelle' => 'required|string|max:255',
            'duree_amortissement' => 'required|integer|min:0|max:100',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'sous_categorie_id.required' => 'La sous-catégorie est obligatoire.',
            'sous_categorie_id.exists' => 'La sous-catégorie sélectionnée n\'existe pas.',
            'code.required' => 'Le code est obligatoire.',
            'code.unique' => 'Ce code existe déjà pour cette sous-catégorie.',
            'code.max' => 'Le code ne peut pas dépasser 50 caractères.',
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.max' => 'Le libellé ne peut pas dépasser 255 caractères.',
            'duree_amortissement.required' => 'La durée d\'amortissement est obligatoire.',
            'duree_amortissement.integer' => 'La durée d\'amortissement doit être un nombre entier.',
            'duree_amortissement.min' => 'La durée d\'amortissement doit être au minimum 0.',
            'duree_amortissement.max' => 'La durée d\'amortissement ne peut pas dépasser 100 ans.',
        ];
    }
}
