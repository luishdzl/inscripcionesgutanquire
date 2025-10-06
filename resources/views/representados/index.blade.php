<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Representados Registrados') }}
        </h2>
    </x-slot>
<!-- Agregar esto después del título y antes de la tabla -->
<div class="mb-6">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" id="search-representados" placeholder="Buscar por nombre, CI, nivel académico..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
        </div>
        <div class="w-full md:w-64">
            <select id="filter-nivel" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todos los niveles</option>
                <option value="Preescolar">Preescolar</option>
                <option value="Primaria">Primaria</option>
                <option value="Secundaria">Secundaria</option>
                <option value="Bachillerato">Bachillerato</option>
                <option value="Universitario">Universitario</option>
            </select>
        </div>
    </div>
</div>

<!-- Cambiar la tabla para que esté en un contenedor -->
<div id="representados-table-container">
    <!-- La tabla existente aquí -->
</div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Lista de Representados</h3>
                        @if(auth()->user()->role === 'user' && auth()->user()->perfil_completo)
                        <a href="{{ route('representados.create') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-black font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 ease-in-out border border-green-400">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Nuevo Representado
                        </a>
                        @endif
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" id="success-message">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if(auth()->user()->role === 'user' && !auth()->user()->perfil_completo)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        Complete su perfil
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Para poder registrar representados, primero debe completar su información de representante.</p>
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ route('profile.edit') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded text-sm">
                                            Completar Perfil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="overflow-x-auto flex justify-center">
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
                                    @if(auth()->user()->role === 'admin')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Representante
                                    </th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
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
                                    @if(auth()->user()->role === 'admin')
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $representado->user->nombre_completo }}</div>
                                        <div class="text-sm text-gray-500">{{ $representado->user->email }}</div>
                                    </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button type="button" 
                                                onclick="abrirModalEdicion({{ $representado->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Editar
                                        </button>
                                        @if(auth()->user()->role === 'admin' || $representado->user_id === auth()->id())
                                        <form action="{{ route('representados.destroy', $representado->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de eliminar este representado?')">Eliminar</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No hay representados registrados.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $representados->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Editar Representado</h3>
                    <button type="button" onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Columna Izquierda -->
                        <div class="space-y-4">
                            <div>
                                <x-label for="edit_nombres" value="Nombres *" />
                                <x-input id="edit_nombres" class="block mt-1 w-full" type="text" name="nombres" required />
                                <span id="error_nombres" class="text-red-500 text-xs mt-1 hidden"></span>
                            </div>

                            <div>
                                <x-label for="edit_apellidos" value="Apellidos *" />
                                <x-input id="edit_apellidos" class="block mt-1 w-full" type="text" name="apellidos" required />
                                <span id="error_apellidos" class="text-red-500 text-xs mt-1 hidden"></span>
                            </div>

                            <div>
                                <x-label for="edit_ci" value="CI (Opcional)" />
                                <x-input id="edit_ci" class="block mt-1 w-full" type="text" name="ci" />
                                <span id="error_ci" class="text-red-500 text-xs mt-1 hidden"></span>
                            </div>

                            <div>
                                <x-label for="edit_fecha_nacimiento" value="Fecha de Nacimiento *" />
                                <x-input id="edit_fecha_nacimiento" class="block mt-1 w-full" type="date" name="fecha_nacimiento" required />
                                <span id="error_fecha_nacimiento" class="text-red-500 text-xs mt-1 hidden"></span>
                            </div>
                        </div>

                        <!-- Columna Derecha -->
                        <div class="space-y-4">
                            <div>
                                <x-label for="edit_telefono" value="Teléfono" />
                                <x-input id="edit_telefono" class="block mt-1 w-full" type="text" name="telefono" />
                                <span id="error_telefono" class="text-red-500 text-xs mt-1 hidden"></span>
                            </div>

                            <div>
                                <x-label for="edit_nivel_academico" value="Nivel Académico *" />
                                <select id="edit_nivel_academico" name="nivel_academico" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required>
                                    <option value="">Seleccione un nivel</option>
                                    <option value="Preescolar">Preescolar</option>
                                    <option value="Primaria">Primaria</option>
                                    <option value="Secundaria">Secundaria</option>
                                    <option value="Bachillerato">Bachillerato</option>
                                    <option value="Universitario">Universitario</option>
                                </select>
                                <span id="error_nivel_academico" class="text-red-500 text-xs mt-1 hidden"></span>
                            </div>

                            <div>
                                <x-label for="edit_direccion" value="Dirección" />
                                <textarea id="edit_direccion" name="direccion" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                                <span id="error_direccion" class="text-red-500 text-xs mt-1 hidden"></span>
                            </div>

                            <div>
                                <x-label for="edit_foto" value="Foto del Representado" />
                                <x-input id="edit_foto" class="block mt-1 w-full" type="file" name="foto" accept="image/*" />
                                <span id="error_foto" class="text-red-500 text-xs mt-1 hidden"></span>
                                <div id="foto_actual" class="mt-2 hidden">
                                    <p class="text-sm text-gray-600">Foto actual:</p>
                                    <img id="foto_actual_img" src="" alt="Foto actual" class="w-20 h-20 object-cover rounded mt-1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <button type="button" onclick="cerrarModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-3">
                            Cancelar
                        </button>
                        <x-button type="submit">
                            Actualizar Representado
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Variable global para la URL de storage
        const storageUrl = "{{ asset('storage') }}";

        function abrirModalEdicion(representadoId) {
            // Ocultar mensaje de éxito anterior
            document.getElementById('success-message')?.classList.add('hidden');
            
            // Limpiar errores anteriores
            limpiarErrores();
            
            // Cargar datos del representado
            fetch(`/representados/${representadoId}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 403) {
                        throw new Error('No tienes permisos para editar este representado.');
                    }
                    throw new Error('Error al cargar los datos del representado');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const representado = data.representado;
                    
                    // Llenar el formulario con los datos
                    document.getElementById('edit_nombres').value = representado.nombres;
                    document.getElementById('edit_apellidos').value = representado.apellidos;
                    document.getElementById('edit_ci').value = representado.ci || '';
                    document.getElementById('edit_fecha_nacimiento').value = representado.fecha_nacimiento;
                    document.getElementById('edit_telefono').value = representado.telefono || '';
                    document.getElementById('edit_direccion').value = representado.direccion || '';
                    document.getElementById('edit_nivel_academico').value = representado.nivel_academico;
                    
                    // Manejar la foto actual
                    if (representado.foto) {
                        document.getElementById('foto_actual').classList.remove('hidden');
                        document.getElementById('foto_actual_img').src = `${storageUrl}/${representado.foto}`;
                    } else {
                        document.getElementById('foto_actual').classList.add('hidden');
                    }
                    
                    // Actualizar la acción del formulario
                    document.getElementById('editForm').action = `/representados/${representadoId}`;
                    
                    // Mostrar el modal
                    document.getElementById('editModal').classList.remove('hidden');
                } else {
                    alert('Error: ' + (data.error || 'No se pudieron cargar los datos'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Error al cargar los datos del representado');
            });
        }

        function cerrarModal() {
            document.getElementById('editModal').classList.add('hidden');
            // Limpiar errores
            limpiarErrores();
        }

        function limpiarErrores() {
            const errorElements = document.querySelectorAll('[id^="error_"]');
            errorElements.forEach(element => {
                element.classList.add('hidden');
                element.textContent = '';
            });
        }

        // Manejar el envío del formulario
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = this.action;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar modal y recargar la página
                    cerrarModal();
                    window.location.reload();
                } else if (data.errors) {
                    // Mostrar errores de validación
                    mostrarErrores(data.errors);
                } else if (data.error) {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el representado');
            });
        });

        function mostrarErrores(errors) {
            limpiarErrores();
            
            for (const field in errors) {
                const errorElement = document.getElementById(`error_${field}`);
                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                    errorElement.classList.remove('hidden');
                }
            }
        }

        // Auto-ocultar mensaje de éxito después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.classList.add('hidden');
                }, 5000);
            }
        });
    </script>
</x-app-layout>