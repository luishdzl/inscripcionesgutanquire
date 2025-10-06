<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <h3 class="text-lg font-semibold text-gray-800">Total Representantes</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_representantes'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <h3 class="text-lg font-semibold text-gray-800">Total Representados</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_representados'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <h3 class="text-lg font-semibold text-gray-800">Usuarios Registrados</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['total_usuarios'] }}</p>
                </div>
            </div>

            <!-- Botones de gestionar -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <a href="{{ route('admin.users.index') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-black font-bold py-4 px-6 rounded-lg text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 ease-in-out border border-blue-400">
                    <div class="flex items-center justify-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0.99 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4.354a4 4 0 100.1 .09M15 21H2v-1a6 6 0 0112 0v1zm0 0h9v-1a6 8 0 00-10-.39m7.562-9a2.5 2.5 0 11-5 0 2.5 2.54 0 015 0z"></path>
                        </svg>
                        Gestionar Usuarios
                    </div>
                </a>
                
                <a href="{{ route('admin.representados.index') }}" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-black font-bold py-4 px-6 rounded-lg text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 ease-in-out border border-green-400">
                    <div class="flex items-center justify-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Gestionar Representados
                    </div>
                </a>
            </div>

            <!-- Lista de Representantes -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Todos los Representantes</h3>
                        <div class="w-1/3">
                            <input type="text" id="search-users" placeholder="Buscar representantes..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                    </div>
                    
                    <div id="users-table-container">
                        @include('admin.partials.users_table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-users');
            let searchTimeout;

            function performSearch() {
                const searchTerm = searchInput.value;
                
                fetch(`{{ route('admin.dashboard') }}?search=${encodeURIComponent(searchTerm)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('users-table-container').innerHTML = data.html;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 500);
            });

            // Limpiar búsqueda cuando el input esté vacío
            searchInput.addEventListener('keyup', function() {
                if (this.value === '') {
                    performSearch();
                }
            });
        });
    </script>
</x-app-layout>