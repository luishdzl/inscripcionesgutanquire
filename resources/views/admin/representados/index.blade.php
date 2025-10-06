<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Representados') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Todos los Representados</h3>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" id="success-message">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Filtros y Búsqueda -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <input type="text" id="search-representados" placeholder="Buscar por nombre, CI, nivel académico, representante..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                        <div>
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

                    <!-- Tabla de representados -->
                    <div id="representados-table-container">
                        @include('admin.representados.partials.representados_table', ['representados' => $representados])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir el mismo modal de edición que en representados/index.blade.php -->
    @include('representados.partials.edit-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-representados');
            const nivelFilter = document.getElementById('filter-nivel');
            
            let searchTimeout;

            function loadRepresentados(page = 1) {
                const searchTerm = searchInput.value;
                const nivel = nivelFilter.value;
                
                const url = new URL('{{ route('admin.representados.index') }}');
                url.searchParams.set('page', page);
                if (searchTerm) url.searchParams.set('search', searchTerm);
                if (nivel) url.searchParams.set('nivel_academico', nivel);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('representados-table-container').innerHTML = data.html;
                    
                    // Re-attach event listeners to new pagination links
                    attachPaginationListeners();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

            function attachPaginationListeners() {
                document.querySelectorAll('.pagination a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page');
                        loadRepresentados(page);
                    });
                });
            }

            function performSearch() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadRepresentados(1);
                }, 500);
            }

            searchInput.addEventListener('input', performSearch);
            nivelFilter.addEventListener('change', () => loadRepresentados(1));

            // Initial attachment of pagination listeners
            attachPaginationListeners();

            // Auto-hide success message
            setTimeout(() => {
                const successMsg = document.getElementById('success-message');
                if (successMsg) successMsg.style.display = 'none';
            }, 5000);
        });
    </script>
</x-app-layout>