<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <!-- Banner de perfil incompleto (solo para usuarios normales sin perfil completo) -->
            @if(auth()->user()->role === 'user' && !auth()->user()->perfil_completo)
            <div class="mb-8">
                <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-lg shadow-lg p-4 border-l-4 border-yellow-600">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-white animate-bounce" fill="#EAB308" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-base font-bold text-black">¡Complete su perfil de representante!</h3>
                            <p class="mt-1 text-yellow-100 text-xs">
                                Para poder registrar representados, necesita completar toda su información personal.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Indicador de progreso del perfil (solo para usuarios normales) -->
            @if(auth()->user()->role === 'user')
            <div class="mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-base font-medium text-gray-900">Progreso del Perfil</h3>
                        <span id="progressPercentage" class="text-xs font-medium {{ auth()->user()->perfil_completo ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ auth()->user()->perfil_completo ? '100% Completado' : 'Perfil Incompleto' }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                        <div id="progressBar" class="h-2 pb-2 rounded-full transition-all duration-500 ease-out 
                            {{ auth()->user()->perfil_completo ? 'bg-green-500 w-full' : 'bg-yellow-500' }}"
                            style="width: {{ auth()->user()->perfil_completo ? '100' : '60' }}%">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                        @php
                            $campos = [
                                'CI' => !empty(auth()->user()->ci),
                                'Nombres' => !empty(auth()->user()->nombres),
                                'Apellidos' => !empty(auth()->user()->apellidos),
                                'Teléfono' => !empty(auth()->user()->telefono),
                                'Dirección' => !empty(auth()->user()->direccion),
                                'Fecha Nac.' => !empty(auth()->user()->fecha_nacimiento),
                            ];
                        @endphp
                        @foreach($campos as $campo => $completado)
                        <div class="flex items-center">
                            @if($completado)
                            <svg class="h-3 w-3 text-green-500 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            @else
                            <svg class="h-3 w-3 text-yellow-500 mr-1 flex-shrink-0 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            @endif
                            <span class="{{ $completado ? 'text-gray-600' : 'text-yellow-600 font-medium' }} truncate">
                                {{ $campo }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Sección de Información de Representante (solo para usuarios normales) -->
            @if(auth()->user()->role === 'user')
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium text-gray-900">Información de Representante</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Actualice su información personal de representante y dirección.
                        </p>
                        @if(!auth()->user()->perfil_completo)
                        <div class="mt-3 p-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-xs text-yellow-700">
                                <span class="font-semibold">Complete todos los campos marcados con *</span> para habilitar el registro de representados.
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            @if(!auth()->user()->perfil_completo)
                            <div class="mb-4 p-3 bg-gradient-to-r from-yellow-50 to-yellow-100 border-l-4 border-yellow-400 rounded">
                                <div class="flex items-center">
                                    <svg class="w-6 text-yellow-500 mr-2 flex-shrink-0" fill="#EAB308" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-yellow-700 text-sm font-medium">Perfil incompleto</span>
                                </div>
                            </div>
                            @endif

                            <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Columna Izquierda -->
                                    <div class="space-y-4">
                                        <div>
                                            <x-label for="ci" value="CI *" class="{{ empty($user->ci) ? 'text-yellow-600 font-semibold' : '' }} text-sm" />
                                            <x-input id="ci" class="block mt-1 w-full {{ empty($user->ci) ? 'border-yellow-300 focus:border-yellow-500 focus:ring-yellow-200' : '' }}" 
                                                     type="text" name="ci" :value="old('ci', $user->ci)" required 
                                                     oninput="actualizarProgreso()" />
                                            @error('ci')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @else
                                                @if(empty($user->ci))
                                                <p class="text-yellow-600 text-xs mt-1">⚠️ Campo requerido</p>
                                                @endif
                                            @enderror
                                        </div>

                                        <div>
                                            <x-label for="nombres" value="Nombres *" class="{{ empty($user->nombres) ? 'text-yellow-600 font-semibold' : '' }} text-sm" />
                                            <x-input id="nombres" class="block mt-1 w-full {{ empty($user->nombres) ? 'border-yellow-300 focus:border-yellow-500 focus:ring-yellow-200' : '' }}" 
                                                     type="text" name="nombres" :value="old('nombres', $user->nombres)" required 
                                                     oninput="actualizarProgreso()" />
                                            @error('nombres')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @else
                                                @if(empty($user->nombres))
                                                <p class="text-yellow-600 text-xs mt-1">⚠️ Campo requerido</p>
                                                @endif
                                            @enderror
                                        </div>

                                        <div>
                                            <x-label for="apellidos" value="Apellidos *" class="{{ empty($user->apellidos) ? 'text-yellow-600 font-semibold' : '' }} text-sm" />
                                            <x-input id="apellidos" class="block mt-1 w-full {{ empty($user->apellidos) ? 'border-yellow-300 focus:border-yellow-500 focus:ring-yellow-200' : '' }}" 
                                                     type="text" name="apellidos" :value="old('apellidos', $user->apellidos)" required 
                                                     oninput="actualizarProgreso()" />
                                            @error('apellidos')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @else
                                                @if(empty($user->apellidos))
                                                <p class="text-yellow-600 text-xs mt-1">⚠️ Campo requerido</p>
                                                @endif
                                            @enderror
                                        </div>

                                        <div>
                                            <x-label for="fecha_nacimiento" value="Fecha de Nacimiento *" class="{{ empty($user->fecha_nacimiento) ? 'text-yellow-600 font-semibold' : '' }} text-sm" />
                                            <x-input id="fecha_nacimiento" class="block mt-1 w-full {{ empty($user->fecha_nacimiento) ? 'border-yellow-300 focus:border-yellow-500 focus:ring-yellow-200' : '' }}" 
                                                     type="date" name="fecha_nacimiento" :value="old('fecha_nacimiento', $user->fecha_nacimiento?->format('Y-m-d'))" required 
                                                     onchange="actualizarProgreso()" />
                                            @error('fecha_nacimiento')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @else
                                                @if(empty($user->fecha_nacimiento))
                                                <p class="text-yellow-600 text-xs mt-1">⚠️ Campo requerido</p>
                                                @endif
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Columna Derecha -->
                                    <div class="space-y-4">
                                        <div>
                                            <x-label for="telefono" value="Teléfono *" class="{{ empty($user->telefono) ? 'text-yellow-600 font-semibold' : '' }} text-sm" />
                                            <x-input id="telefono" class="block mt-1 w-full {{ empty($user->telefono) ? 'border-yellow-300 focus:border-yellow-500 focus:ring-yellow-200' : '' }}" 
                                                     type="text" name="telefono" :value="old('telefono', $user->telefono)" required 
                                                     oninput="actualizarProgreso()" />
                                            @error('telefono')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @else
                                                @if(empty($user->telefono))
                                                <p class="text-yellow-600 text-xs mt-1">⚠️ Campo requerido</p>
                                                @endif
                                            @enderror
                                        </div>

                                        <div>
                                            <x-label for="direccion" value="Dirección *" class="{{ empty($user->direccion) ? 'text-yellow-600 font-semibold' : '' }} text-sm" />
                                            <textarea id="direccion" name="direccion" rows="3" 
                                                class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm {{ empty($user->direccion) ? 'border-yellow-300 focus:border-yellow-500 focus:ring-yellow-200' : '' }} text-sm" 
                                                required oninput="actualizarProgreso()">{{ old('direccion', $user->direccion) }}</textarea>
                                            @error('direccion')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @else
                                                @if(empty($user->direccion))
                                                <p class="text-yellow-600 text-xs mt-1">⚠️ Campo requerido</p>
                                                @endif
                                            @enderror
                                        </div>

                                        <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="vive_con_representado" value="1" 
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    {{ old('vive_con_representado', $user->vive_con_representado) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-600">Vive con el representado</span>
                                            </label>
                                            <p class="text-xs text-blue-600 mt-1">📍 Si marca esta opción, los representados heredarán automáticamente esta dirección</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                                    <div>
                                        @if(!auth()->user()->perfil_completo)
                                        <div class="flex items-center text-yellow-600">
                                            <svg class="w-8 mr-1 flex-shrink-0" fill="#EAB308" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-xs">Complete el formulario para habilitar el registro de representados</span>
                                        </div>
                                        @endif
                                    </div>
                                    <x-button class="{{ !auth()->user()->perfil_completo ? 'bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-200' : '' }} text-sm">
                                        <span class="flex items-center">
                                            @if(!auth()->user()->perfil_completo)
                                            <svg class="h-3 w-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            @endif
                                            {{ auth()->user()->perfil_completo ? 'Actualizar Perfil' : 'Completar Perfil' }}
                                        </span>
                                    </x-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')
                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-8 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>
                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-8 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>
                <x-section-border />
            @endif

            <div class="mt-8 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />
                <div class="mt-8 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>

    <script>
        function actualizarProgreso() {
            // Campos requeridos para el progreso
            const campos = [
                'ci',
                'nombres', 
                'apellidos',
                'telefono',
                'direccion',
                'fecha_nacimiento'
            ];

            let camposCompletados = 0;

            // Verificar cada campo
            campos.forEach(campo => {
                const elemento = document.getElementById(campo);
                if (elemento) {
                    if (elemento.type === 'checkbox') {
                        if (elemento.checked) {
                            camposCompletados++;
                        }
                    } else {
                        if (elemento.value && elemento.value.trim() !== '') {
                            camposCompletados++;
                        }
                    }
                }
            });

            // Calcular porcentaje
            const porcentaje = Math.round((camposCompletados / campos.length) * 100);
            
            // Actualizar barra de progreso
            const progressBar = document.getElementById('progressBar');
            const progressPercentage = document.getElementById('progressPercentage');
            
            if (progressBar && progressPercentage) {
                // Aplicar animación siempre
                progressBar.classList.add('transition-all', 'duration-500', 'ease-out');
                
                progressBar.style.width = porcentaje + '%';
                
                // Cambiar color según el progreso
                if (porcentaje === 100) {
                    progressBar.classList.remove('bg-yellow-500', 'bg-red-500');
                    progressBar.classList.add('bg-yellow-500');
                    progressPercentage.textContent = '100% Completado';
                    progressPercentage.classList.remove('text-yellow-600', 'text-red-600');
                    progressPercentage.classList.add('text-yellow-600');
                } else {
                    // Para todos los porcentajes menores a 100%, usar amarillo
                    progressBar.classList.remove('bg-yellow-500', 'bg-red-500');
                    progressBar.classList.add('bg-yellow-500');
                    progressPercentage.textContent = porcentaje + '% Completado';
                    progressPercentage.classList.remove('text-yellow-600', 'text-red-600');
                    progressPercentage.classList.add('text-yellow-600');
                }
            }
        }

        // Inicializar el progreso al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            actualizarProgreso();
            
            // También actualizar cuando cambie el checkbox
            const checkbox = document.querySelector('input[name="vive_con_representado"]');
            if (checkbox) {
                checkbox.addEventListener('change', actualizarProgreso);
            }
        });
    </script>
</x-app-layout>