<?php

namespace App\Http\Controllers;

use App\Models\Representado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'ci' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'direccion' => 'required|string|max:500',
            'fecha_nacimiento' => 'required|date',
            'vive_con_representado' => 'boolean',
        ]);

        $user->update($request->all());

        return redirect()->route('dashboard')
            ->with('success', 'Perfil actualizado exitosamente.');
    }

    public function createRepresentado()
    {
        $user = Auth::user();
        $representados = $user->representados;
        return view('representados.create', compact('user', 'representados'));
    }

    public function storeRepresentado(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'ci' => 'nullable|string|max:20|unique:representados',
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string',
            'nivel_academico' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Si vive con representado, heredar la dirección del user
        if (Auth::user()->vive_con_representado && empty($request->direccion)) {
            $data['direccion'] = Auth::user()->direccion;
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('representados', 'public');
        }

        Representado::create($data);

        return redirect()->route('representados.index')
            ->with('success', 'Representado registrado exitosamente.');
    }
}