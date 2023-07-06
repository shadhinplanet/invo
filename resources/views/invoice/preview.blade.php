<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice Preview') }}
        </h2>
    </x-slot>

    <div class="py-20 bg-white  border-t">
       <div class="container mx-auto ">
            @include('invoice.pdf')
       </div>
    </div>
</x-app-layout>
