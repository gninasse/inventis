<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUniteMesureRequest extends FormRequest
{
    public function rules(): array
    {
        $uniteId = $this->route('id');

        return [
            'libelle' => 'required|string|max:255',
            'abreviation' => [
                'required',
                'string',
                'max:50',
                Rule::unique('referentiel_unites_mesure', 'abreviation')->ignore($uniteId),
            ],
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
