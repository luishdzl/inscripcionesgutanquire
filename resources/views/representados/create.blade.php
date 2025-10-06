<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nuevo Representado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                @if(!auth()->user()->perfil_completo)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Complete su perfil primero
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
                @else
                <form method="POST" action="{{ route('representados.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Información del Representante -->
                    <div class="mb-8 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">Información del Representante</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-semibold">Nombre:</span> {{ auth()->user()->nombre_completo }}
                            </div>
                            <div>
                                <span class="font-semibold">CI:</span> {{ auth()->user()->ci }}
                            </div>
                            <div>
                                <span class="font-semibold">Teléfono:</span> {{ auth()->user()->telefono }}
                            </div>
                            <div>
                                <span class="font-semibold">Dirección:</span> {{ auth()->user()->direccion }}
                            </div>
                        </div>
                        @if(auth()->user()->vive_con_representado)
                        <div class="mt-2 text-sm text-green-600">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Vive con el representado - La dirección se heredará automáticamente
                        </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Columna Izquierda -->
                        <div class="space-y-4">
                            <div>
                                <x-label for="nombres" value="Nombres *" />
                                <x-input id="nombres" class="block mt-1 w-full" type="text" name="nombres" :value="old('nombres')" required />
                                @error('nombres')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="apellidos" value="Apellidos *" />
                                <x-input id="apellidos" class="block mt-1 w-full" type="text" name="apellidos" :value="old('apellidos')" required />
                                @error('apellidos')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="ci" value="CI (Opcional)" />
                                <x-input id="ci" class="block mt-1 w-full" type="text" name="ci" :value="old('ci')" />
                                @error('ci')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="fecha_nacimiento" value="Fecha de Nacimiento *" />
                                <x-input id="fecha_nacimiento" class="block mt-1 w-full" type="date" name="fecha_nacimiento" :value="old('fecha_nacimiento')" required />
                                @error('fecha_nacimiento')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Columna Derecha -->
                        <div class="space-y-4">
                            <div>
                                <x-label for="telefono" value="Teléfono" />
                                <x-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" />
                                @error('telefono')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="nivel_academico" value="Nivel Académico *" />
                                <select id="nivel_academico" name="nivel_academico" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required>
                                    <option value="">Seleccione un nivel</option>
                                    <option value="Preescolar" {{ old('nivel_academico') == 'Preescolar' ? 'selected' : '' }}>Preescolar</option>
                                    <option value="Primaria" {{ old('nivel_academico') == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                                    <option value="Secundaria" {{ old('nivel_academico') == 'Secundaria' ? 'selected' : '' }}>Secundaria</option>
                                    <option value="Bachillerato" {{ old('nivel_academico') == 'Bachillerato' ? 'selected' : '' }}>Bachillerato</option>
                                    <option value="Universitario" {{ old('nivel_academico') == 'Universitario' ? 'selected' : '' }}>Universitario</option>
                                </select>
                                @error('nivel_academico')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="direccion" value="Dirección" />
                                <textarea id="direccion" name="direccion" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" placeholder="{{ auth()->user()->vive_con_representado ? 'Se heredará la dirección del representante' : '' }}">{{ old('direccion') }}</textarea>
                                @error('direccion')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                @if(auth()->user()->vive_con_representado)
                                <p class="text-sm text-green-600 mt-1">Si deja vacío, se usará la dirección del representante</p>
                                @endif
                            </div>

                            <div>
                                <x-label for="foto" value="Foto del Representado" />
                                <x-input id="foto" class="block mt-1 w-full" type="file" name="foto" accept="image/*" />
                                @error('foto')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('representados.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancelar
                        </a>
                        <x-button>
                            Registrar Representado
                        </x-button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>