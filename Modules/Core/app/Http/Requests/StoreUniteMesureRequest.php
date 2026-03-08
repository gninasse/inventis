<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUniteMesureRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'libelle' => 'required|string|max:255',
            'abreviation' => 'required|string|max:50|unique:referentiel_unites_mesure,abreviation',
            'type' => 'required|in:masse,volume,longueur,quantite',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'libelle.required' => 'Le libellé est obligatoire.',
            'abreviation.required' => 'L’abréviation est obligatoire.',
            'abreviation.unique' => 'Cette abréviation est déjà utilisée.',
            'type.required' => 'Le type est obligatoire.',
            'type.in' => 'Le type sélectionné est invalide.',
        ];
    }
}
