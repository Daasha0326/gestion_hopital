<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DossierMedicalRequest extends FormRequest
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
        return [
            "dateEntre" => "required",
            //"dateSortie" => "required",
            "symptomes" => "required",
            "maladie_antecedent" => "required",
            "bilan" => "required"
        ];
    }
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'success'=>false,
            'error'=>true,
            'message'=>'Erreur de validation',
            'errorsList'=>$validator->errors()
        ]));
    }
    public function messages(){
        return [
            'dateEntre.required'=>'La date doit etre fournie',
            'symptomes.required'=>'Les symptomes doit être fourni',
            'maladie_antecedent.required'=>'La maladie antecedent doit être fourni',
            'bilan.required'=>'Le bilan doit être fourni'
        ];
    }
}
