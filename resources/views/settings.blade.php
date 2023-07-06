<x-app-layout>
    <x-slot name="header">

    </x-slot>

    @php
    function getImageUrl($image){
    if( str_starts_with($image, 'http') ){
    return $image;
    }
    return asset('storage/uploads') . '/' . $image;
    }
    @endphp

    <div class="py-10">
        <div class="max-w-5xl mx-auto">
            <div class="">
                <div class="py-36 relative bg-black bg-opacity-50 bg-blend-overlay bg-cover bg-no-repeat bg-center mb-10"
                    style="background-image: url('https://picsum.photos/1024/400')">

                    <div class="absolute bottom-5 left-5 flex space-x-5">
                        <div class=" w-28 h-28 bg-center bg-cover rounded-full "
                            style="background-image: url({{ getImageUrl(Auth::user()->thumbnail) }})"></div>
                        <div class="">
                            <h1 class="text-white mt-5 text-lg">{{ Auth::user()->name }}</h1>
                            <h2 class="text-white">{{ Auth::user()->email }}</h2>
                        </div>
                    </div>
                </div>


                <div class="bg-white p-8 mt-6">
                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <h2 class="font-bold">Personal Information</h2>
                        <div class="flex justify-between">
                            <div class="flex-1">
                                <div class="mt-6 flex justify-between items-center">
                                    <label for="name" class="w-1/4">Name</label>
                                    <span class="w-10 text-center">:</span>
                                    <input type="text" class="flex-1" name="name" id="name"
                                        value="{{ Auth::user()->name }}">
                                </div>
                                <div class="mt-6 flex justify-between items-center">
                                    <label for="email" class="w-1/4">Email</label>
                                    <span class="w-10 text-center">:</span>
                                    <input type="text" class="flex-1" name="email" id="email"
                                        value="{{ Auth::user()->email }}">
                                </div>
                                <div class="mt-6 flex justify-between items-center">
                                    <label for="company" class="w-1/4">Company</label>
                                    <span class="w-10 text-center">:</span>
                                    <input type="text" class="flex-1" name="company" id="company"
                                        value="{{ Auth::user()->company }}">
                                </div>
                                <div class="mt-6 flex justify-between items-center">
                                    <label for="phone" class="w-1/4">Phone</label>
                                    <span class="w-10 text-center">:</span>
                                    <input type="text" class="flex-1" name="phone" id="phone"
                                        value="{{ Auth::user()->phone }}">
                                </div>
                                <div class="mt-6 flex justify-between items-center">
                                    <label for="country" class="w-1/4">Country</label>
                                    <span class="w-10 text-center">:</span>
                                    <select name="country" id="country" class="flex-1">

                                        @foreach ($countries as $country)
                                        <option value="{{ $country }}" {{ $country == Auth::user()->country ? 'selected' : '' }}>{{ $country }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>
                            <div class="flex-1 ml-8 flex flex-col justify-between">
                                <div class="flex mt-6 justify-between">
                                    <div class="border p-5 flex flex-col">
                                        <label for="thumbnail" class="w-full mb-2 cursor-pointer">Thumbnail</label>

                                        <input type="file" class="flex-1" name="thumbnail" id="thumbnail">
                                    </div>
                                    <div class="border p-2"><img src="{{ getImageUrl( Auth::user()->thumbnail ) }}" class="w-20"
                                            alt="">
                                    </div>
                                </div>
                                <div class="flex justify-between">
                                    <div class="border p-5 flex flex-col">
                                        <label for="logo" class="w-full mb-2 cursor-pointer">Invoice Logo</label>

                                        <input type="file" class="flex-1" name="invoice_logo" id="logo">
                                    </div>
                                    <div class="border p-2">
                                        @if (Auth::user()->invoice_logo !=null)
                                        <img src="{{ asset('storage/uploads/'.Auth::user()->invoice_logo) }}" class="w-20" alt="">
                                        @else
                                        <img src="{{ asset('img/invo-mate.png') }}" class="w-20" alt="">
                                        @endif
                                    </div>
                                </div>
                                <div class="">
                                    <button type="submit"
                                        class="w-full p-2 bg-teal-600 text-white font-bold hover:bg-orange-400 duration-300 transition-all">UPDATE</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
