<x-action-section>
    <x-slot name="title">
        {{ __('Hapus Akun') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Hapus Akun Anda Secara Permanen.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('Setelah Akun dihapus maka data yang berkaitan akan dihapus secara permanen. Download atau simpan data anda yang berharga') }}
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                {{ __('Hapus Akun') }}
            </x-danger-button>
        </div>

        <!-- Delete User Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingUserDeletion">
            <x-slot name="title">
                {{ __('Hapus Akun') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Apakah Anda yakin ingin menghapus akun ini? Setelah akun dihapus maka data yang berkaitan akan dihapus juga secara permanen. Masukkan password untuk mengkonfirmasi apakah anda yakin untuk menghapus akun.') }}

                <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-input type="password" class="mt-1 block w-3/4"
                                autocomplete="current-password"
                                placeholder="{{ __('Password') }}"
                                x-ref="password"
                                wire:model="password"
                                wire:keydown.enter="deleteUser" />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                    {{ __('Hapus Akun') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
