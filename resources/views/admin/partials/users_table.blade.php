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
                Representados
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Perfil Completo
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
                @foreach($user->representados as $representado)
                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-1 mr-1">
                    {{ $representado->nombres }}
                </span>
                @endforeach
                <span class="text-xs text-gray-500">{{ $user->representados_count }} representado(s)</span>
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
        </tr>
        @empty
        <tr>
            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                No hay representantes registrados.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>