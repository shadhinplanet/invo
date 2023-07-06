<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Client') }}
            </h2>
            <a href="{{ route('client.index') }}" class="border border-emerald-400 px-3 py-1">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('client.update', $client->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mt-6 flex">

                            <div class="flex-1 mr-4">
                                <label for="name" class="formLabel">Name</label>
                                <input type="text" name="name" id="name" class="formInput" value="{{ $client->name }}">

                                @error('name')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror

                            </div>
                            <div class="flex-1 ml-4">
                                <label for="username" class="formLabel">Username</label>
                                <input type="text" name="username" id="username" class="formInput"
                                    value="{{ $client->username }}">
                                @error('username')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>



                        <div class="mt-6 flex">
                            <div class="flex-1 mr-4">
                                <label for="email" class="formLabel">Email</label>
                                <input type="text" name="email" id="email" class="formInput" value="{{ $client->email }}">
                                @error('email')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex-1 ml-4">
                                <label for="phone" class="formLabel">Phone</label>
                                <input type="text" name="phone" id="phone" class="formInput" value="{{ $client->phone }}">
                                @error('phone')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <div class="mt-6 flex justify-between">
                            <div class="flex-1">
                                <label for="country" class="formLabel">Country</label>

                                <select name="country" id="country" class="formInput">
                                    <option value="none">Select country</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country }}" {{ $client->country == $country ? 'selected' :'' }}>{{ $country }}</option>
                                    @endforeach
                                </select>

                                @error('country')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex-1 mx-5">
                                <label for="status" class="formLabel">Status</label>
                                <select name="status" id="status" class="formInput">
                                    <option value="active" {{ $client->status == 'active' ? 'selected' : '' }} >Active</option>
                                    <option value="inactive" {{ $client->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>

                                @error('status')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror


                            </div>

                            @php
                            function getImageUrl($image){
                                if( str_starts_with($image, 'http') ){
                                    return $image;
                                }
                                return asset('storage/uploads') . '/' . $image;
                            }
                        @endphp

                            <div class="flex-1 relative">
                                <label for="" class="formLabel">Thumbnail</label>
                                <label for="thumbnail"
                                    class="formLabel border-2 rounded-md border-dashed border-emerald-700 py-4 text-center">Click
                                    to upload image</label>
                                <input type="file" name="thumbnail" id="thumbnail" class="formInput hidden">

                                @error('thumbnail')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror

                                <div class="w-full text-center absolute  "><img src="{{ getImageUrl($client->thumbnail) }}" alt="" width="80" class="mx-auto bg-white  p-5"></div>

                            </div>


                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="px-4 py-2 text-base uppercase bg-blue-600 text-white rounded-md">Update</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
