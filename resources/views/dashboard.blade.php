<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sistema de Inscripción') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->role === 'admin')
                <!-- Dashboard Admin -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                        <h3 class="text-lg font-semibold text-gray-800">Total Representantes</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ \App\Models\User::where('role', 'user')->count() }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <h3 class="text-lg font-semibold text-gray-800">Total Representados</h3>
                        <p class="text-3xl font-bold text-green-600">{{ \App\Models\Representado::count() }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                        <h3 class="text-lg font-semibold text-gray-800">Usuarios Registrados</h3>
                        <p class="text-3xl font-bold text-purple-600">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Registros Recientes</h3>
                            <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ver Todos los Registros
                            </a>
                        </div>
                        <!-- Aquí puedes agregar una tabla de registros recientes -->
                    </div>
                </div>
            @else
                <!-- Dashboard Usuario Normal -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">Bienvenido al Sistema de Inscripción</h3>
                            <p class="text-gray-600 mb-6">Gestiona tus inscripciones de manera fácil y rápida</p>
                            
                            <!-- Tarjeta de estado del perfil -->
                            @if(!auth()->user()->perfil_completo)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6 max-w-2xl mx-auto">
                                <div class="flex justify-center items-center">

                                    <div class="justify-center">
                                        <div class="ml-4 flex flex justify-center items-center">
                                        <h3 class="text-lg font-medium text-yellow-800">
                                            Complete su perfil
                                        </h3>
                                        <svg class="h-8 w-8 text-yellow-400" fill="#EAB308" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                        <div class="text-gray-600 mb-4">
                                            <p>Para poder registrar representados, primero debe completar su información de representante.</p>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('profile.show') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                                                Completar Perfil
                                            </a>
                                        </div>
                                    </div>

                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6 max-w-2xl mx-auto">
                                <div class="flex justify-center items-center">
                                    <div class="justify-center">


                                    <div class="ml-4 flex flex justify-center items-center">
                                        <h3 class="text-xl font-semibold text-gray-800 ">
                                            Perfil Completo
                                        </h3>
                                        <svg class="h-8 w-8 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                        <div class="text-gray-600 mb-4">
                                            <p>Su información de representante está completa. Ya puede gestionar representados.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                                    <a href="{{ route('profile.show') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold rounded inline-block">
                                        {{ auth()->user()->perfil_completo ? 'Editar Perfil' : 'Completar Perfil' }}
                                    
                                    <div class="text-blue-500 mb-4">
                                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Mi Perfil</h4>
                                    <p class="text-gray-600 mb-4">Completa o actualiza tu información de representante</p>
</a>
                                </div>

                                <div class="bg-green-50 p-6 rounded-lg border border-green-200 {{ !auth()->user()->perfil_completo ? 'opacity-50' : '' }}">
                                    @if(auth()->user()->perfil_completo)
                                    <a href="{{ route('representados.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold rounded inline-block">
                                        Ver Representados
                                    <div class="text-green-500 mb-4">
                                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0.99 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4.354a4 4 0 100.1 .09M15 21H2v-1a6 6 0 0112 0v1zm0 0h9v-1a6 8 0 00-10-.39m7.562-9a2.5 2.5 0 11-5 0 2.5 2.54 0 015 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Gestionar Representados</h4>
                                    <p class="text-gray-600 mb-4">Administra la información de los representados</p>

                                    </a>
                                    @else
                                    <button class="bg-gray-400 text-white font-bold py-2 px-4 rounded inline-block cursor-not-allowed" disabled>
                                        Completa tu perfil primero
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Estadísticas del usuario -->
                            @if(auth()->user()->perfil_completo)
                            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4 max-w-4xl mx-auto">
                                <div class="bg-white p-4 rounded-lg shadow text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ auth()->user()->representados_count ?? 0 }}</div>
                                    <div class="text-sm text-gray-600">Representados Registrados</div>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ auth()->user()->representados()->where('nivel_academico', 'Primaria')->count() }}</div>
                                    <div class="text-sm text-gray-600">En Primaria</div>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow text-center">
                                    <div class="text-2xl font-bold text-purple-600">{{ auth()->user()->representados()->where('nivel_academico', 'Secundaria')->count() }}</div>
                                    <div class="text-sm text-gray-600">En Secundaria</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>