<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Mail\Urgence;
use App\Models\Patient;
use App\Models\Service;
use App\Jobs\Urgencesmail;
use App\Models\RendezVous;
use App\Models\Consultation;
use Illuminate\Http\Request;
use App\Models\DossierMedical;
use Illuminate\Support\Carbon;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Http\Requests\DossierMedicalRequest;
use App\Http\Resources\ConsultationResource;

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

            $patientExist = Patient::where('telephone', $request->telephone)->first();
            //$idPatient = $patientExist->id;
            $currentDate = now();

            $numeroChoix = ($request->etat == 'consultation') ? 'numero' : 'numeroRV';

            $lastConsultation = Consultation::whereDate('date', $currentDate)
                ->orderByDesc($numeroChoix)
                ->first();

            $numero = $lastConsultation ? $lastConsultation->$numeroChoix + 1 : 1;
            if ($patientExist) {
                $consult = Consultation::create([

                    // $request->consultations
                    "patient_id" => $patientExist->id,
                    "medecin_id" => $request->medecin_id,
                    "service_id" => $request->service_id,
                    $numeroChoix => $numero,
                    "etat" => $request->etat,
                    // "numero" => $numero,
                    "date" => $currentDate,
                ]);

                return response()->json([
                    'message' => 'success',
                    'patient' => $patientExist
                ]);
            }

            $patient = Patient::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'adresse' => $request->adresse,
                'telephone' => $request->telephone,
                'sexe' => $request->sexe,
                'age' => $request->age,
                'numeroDossier' => $this->genererNumeroDossier($request->nom, $request->prenom, $request->telephone),
            ]);

            $consult = Consultation::create([

                // $request->consultations
                "patient_id" => $patient->id,
                "medecin_id" => $request->medecin_id,
                "service_id" => $request->service_id,
                $numeroChoix => $numero,
                "etat" => $request->etat,
                // "numero" => $numero,
                "date" => $currentDate,
            ]);

            return $patient;

            return response()->json([
                'message' => 'success',
                'patient' => $patient,
                'consultation' => $consult
            ]);
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
    public function trierPatientsParEtat($etat)
    {

        $consultations = Consultation::where('etat', $etat)->get();


        $patients = $consultations->map(function ($consultation) {
            return $consultation->patient;
        });
        return response()->json([
            'message' => 'success',
            'patient' => $patients,
        ]);
    }


    public function lesConsultationDuPatient($idPatient)
    {
        $consultations = Consultation::where('patient_id', $idPatient)->get();
        return ConsultationResource::collection($consultations);

        // return response()->json([
        //     'consultations' => $consultations,
        // ]);
    }



    public function historiqueConsultationsParMois($mois, $annee)
    {
        $startOfMonth = Carbon::create($annee, $mois, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $consultations = Consultation::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date')
            ->get();

        return ConsultationResource::collection($consultations);
    }


    public function getMedecinsByService($serviceId)
    {
        $medecins = User::where('role', 'medecin')
            ->where('service_id', $serviceId)
            ->get();

        return response()->json($medecins);
    }
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

    public function envoieMailUrgence(Request $request)
    {
        $lieu = $request->lieu;
        $heure = $request->heure;
        $description = $request->description;
        $photo = $request->photo;

        Urgencesmail::dispatch($lieu, $heure, $description, $photo);
        return response()->json(['message' => 'succes']);
    }
    /*____________________________________________________Fin enregistrement urgences_________________________________________*/



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = Consultation::all();
        return response()->json([
            "data" => ConsultationResource::collection($all)
        ]);
    }

    public function allPatients()
    {
        $all = Patient::all();
        return response()->json([
            "data" => PatientResource::collection($all)
        ]);
    }

    public function chargerSelect()
    {
        $service = Service::all();
        $medecin = User::where('role', 'medecin')->get();


        return response()->json([
            "service_id" => $service,
            "medecin_id" => $medecin,

        ]);
    }

    // public function rechercherParNumeroDossier(Request $request)
    // {
    //     $numeroDossier = $request->numeroDossier;

    //     $patient = Patient::where('numeroDossier', $numeroDossier)->first();

    //     if ($patient) {
    //         return response()->json([
    //             'patient' => $patient,
    //         ]);
    //     } else {
    //         return response()->json([
    //             'message' => 'Patient non trouvé',
    //         ], 404);
    //     }
    // }

    public function getNumeroDossierBySearch($numDossier)
    {

        $search = Patient::where('numeroDossier', $numDossier)->first();
        return response()->json([
            'patient' => $search,
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
