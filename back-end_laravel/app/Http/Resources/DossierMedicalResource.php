<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DossierMedicalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "dateEntre" => $this->dateEntre,
            "dateSortie" => $this->dateSortie,
            "symptomes" => $this->symptomes,
            "maladie_antecedent" => $this->maladie_antecedent,
            "bilan" => $this->bilan
        ];
    }
}
