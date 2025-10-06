<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('representados')->withCount('representados');

        // Búsqueda
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ci', 'like', "%{$search}%")
                  ->orWhere('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        // Filtro por perfil completo
        if ($request->has('perfil_completo') && $request->perfil_completo !== '') {
            if ($request->perfil_completo == 1) {
                $query->whereNotNull('ci')
                      ->whereNotNull('nombres')
                      ->whereNotNull('apellidos')
                      ->whereNotNull('telefono')
                      ->whereNotNull('direccion');
            } else {
                $query->whereNull('ci')
                      ->orWhereNull('nombres')
                      ->orWhereNull('apellidos')
                      ->orWhereNull('telefono')
                      ->orWhereNull('direccion');
            }
        }

        $users = $query->latest()->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'users' => $users,
                'html' => view('admin.users.partials.users_table', compact('users'))->render(),
                'pagination' => (string) $users->links()
            ]);
        }

        return view('admin.users.index', compact('users'));
    }
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'ci' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'direccion' => 'required|string|max:500',
            'fecha_nacimiento' => 'required|date',
            'vive_con_representado' => 'boolean',
            'role' => 'required|in:admin,user',
        ]);

        $user->update($request->all());

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $user)
    {
        // No permitir que el admin se elimine a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Eliminar representados asociados
        $user->representados()->delete();
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}