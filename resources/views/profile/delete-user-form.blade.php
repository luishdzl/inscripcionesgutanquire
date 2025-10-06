<x-form-section submit="deleteUser">
    <x-slot name="title">
        {{ __('Eliminar Cuenta') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Elimine permanentemente su cuenta.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <div class="max-w-xl text-sm text-gray-600">
                {{ __('Una vez que se elimine su cuenta, todos sus recursos y datos se borrarán de forma permanente. Antes de eliminar su cuenta, descargue cualquier dato o información que desee conservar.') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-danger-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
            {{ __('Eliminar Cuenta') }}
        </x-danger-button>

        <x-action-message class="me-3" on="deleted">
            {{ __('Su cuenta ha sido eliminada.') }}
        </x-action-message>
    </x-slot>
</x-form-section>