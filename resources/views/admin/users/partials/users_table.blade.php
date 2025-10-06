<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                CI
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nombre Completo
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Teléfono
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Rol
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Representados
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Perfil Completo
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Acciones
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200" id="users-table-body">
        @forelse($users as $user)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $user->ci ?? 'N/A' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">
                    {{ $user->nombre_completo }}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $user->email }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $user->telefono ?? 'N/A' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ $user->role === 'admin' ? 'Administrador' : 'Usuario' }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm text-gray-900">{{ $user->representados_count }} representado(s)</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($user->perfil_completo)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Completo
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    Incompleto
                </span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de eliminar este usuario? Se eliminarán todos sus representados.')">Eliminar</button>
                </form>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                No hay usuarios registrados.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($users->hasPages())
<div class="mt-4" id="pagination-container">
    {{ $users->links() }}
</div>
@endif