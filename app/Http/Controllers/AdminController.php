<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Representado;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = User::with('representados')
                    ->where('role', 'user')
                    ->withCount('representados');

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

        $users = $query->latest()->get();

        $stats = [
            'total_representantes' => User::where('role', 'user')->count(),
            'total_representados' => Representado::count(),
            'total_usuarios' => User::count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'users' => $users,
                'html' => view('admin.partials.users_table', compact('users'))->render()
            ]);
        }

        return view('admin.dashboard', compact('users', 'stats'));
    }
}