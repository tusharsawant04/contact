<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage contacts
    </h2>
</x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            @if (session()->has('message'))
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                  <div class="flex">
                    <div>
                      <p class="text-sm">{{ session('message') }}</p>
                    </div>
                  </div>
                </div>
            @endif
            <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create New contact</button>
            <button wire:click="import()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">import contact</button>
            <button wire:click="export()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">export contact</button>
            @if($isOpen)
                @include('livewire.create')
            @endif
            @if($isImport)
            @include('livewire.Import')
        @endif
        @if($isExport)
        @include('livewire.Export')
    @endif
    <div class="flex justify-end mb-4">
        <input wire:model="search" wire:change="triggerSearch" type="text" placeholder="Search..." class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:border-blue-500">
    </div>
    <div class="overflow-x-auto">
        <table class="table-auto min-w-full divide-y divide-gray-200">
            <!-- Table headers -->
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2" wire:click="sortBy('id')">
                        No. @if($sort === 'id') <span>@if($sortDirection === 'asc') &#9650; @else &#9660; @endif</span> @endif
                    </th>
                    <th class="px-4 py-2">Profile Photo</th>
                    <th class="px-4 py-2" wire:click="sortBy('name')">
                        Name @if($sort === 'name') <span>@if($sortDirection === 'asc') &#9650; @else &#9660; @endif</span> @endif
                    </th>
                    <th class="px-4 py-2" wire:click="sortBy('email')">
                        Email @if($sort === 'email') <span>@if($sortDirection === 'asc') &#9650; @else &#9660; @endif</span> @endif
                    </th>
                    <th class="px-4 py-2" wire:click="sortBy('mobile_no')">
                        Mobile No @if($sort === 'mobile_no') <span>@if($sortDirection === 'asc') &#9650; @else &#9660; @endif</span> @endif
                    </th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <!-- Table body -->
            <tbody>
                @foreach($contacts as $index => $contact)
                <tr>
                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border px-4 py-2"><img src="{{ $contact->profilePhoto }}" alt="Uploaded Image"></td>
                    <td class="border px-4 py-2">{{ $contact->name }}</td>
                    <td class="border px-4 py-2">{{ $contact->email }}</td>
                    <td class="border px-4 py-2">{{ $contact->mobile_no }}</td>
                    <td class="border px-4 py-2">
                        <button wire:click="edit({{ $contact->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</button>
                        <button wire:click="delete({{ $contact->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

            {{ $contacts->links() }}
        </div>
    </div>
</div>
