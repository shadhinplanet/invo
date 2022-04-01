<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New User') }}
            </h2>
            <a href="{{ route('user.index') }}" class="border border-emerald-400 px-3 py-1">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf

                        <div class="mt-6 flex">

                            <div class="flex-1">
                                <label for="name" class="formLabel">Name</label>
                                <input type="text" name="name" id="name" class="formInput" value="{{ old('name') }}">

                                @error('name')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror

                            </div>
                            <div class="flex-1 ml-4">
                                <label for="email" class="formLabel">Email</label>
                                <input type="text" name="email" id="name" class="formInput" value="{{ old('email') }}">


                                @error('email')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror
                            </div>


                        </div>



                        <div class="mt-6 flex">
                            <div class="flex-1 mr-4">
                                <label for="company" class="formLabel">Company</label>
                                <input type="text" name="company" id="company" class="formInput" value="{{ old('company') }}">
                                @error('company')
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
                            <div class="flex-1 ml-4">
                                <label for="country" class="formLabel">Country</label>

                                <select name="country" id="country" class="formInput">
                                    <option value="none">Select Country</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country }}" {{ old('country') == $country ? 'selected': '' }}>{{ $country }}</option>

                                    @endforeach
                                </select>

                                @error('country')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-6 flex space-x-5">
                            <div class="flex-1">
                                <label for="password" class="formLabel">Password</label>
                                <input type="password" name="password" class="formInput" value="{{ old('password') }}">
                                @error('password')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex-1">
                                <label for="password_confirmation" class="formLabel">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="formInput" value="{{ old('password_confirmation') }}">
                                @error('password_confirmation')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex-1">
                                <label for="role" class="formLabel">Role</label>

                                <select name="role" id="role" class="formInput">
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>

                                @error('role')
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
