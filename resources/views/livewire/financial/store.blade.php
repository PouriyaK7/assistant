<div>
    <x-button wire:click="showModal()" id="create-modal" class="hidden" />
    <x-dialog-modal wire:model="modalStatus">
        <x-slot name="title">Add transaction</x-slot>
        <x-slot name="content">
            <x-slot name="logo">
                <x-authentication-card-logo/>
            </x-slot>

            <x-validation-errors class="mb-4"/>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit.prevent="store">
                @csrf

                <div class="mt-3">
                    <x-label for="title" value="{{ __('Title') }}"/>
                    <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')"
                             required autofocus autocomplete="title" wire:model="title" />
                </div>

                <div class="mt-3">
                    <x-label for="amount" value="{{ __('Amount') }}"/>
                    <x-input id="amount" class="block mt-1 w-full" type="text" name="amount" :value="old('amount')"
                             required autofocus autocomplete="amount" wire:model="amount" />
                </div>

                <div class="mt-3">
                    <x-label for="bank_card_id" value="{{ __('Bank Card') }}"/>
                    <select
                        name="bank_card_id"
                        id="bank_card_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        wire:model="bank_card_id"
                    >
                        <option selected>Choose bank card...</option>
                        @foreach($bankCards as $card)
                            <option value="{{ $card->id }}">{{ $card->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <x-button>Submit</x-button>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-dialog-modal>
</div>
