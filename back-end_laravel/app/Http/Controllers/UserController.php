<?php

namespace App\Http\Controllers;

use App\Http\Requests\DossierMedicalRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\RendezVousResource;
use App\Models\DossierMedical;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function listePatient()
    {
        $today = date('Y-m-d');
        $rendezVous = RendezVous::with(['medecin', 'patient'])
            ->whereDate('dateRend', $today)
            ->orderBy('heureRend')
            ->get();
        return response()->json([
            "data" => [
                "patient" => RendezVousResource::collection($rendezVous)
            ]
        ]);
    }

    public function nbrePatientParJour()
    {
        $user = Auth::user();
        if ($user->role === "medecin") {
            $medecin_id = $user->id;
            $nbRendezVous = RendezVous::where('medecin_id', $medecin_id)->count();
            return response()->json([
                "nbRendezVous" => $nbRendezVous
            ]);
        }
    }
    public function createDossierMedical(DossierMedicalRequest $request)
    {
        $dossier = DossierMedical::create([
            "dateEntre" => $request->dateEntre,
            "symptomes" => $request->symptomes,
            "maladie_antecedent" => $request->maladie_antecedent,
            "bilan" => $request->bilan
        ]);
        return response()->json([
            "message" => "Insertion reuissie",
            "status" => 200,
            "dossier" => $dossier
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
