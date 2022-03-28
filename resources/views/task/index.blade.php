<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tasks') }}
            </h2>
            <a href="{{ route('task.create') }}" class="border border-emerald-400 px-3 py-1">Add New</a>
        </div>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 bg-white py-10 rounded-md {{ request('client_id') || request('status') || request('fromDate') || request('endDate') || request('price')? '': 'hidden' }}"
                id="task_filter">
                <h2 class="text-center font-bold mb-6">Filter Tasks</h2>
                <form action="{{ route('task.index') }}" method="GET">
                    <div class="flex space-x-3 items-end justify-center">
                        <div class="">
                            @error('client_id')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                            @enderror
                            <label for="client_id" class="formLabel">Client</label>
                            <select name="client_id" id="client_id" class="formInput">
                                <option value="">Select Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ $client->id == old('client_id') || $client->id == request('client_id') ? 'selected' : '' }}>
                                        {{ $client->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="">
                            @error('status')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                            @enderror
                            <label for="status" class="formLabel">Status</label>
                            <select name="status" id="status" class="formInput">
                                <option value="">Select Status</option>
                                <option value="pending"
                                    {{ old('status') == 'pending' || request('status') == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="complete"
                                    {{ old('status') == 'complete' || request('status') == 'complete' ? 'selected' : '' }}>
                                    Complete</option>
                            </select>

                        </div>
                        <div class="">
                            @error('fromDate')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                            @enderror
                            <label for="fromDate" class="formLabel">Start Date</label>
                            <input type="date" class="formInput" name="fromDate" id="fromDate"
                                max="{{ now()->format('Y-m-d') }}" value="{{ request('fromDate') }}">

                        </div>
                        <div class="">
                            @error('endDate')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                            @enderror
                            <label for="endDate" class="formLabel">End Date</label>
                            <input type="date" class="formInput" name="endDate" id="endDate"
                                value="{{ request('endDate') != '' ? request('endDate') : '' }}"
                                max="{{ now()->format('Y-m-d') }}">

                        </div>
                        <div class="">
                            @error('price')
                                <p class="text-red-700 text-sm">{{ $message }}</p>
                            @enderror
                            <label for="price" class="formLabel">Max Price</label>
                            <input type="number" class="formInput" name="price" id="price"
                                value="{{ request('price') }}">
                        </div>
                        <div class="">
                            <button type="submit" class="bg-purple-600 text-white px-3 py-2">Filter</button>
                        </div>
                    </div>

                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="text-right">
                        <button id="task_filter_btn" type="button"
                            class="px-3 py-1 bg-blue-400 text-white mb-6">{{ request('client_id') || request('status') || request('fromDate') || request('endDate') || request('price')
                                ? 'Close Filter'
                                : 'Filter' }}</button>
                    </div>

                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border py-2">Name</th>
                                <th class="border py-2 w-20">Price</th>
                                <th class="border py-2 w-32">Status</th>
                                <th class="border py-2 w-32">Priority</th>
                                <th class="border py-2">Client</th>
                                <th class="border py-2 min-w-max px-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($tasks as $task)
                                <tr>
                                    <td class="border py-2 text-left px-2 relative">
                                        <a class=" font-semibold hover:text-purple-700"
                                            href="{{ route('task.show', $task->slug) }}">{{ $task->name }}</a>


                                        @php
                                            $startdate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', Carbon\Carbon::now())->setTimezone('Asia/Dhaka');
                                            $enddate = $task->end_date;

                                            // Time Left Calculation
                                            if ($enddate > $startdate) {
                                                $days = $startdate->diffInDays($enddate);
                                                $hours = $startdate
                                                    ->copy()
                                                    ->addDays($days)
                                                    ->diffInHours($enddate);
                                                $minutes = $startdate
                                                    ->copy()
                                                    ->addDays($days)
                                                    ->addHours($hours)
                                                    ->diffInMinutes($enddate);
                                            } else {
                                                $days = 0;
                                                $hours = 0;
                                                $minutes = 0;
                                            }

                                            // Bar Color And Percent
                                            if ($enddate > $startdate && $task->status == 'pending') {
                                                if ($days == 1) {
                                                    $percent = 95;
                                                    $color = 'bg-red-700 ';
                                                } elseif ($days < 3) {
                                                    $percent = 75;
                                                    $color = 'bg-red-400 ';
                                                } elseif ($days < 5) {
                                                    $percent = 50;
                                                    $color = 'bg-red-300 ';
                                                } else {
                                                    $percent = 100;
                                                    $color = 'bg-green-500';
                                                }
                                            } else {
                                                $percent = 100;
                                                $color = 'bg-red-500';
                                            }

                                        @endphp


                                        <div class="counter-class border-t py-1 mt-2 flex justify-end space-x-2 task-{{ $task->id }}"
                                            data-date="{{ $task->end_date }}">
                                            @if ($enddate > $startdate && $task->status == 'pending')
                                                <p>{{ $days != 0 ? $days . ' Days,' : '' }}
                                                    {{ $days != 0 && $hours != 0 ? $hours . ' Hours' : '' }}
                                                    {{ $minutes . ' Minutes' }}</p>
                                            @else
                                                <div class="text-sm">
                                                    {{ $task->status == 'pending' ? 'Time Over Due!' : '' }}</div>
                                            @endif

                                        </div>


                                        @if ($task->status == 'complete')
                                            <div class="absolute h-1 w-full z-10 bg-green-600 left-0 bottom-0"></div>
                                        @else
                                            <div class="absolute h-1 z-10 left-0 bottom-0 {{ $color }}"
                                                style="width:{{ $percent }}%;"></div>
                                        @endif

                                        <div class="absolute h-1 w-full bg-slate-400 left-0 bottom-0"></div>

                                    </td>
                                    <td class="border py-2 text-center text-sm">{{ $task->price }}</td>
                                    <td class="border py-2 text-center capitalize text-sm">
                                        {{ $task->status }}
                                        @if ($task->status == 'pending')
                                            <form action="{{ route('markAsComplete', $task) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="border-2 bg-teal-500 w-full text-white hover:bg-transparent hover:text-black transition-all duration-300 px-3 py-1 mr-2">Done</button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="border py-2 text-center px-3 capitalize">
                                        {{ $task->priority }}
                                    </td>
                                    <td class="border py-2 text-left px-3 text-sm">
                                        <a class="text-black font-bold hover:text-purple-700"
                                            href="{{ route('task.index') }}?client_id={{ $task->client->id }}">{{ $task->client->name }}</a>
                                    </td>
                                    <td class="border py-2 text-center px-3">

                                        <div class="flex justify-center">

                                            <a href="{{ route('task.edit', $task) }}"
                                                class="border-2 bg-purple-500 text-white hover:bg-transparent hover:text-black transition-all duration-300 px-3 py-1 mr-2">Edit</a>

                                            <form action="{{ route('task.destroy', $task) }}" method="POST"
                                                onsubmit="return confirm('Do you really want to delete?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="border-2 bg-red-500 text-white hover:bg-transparent hover:text-black transition-all duration-300 px-3 py-1 mr-2">Delete</button>
                                            </form>
                                        </div>





                                    </td>
                                </tr>


                            @empty

                                <tr>
                                    <td class="border py-2 text-center" colspan="5">No Task Found!</td>
                                </tr>
                            @endforelse


                        </tbody>
                    </table>

                    <div class="mt-5">
                        {{ $tasks->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
