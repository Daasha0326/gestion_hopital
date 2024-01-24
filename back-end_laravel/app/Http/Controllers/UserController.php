<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use App\Models\DossierMedical;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\DossierMedicalRequest;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;


class UserController extends Controller
{

    public function listePatient()
    {
        $today = date('Y-m-d');
        $rendezVous = RendezVous::with(['medecin', 'patient'])
            ->whereDate('dateRend', $today)
            ->orderBy('heureRend')
            ->get();
        return $rendezVous;
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
    /*______________________________________________Enregistrer patient (Adama)_______________________________________*/

    public function ajouterInfoPatient(PatientRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $patient = Patient::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'adresse' => $request->adresse,
                'telephone' => $request->telephone,
                'sexe' => $request->sexe,
                'age' => $request->age,
                'numeroDossier' => $this->genererNumeroDossier($request->nom, $request->prenom, $request->telephone),
            ]);

            $currentDate = now();

            $lastConsultation = Consultation::whereDate('date', $currentDate)
                ->orderByDesc('numero')
                ->first();

            $numero = $lastConsultation ? $lastConsultation->numero + 1 : 1;

            $consult = Consultation::create([

                // $request->consultations
                "patient_id" => $patient->id,
                "medecin_id" => $request->medecin_id,
                "service_id" => $request->service_id,
                "numero" => $numero,
                "date" => $currentDate,
            ]);

            return $patient;

            return response()->json(['message' => 'success', 'patient' => $patient, 'consultation' => $consult]);
        });
    }

    private function genererNumeroDossier($nom, $prenom, $telephone)
    {

        $nomPart = strtoupper(substr($nom, 0, 3));
        $prenomPart = strtoupper(substr($prenom, 0, 3));
        $telephonePart = strtoupper(substr($telephone, 0, 3));

        $numeroDossier = $nomPart . $prenomPart . $telephonePart;

        $existingDossier = Patient::where('numeroDossier', $numeroDossier)->first();

        if ($existingDossier) {
            $telephonePart .= strtoupper(substr($telephone, 3, 1));
            $numeroDossier = $nomPart . $prenomPart . $telephonePart;
        }

        return $numeroDossier;
    }
    /*____________________________________________________Fin enregistrement patient_________________________________________*/





    /*___________________________________________________Supprimer ou modifier un patient_____________________________________*/

    public function destroy(Patient $patient)
    {
        return DB::transaction(function () use ($patient) {

            $delPatient = Patient::findOrFail($patient->id);
            $delPatient->delete();

            return response()->json(
                [
                    'message' => 'patient supprimé avec succès',
                    'data' => $patient,
                    'status' => 200
                ]
            );
        });
    }

    public function update(Request $request, $patient)
{
    $pat = Patient::find($patient);

    $oldNom = $pat->nom;
    $oldPrenom = $pat->prenom;
    $oldTelephone = $pat->telephone;

    $pat->update([
        'nom' => $request->nom,
        'prenom' => $request->prenom,
        'sexe' => $request->sexe,
        'age' => $request->age,
        'adresse' => $request->adresse,
        'numeroDossier' => $this->genererNumeroDossier($request->nom, $request->prenom, $request->telephone),
    ]);

    $nomModifie = $oldNom !== $request->nom;
    $prenomModifie = $oldPrenom !== $request->prenom;
    $telephoneModifie = $oldTelephone !== $request->telephone;

    if ($nomModifie || $prenomModifie || $telephoneModifie) {
        $pat->update([
            'numeroDossier' => $this->genererNumeroDossier($request->nom, $request->prenom, $request->telephone),
        ]);
    }

    return response()->json(['message' => 'Les informations du patient ont été mises à jour avec succès', 'patient' => $pat]);
}



    /*____________________________________________________Fin suppression et modification d'un patient________________________*/












    /*____________________________________________________Enregistrement urgences_________________________________________*/

    public function alerteUrgence(Request $request){
        
    }




    /*____________________________________________________Fin enregistrement urgences_________________________________________*/



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = Consultation::all();
        return response()->json([
            "consultations" => ConsultationResource::collection($all)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    // public function chergerSelect()
    // {
    //     $patient = Patient::all();
    //     $consult = Consultation::all();

    //     return response()->json([
    //         "patient" => $patient,
    //         "consultation" => $consult,
    //     ]);
    // }

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
    
}
