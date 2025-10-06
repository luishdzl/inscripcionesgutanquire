<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Foto
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nombres
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                CI
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nivel Académico
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Representante
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Acciones
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200" id="representados-table-body">
        @forelse($representados as $representado)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($representado->foto)
                    <img src="{{ asset('storage/' . $representado->foto) }}" alt="Foto" class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">
                    {{ $representado->nombres }} {{ $representado->apellidos }}
                </div>
                <div class="text-sm text-gray-500">
                    {{ $representado->fecha_nacimiento->format('d/m/Y') }}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $representado->ci ?? 'N/A' }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ $representado->nivel_academico }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $representado->user->nombre_completo }}</div>
                <div class="text-sm text-gray-500">{{ $representado->user->email }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button type="button" 
                        onclick="abrirModalEdicion({{ $representado->id }})"
                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                    Editar
                </button>
                <form action="{{ route('representados.destroy', $representado->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de eliminar este representado?')">Eliminar</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                No hay representados registrados.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($representados->hasPages())
<div class="mt-4" id="pagination-container">
    {{ $representados->links() }}
</div>
@endif