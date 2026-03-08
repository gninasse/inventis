<?php

namespace Modules\Core\Http\Requests\Referentiel;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code_national' => 'required|unique:referentiel_articles,code_national',
            'designation' => 'required',
            'type' => 'required|in:durable,consommable',
            'famille_id' => 'required|exists:referentiel_familles,id',
            'categorie_id' => 'required|exists:referentiel_categories,id',
            'sous_categorie_id' => 'required|exists:referentiel_sous_categories,id',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
