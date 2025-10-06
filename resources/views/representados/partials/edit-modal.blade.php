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