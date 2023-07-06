<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Client') }}
            </h2>
            <a href="{{ route('client.index') }}" class="border border-emerald-400 px-3 py-1">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('client.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mt-6 flex">

                            <div class="flex-1 mr-4">
                                <label for="name" class="formLabel">Name</label>
                                <input type="text" name="name" id="name" class="formInput" value="{{ old('name') }}">

                                @error('name')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror

                            </div>
                            <div class="flex-1 ml-4">
                                <label for="username" class="formLabel">Username</label>
                                <input type="text" name="username" id="username" class="formInput"
                                    value="{{ old('username') }}">
                                @error('username')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>



                        <div class="mt-6 flex">
                            <div class="flex-1 mr-4">
                                <label for="email" class="formLabel">Email</label>
                                <input type="text" name="email" id="email" class="formInput" value="{{ old('email') }}">
                                @error('email')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex-1 ml-4">
                                <label for="phone" class="formLabel">Phone</label>
                                <input type="text" name="phone" id="phone" class="formInput" value="{{ old('phone') }}">
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
                                    <option value="{{ $country }}" {{ old('country') == $country ? 'selected' :'' }}>{{ $country }}</option>
                                    @endforeach
                                </select>


                                @error('country')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex-1 mx-5">
                                <label for="" class="formLabel">Thumbnail</label>
                                <label for="thumbnail"
                                    class="formLabel border-2 rounded-md border-dashed border-emerald-700 py-4 text-center">Click
                                    to upload image</label>
                                <input type="file" name="thumbnail" id="thumbnail" class="formInput hidden">

                                @error('thumbnail')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror

                            </div>
                            <div class="flex-1">
                                <label for="status" class="formLabel">Status</label>
                                <select name="status" id="status" class="formInput">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }} >Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>

                                @error('status')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror


                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="px-4 py-2 text-base uppercase bg-emerald-800 text-white rounded-md">Create</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
