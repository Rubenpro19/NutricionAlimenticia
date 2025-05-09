<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Listar todos los usuarios
    public function index()
    {
        return User::all();
    }

    // Crear un nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json($user, 201);
    }

    // Mostrar un usuario específico
    public function show(User $user)
    {
        return $user;
    }

    // Actualizar un usuario
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:8',
        ]);

        if ($request->has('password')) {
            $request->merge(['password' => bcrypt($request->password)]);
        }

        $user->update($request->all());

        return response()->json($user);
    }

    // Eliminar un usuario
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}