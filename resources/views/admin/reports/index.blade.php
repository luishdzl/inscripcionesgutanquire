<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generar Reportes Personalizados') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.reports.generate') }}" id="report-form">
                    @csrf

                    <!-- Tipo de Reporte -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Tipo de Reporte</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="report_type" value="representados" class="mr-3" checked>
                                <div>
                                    <div class="font-medium">Reporte de Representados</div>
                                    <div class="text-sm text-gray-600">Datos de los representados con información básica del representante</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="report_type" value="users" class="mr-3">
                                <div>
                                    <div class="font-medium">Reporte de Usuarios</div>
                                    <div class="text-sm text-gray-600">Datos de los usuarios/representantes</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="report_type" value="combined" class="mr-3">
                                <div>
                                    <div class="font-medium">Reporte Combinado</div>
                                    <div class="text-sm text-gray-600">Datos completos de representados y representantes</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Columnas a Incluir -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Columnas a Incluir</h3>
                        
                        <!-- Representados -->
                        <div id="representados-columns" class="column-section">
                            <h4 class="font-medium text-gray-700 mb-3">Datos del Representado</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="representado_id" class="mr-2 column-checkbox">
                                    <span>ID Representado</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="representado_nombres" class="mr-2 column-checkbox" checked>
                                    <span>Nombres</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="representado_apellidos" class="mr-2 column-checkbox" checked>
                                    <span>Apellidos</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="representado_ci" class="mr-2 column-checkbox">
                                    <span>CI</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="representado_fecha_nacimiento" class="mr-2 column-checkbox">
                                    <span>Fecha Nacimiento</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="representado_telefono" class="mr-2 column-checkbox">
                                    <span>Teléfono</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="representado_direccion" class="mr-2 column-checkbox">
                                    <span>Dirección</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="representado_nivel_academico" class="mr-2 column-checkbox" checked>
                                    <span>Nivel Académico</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="representado_fecha_registro" class="mr-2 column-checkbox">
                                    <span>Fecha Registro</span>
                                </label>
                            </div>
                        </div>

                        <!-- Representantes -->
                        <div id="users-columns" class="column-section hidden">
                            <h4 class="font-medium text-gray-700 mb-3">Datos del Representante</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="user_id" class="mr-2 column-checkbox">
                                    <span>ID Usuario</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="user_ci" class="mr-2 column-checkbox" checked>
                                    <span>CI</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="user_nombres" class="mr-2 column-checkbox" checked>
                                    <span>Nombres</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="user_apellidos" class="mr-2 column-checkbox" checked>
                                    <span>Apellidos</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="user_email" class="mr-2 column-checkbox" checked>
                                    <span>Email</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="user_telefono" class="mr-2 column-checkbox">
                                    <span>Teléfono</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="user_direccion" class="mr-2 column-checkbox">
                                    <span>Dirección</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="user_fecha_nacimiento" class="mr-2 column-checkbox">
                                    <span>Fecha Nacimiento</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="user_role" class="mr-2 column-checkbox" checked>
                                    <span>Rol</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="user_perfil_completo" class="mr-2 column-checkbox">
                                    <span>Perfil Completo</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="user_cantidad_representados" class="mr-2 column-checkbox">
                                    <span>Cant. Representados</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="user_fecha_registro" class="mr-2 column-checkbox">
                                    <span>Fecha Registro</span>
                                </label>
                            </div>
                        </div>

                        <!-- Combinado -->
                        <div id="combined-columns" class="column-section hidden">
                            <h4 class="font-medium text-gray-700 mb-3">Datos Combinados</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                <!-- Representado -->
                                <div class="col-span-full mb-4">
                                    <h5 class="font-medium text-gray-600 mb-2">Datos del Representado</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="columns[]" value="representado_nombres" class="mr-2 column-checkbox" checked>
                                            <span>Nombres Rep.</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="columns[]" value="representado_apellidos" class="mr-2 column-checkbox" checked>
                                            <span>Apellidos Rep.</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="columns[]" value="representado_nivel_academico" class="mr-2 column-checkbox" checked>
                                            <span>Nivel Académico</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Representante -->
                                <div class="col-span-full">
                                    <h5 class="font-medium text-gray-600 mb-2">Datos del Representante</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="columns[]" value="user_nombres" class="mr-2 column-checkbox" checked>
                                            <span>Nombres Repre.</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="columns[]" value="user_apellidos" class="mr-2 column-checkbox" checked>
                                            <span>Apellidos Repre.</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="columns[]" value="user_email" class="mr-2 column-checkbox" checked>
                                            <span>Email</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" id="select-all" class="text-blue-600 hover:text-blue-800 text-sm mr-4">Seleccionar Todo</button>
                            <button type="button" id="deselect-all" class="text-red-600 hover:text-red-800 text-sm">Deseleccionar Todo</button>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Filtros (Opcional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Filtros para Representados -->
                            <div id="representados-filters" class="filter-section">
                                <div class="mb-4">
                                    <x-label for="nivel_academico" value="Nivel Académico" />
                                    <select id="nivel_academico" name="filters[nivel_academico]" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                        <option value="">Todos los niveles</option>
                                        <option value="Preescolar">Preescolar</option>
                                        <option value="Primaria">Primaria</option>
                                        <option value="Secundaria">Secundaria</option>
                                        <option value="Bachillerato">Bachillerato</option>
                                        <option value="Universitario">Universitario</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <x-label for="user_id" value="Representante Específico" />
                                    <select id="user_id" name="filters[user_id]" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                        <option value="">Todos los representantes</option>
                                        @foreach(\App\Models\User::where('role', 'user')->get() as $user)
                                            <option value="{{ $user->id }}">{{ $user->nombre_completo }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Filtros para Usuarios -->
                            <div id="users-filters" class="filter-section hidden">
                                <div class="mb-4">
                                    <x-label for="role" value="Rol" />
                                    <select id="role" name="filters[role]" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                        <option value="">Todos los roles</option>
                                        <option value="admin">Administrador</option>
                                        <option value="user">Usuario</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <x-label for="perfil_completo" value="Perfil Completo" />
                                    <select id="perfil_completo" name="filters[perfil_completo]" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                        <option value="">Todos</option>
                                        <option value="completo">Perfil Completo</option>
                                        <option value="incompleto">Perfil Incompleto</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filtros de Fecha (comunes) -->
                            <div class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-label for="fecha_desde" value="Fecha Desde" />
                                    <x-input id="fecha_desde" class="block mt-1 w-full" type="date" name="filters[fecha_desde]" />
                                </div>
                                <div>
                                    <x-label for="fecha_hasta" value="Fecha Hasta" />
                                    <x-input id="fecha_hasta" class="block mt-1 w-full" type="date" name="filters[fecha_hasta]" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formato -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Formato de Exportación</h3>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="format" value="csv" class="mr-2" checked>
                                <span>CSV (Compatible con Excel)</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <x-button type="submit" class="bg-green-600 hover:bg-green-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Generar Reporte
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reportTypeRadios = document.querySelectorAll('input[name="report_type"]');
            const columnSections = document.querySelectorAll('.column-section');
            const filterSections = document.querySelectorAll('.filter-section');
            const selectAllBtn = document.getElementById('select-all');
            const deselectAllBtn = document.getElementById('deselect-all');

            function showSection(type) {
                // Ocultar todas las secciones
                columnSections.forEach(section => section.classList.add('hidden'));
                filterSections.forEach(section => section.classList.add('hidden'));

                // Mostrar secciones según el tipo
                document.getElementById(`${type}-columns`).classList.remove('hidden');
                document.getElementById(`${type}-filters`)?.classList.remove('hidden');
            }

            // Cambiar secciones cuando cambia el tipo de reporte
            reportTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    showSection(this.value);
                });
            });

            // Seleccionar/Deseleccionar todos los checkboxes
            selectAllBtn.addEventListener('click', function() {
                document.querySelectorAll('.column-checkbox').forEach(checkbox => {
                    checkbox.checked = true;
                });
            });

            deselectAllBtn.addEventListener('click', function() {
                document.querySelectorAll('.column-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
            });

            // Validación del formulario
            document.getElementById('report-form').addEventListener('submit', function(e) {
                const checkedColumns = document.querySelectorAll('.column-checkbox:checked');
                if (checkedColumns.length === 0) {
                    e.preventDefault();
                    alert('Por favor, seleccione al menos una columna para el reporte.');
                }
            });

            // Inicializar con el primer tipo
            showSection('representados');
        });
    </script>

    <style>
        .column-section, .filter-section {
            transition: all 0.3s ease;
        }
    </style>
</x-app-layout>