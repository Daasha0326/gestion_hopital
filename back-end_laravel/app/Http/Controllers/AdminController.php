<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequeste;
use App\Http\Requests\UserRequest;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            "users" => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createService(ServiceRequeste $request)
    {
        $user = Service::create([
            "nom" => $request
        ]);
        return response()->json([
            "status" => 200,
            "message" => "Isertion resuisie",
            "user" => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = User::create([
            "nom" => $request->nom,
            "prenom" => $request->prenom,
            "telephone" => $request->telephone,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role" => $request->role,
        ]);
        return response()->json([
            "status" => 200,
            "message" => "Isertion resuisie",
            "user" => $user
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::select('users.*')->where('id', $id)->first();
        return response()->json([
            "status" => 200,
            "user" => $user,
        ]);
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
        $user = User::find($id);
        $user->update([
            "nom" => $request->nom,
            "prenom" => $request->prenom,
            "telephone" => $request->telephone,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "role" => $request->role,
        ]);
        return response()->json([
            "status" => 200,
            "Message" => "modification avec succès",
            "user" => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(
            [
                'message' => 'User supprimé avec succès',
                'status' => 200
            ]
        );
    }
}
