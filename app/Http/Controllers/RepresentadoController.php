<?php

namespace App\Http\Controllers;

use App\Models\Representado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepresentadoController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $representados = Representado::with('user')->latest()->paginate(10);
        } else {
            $representados = Auth::user()->representados()->latest()->paginate(10);
        }
        
        return view('representados.index', compact('representados'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Verificar si el usuario tiene perfil completo
        if (!$user->perfil_completo) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Complete su perfil de representante antes de agregar representados.');
        }

        return view('representados.create', compact('user'));
    }

    public function store(Request $request)
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

    public function edit(Representado $representado)
    {
        // Verificar que el usuario sea el dueño o admin
        if (Auth::user()->role !== 'admin' && $representado->user_id !== Auth::id()) {
            if (request()->ajax()) {
                return response()->json(['error' => 'No tienes permisos para editar este representado.'], 403);
            }
            abort(403, 'No tienes permisos para editar este representado.');
        }

        // Si es una petición AJAX (para el modal)
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'representado' => [
                    'id' => $representado->id,
                    'nombres' => $representado->nombres,
                    'apellidos' => $representado->apellidos,
                    'ci' => $representado->ci,
                    'fecha_nacimiento' => $representado->fecha_nacimiento->format('Y-m-d'),
                    'telefono' => $representado->telefono,
                    'direccion' => $representado->direccion,
                    'nivel_academico' => $representado->nivel_academico,
                    'foto' => $representado->foto,
                ]
            ]);
        }

        return view('representados.edit', compact('representado'));
    }

    public function update(Request $request, Representado $representado)
    {
        // Verificar que el usuario sea el dueño o admin
        if (Auth::user()->role !== 'admin' && $representado->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['error' => 'No tienes permisos para actualizar este representado.'], 403);
            }
            abort(403, 'No tienes permisos para actualizar este representado.');
        }

        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'ci' => 'nullable|string|max:20|unique:representados,ci,' . $representado->id,
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string',
            'nivel_academico' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('representados', 'public');
        }

        $representado->update($data);

        // Si es una petición AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Representado actualizado exitosamente.'
            ]);
        }

        return redirect()->route('representados.index')
            ->with('success', 'Representado actualizado exitosamente.');
    }

    public function destroy(Representado $representado)
    {
        // Verificar que el usuario sea el dueño o admin
        if (Auth::user()->role !== 'admin' && $representado->user_id !== Auth::id()) {
            if (request()->ajax()) {
                return response()->json(['error' => 'No tienes permisos para eliminar este representado.'], 403);
            }
            abort(403, 'No tienes permisos para eliminar este representado.');
        }

        $representado->delete();
        
        // Si es una petición AJAX
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Representado eliminado exitosamente.'
            ]);
        }

        return redirect()->route('representados.index')
            ->with('success', 'Representado eliminado exitosamente.');
    }
}