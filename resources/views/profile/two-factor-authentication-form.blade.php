<x-form-section submit="enableTwoFactorAuthentication">
    <x-slot name="title">
        {{ __('Autenticación de Dos Factores') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Agregue seguridad adicional a su cuenta usando la autenticación de dos factores.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            @if ($this->enabled)
                @if ($showingQrCode)
                    <div class="max-w-xl text-sm text-gray-600">
                        <p class="font-semibold">
                            {{ __('La autenticación de dos factores ahora está habilitada. Escanee el siguiente código QR usando la aplicación autenticadora de su teléfono.') }}
                        </p>
                    </div>

                    <div class="mt-4 dark:p-4 dark:w-56 dark:bg-white">
                        {!! $this->user->twoFactorQrCodeSvg() !!}
                    </div>
                @endif

                @if ($showingRecoveryCodes)
                    <div class="max-w-xl text-sm text-gray-600">
                        <p class="font-semibold">
                            {{ __('Guarde estos códigos de recuperación en un administrador de contraseñas seguro. Pueden usarse para recuperar el acceso a su cuenta si pierde su dispositivo de autenticación de dos factores.') }}
                        </p>
                    </div>

                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                        @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                            <div>{{ $code }}</div>
                        @endforeach
                    </div>
                @endif
            @endif

            <div class="mt-5">
                @if (! $this->enabled)
                    <x-confirms-password wire:then="enableTwoFactorAuthentication">
                        <x-button type="button" wire:loading.attr="disabled">
                            {{ __('Habilitar') }}
                        </x-button>
                    </x-confirms-password>
                @else
                    @if ($showingRecoveryCodes)
                        <x-confirms-password wire:then="regenerateRecoveryCodes">
                            <x-secondary-button class="me-3">
                                {{ __('Regenerar Códigos de Recuperación') }}
                            </x-secondary-button>
                        </x-confirms-password>
                    @else
                        <x-confirms-password wire:then="showRecoveryCodes">
                            <x-secondary-button class="me-3">
                                {{ __('Mostrar Códigos de Recuperación') }}
                            </x-secondary-button>
                        </x-confirms-password>
                    @endif

                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-danger-button wire:loading.attr="disabled">
                            {{ __('Deshabilitar') }}
                        </x-danger-button>
                    </x-confirms-password>
                @endif
            </div>
        </div>
    </x-slot>
</x-form-section>