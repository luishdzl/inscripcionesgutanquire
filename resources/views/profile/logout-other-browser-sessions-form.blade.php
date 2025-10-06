<x-form-section submit="logoutOtherBrowserSessions">
    <x-slot name="title">
        {{ __('Sesiones del Navegador') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Administre y cierre sus sesiones activas en otros navegadores y dispositivos.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <div class="max-w-xl text-sm text-gray-600">
                {{ __('Si es necesario, puede cerrar todas sus otras sesiones de navegador en todos sus dispositivos. Algunas de sus sesiones recientes se enumeran a continuación; sin embargo, esta lista puede no ser exhaustiva. Si cree que su cuenta ha sido comprometida, también debe actualizar su contraseña.') }}
            </div>

            @if (count($this->sessions) > 0)
                <div class="mt-5 space-y-6">
                    @foreach ($this->sessions as $session)
                        <div class="flex items-center">
                            <div>
                                @if ($session->agent->isDesktop())
                                    <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-gray-500">
                                        <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-gray-500">
                                        <path d="M0 0h24v24H0z" stroke="none"></path><rect x="7" y="4" width="10" height="16" rx="1"></rect><path d="M11 5h2M12 17v.01"></path>
                                    </svg>
                                @endif
                            </div>

                            <div class="ms-3">
                                <div class="text-sm text-gray-600">
                                    {{ $session->agent->platform() ? $session->agent->platform() : 'Unknown' }} - {{ $session->agent->browser() ? $session->agent->browser() : 'Unknown' }}
                                </div>

                                <div>
                                    <div class="text-xs text-gray-500">
                                        {{ $session->ip_address }},
                                        @if ($session->is_current_device)
                                            <span class="text-green-500 font-semibold">{{ __('Este dispositivo') }}</span>
                                        @else
                                            {{ __('Última actividad') }} {{ $session->last_active }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center mt-5">
                <x-button wire:loading.attr="disabled">
                    {{ __('Cerrar Otras Sesiones del Navegador') }}
                </x-button>

                <x-action-message class="ms-3" on="loggedOut">
                    {{ __('Hecho.') }}
                </x-action-message>
            </div>
        </div>
    </x-slot>
</x-form-section>