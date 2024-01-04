<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory,SoftDeletes;

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function rendezVouse()
    {
        return $this->hasMany(RendezVous::class);
    }
    public function dossier_medical(){
        return $this->belongsTo(DossierMedical::class);
    }
}
