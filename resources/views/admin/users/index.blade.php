<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Todos los Usuarios</h3>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" id="success-message">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" id="error-message">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Filtros y Búsqueda -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <input type="text" id="search-users" placeholder="Buscar por CI, nombre, email, teléfono..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                        <div>
                            <select id="filter-role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos los roles</option>
                                <option value="admin">Administrador</option>
                                <option value="user">Usuario</option>
                            </select>
                        </div>
                        <div>
                            <select id="filter-perfil" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Estado del perfil</option>
                                <option value="1">Perfil Completo</option>
                                <option value="0">Perfil Incompleto</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tabla de usuarios -->
                    <div id="users-table-container">
                        @include('admin.users.partials.users_table', ['users' => $users])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-users');
            const roleFilter = document.getElementById('filter-role');
            const perfilFilter = document.getElementById('filter-perfil');
            
            let searchTimeout;

            function loadUsers(page = 1) {
                const searchTerm = searchInput.value;
                const role = roleFilter.value;
                const perfil = perfilFilter.value;
                
                const url = new URL('{{ route('admin.users.index') }}');
                url.searchParams.set('page', page);
                if (searchTerm) url.searchParams.set('search', searchTerm);
                if (role) url.searchParams.set('role', role);
                if (perfil) url.searchParams.set('perfil_completo', perfil);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('users-table-container').innerHTML = data.html;
                    
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
                        loadUsers(page);
                    });
                });
            }

            function performSearch() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadUsers(1);
                }, 500);
            }

            searchInput.addEventListener('input', performSearch);
            roleFilter.addEventListener('change', () => loadUsers(1));
            perfilFilter.addEventListener('change', () => loadUsers(1));

            // Initial attachment of pagination listeners
            attachPaginationListeners();

            // Auto-hide messages
            setTimeout(() => {
                const successMsg = document.getElementById('success-message');
                const errorMsg = document.getElementById('error-message');
                if (successMsg) successMsg.style.display = 'none';
                if (errorMsg) errorMsg.style.display = 'none';
            }, 5000);
        });
    </script>
</x-app-layout>