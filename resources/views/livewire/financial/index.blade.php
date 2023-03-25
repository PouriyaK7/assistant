<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex">
            <img src="{{ asset('assets/images/transaction-svgrepo-com.svg') }}" alt="" class="w-6 h-6 mr-3 stroke-gray-400 inline">
            {{ __('Financial') }}
            <x-button class="align-self-end ml-auto" onclick="document.getElementById('create-modal').click()">Create</x-button>
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 gap-6 lg:gap-8 p-6 lg:p-8 rounded">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 text-center">
                            <thead class="text-xs text-gray-900 uppercase bg-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Title</th>
                                <th scope="col" class="px-6 py-3">Amount</th>
                                <th scope="col" class="px-6 py-3">Card</th>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Update</th>
                                <th scope="col" class="px-6 py-3">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $item)
                                <tr wire:key="'transaction-' . $item->id" class="bg-white border-b">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                    >{{ $item->id }}</th>
                                    <td class="px-6 py-4">{{ $item->title }}</td>
                                    <td class="px-6 py-4">{{ $item->formatted_amount }}</td>
                                    <td class="px-6 py-4">{{ $item->bankCard?->title ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $item->created_at }}</td>
                                    <td class="px-6 py-4">
                                        <livewire:financial.update
                                            :wire:key="'transaction-' . $item->id"
                                            :transaction="$item"
                                        />
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-button
                                            class="bg-red-500 hover:bg-red-700 focus:bg-red-700"
                                            wire:click="delete({{ $item }})"
                                        >Delete</x-button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
