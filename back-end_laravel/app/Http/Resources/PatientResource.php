<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "nom"=>$this->nom,
            "prenom"=>$this->prenom,
            "sexe"=>$this-> sexe,
            "age"=>$this->age,
            "adresse"=>$this->adresse,
            "telephone"=>$this->telephone,
            "numeroDossier"=>$this->numeroDossier
            "nom" => $this->nom,
            "prenom"=> $this->prenom,
            "adresse" => $this->adresse,
            "telephone"=> $this->telephone,
            "sexe" => $this->age,
            "dossier_medical" => new DossierMedicalResource($this->dossier_medical),

        ];
    }
}
