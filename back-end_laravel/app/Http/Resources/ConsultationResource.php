<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "numero" => $this->numero,
            "date" => $this->date,
            "patient" => new PatientResource($this->patient),
            "service" => new ServiceRessource($this->service),
            "medecin" => new MedecinResource($this->user),
        ];
    }
}
