@if (session('success'))
    <div class="mb-6 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
    <div class="card lg:col-span-2 h-fit">
        <h2 class="font-semibold text-gray-800 mb-4">Book Equipment</h2>

        <form method="POST" action="{{ route('borrow.store') }}" class="space-y-4">
            @csrf

            @guest
                <div>
                    <x-input-label for="guest_name" value="Full Name" />
                    <x-text-input id="guest_name" type="text" name="guest_name" :value="old('guest_name')" required />
                    <x-input-error :messages="$errors->get('guest_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="guest_email" value="Email" />
                    <x-text-input id="guest_email" type="email" name="guest_email" :value="old('guest_email')" required />
                    <x-input-error :messages="$errors->get('guest_email')" class="mt-2" />
                </div>
            @endguest

            <div>
                <x-input-label for="equipment_id" value="Equipment" />
                <select id="equipment_id" name="equipment_id" required
                        class="block w-full form-field">
                    <option value="">Select equipment&hellip;</option>
                    @foreach ($equipmentList->groupBy('category') as $category => $items)
                        <optgroup label="{{ $category }}">
                            @foreach ($items as $item)
                                <option value="{{ $item['id'] }}" @selected((int) old('equipment_id') === $item['id'])>
                                    {{ $item['code'] }} {{ $item['available'] ? '' : '(booked until '.$item['available_from'].')' }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('equipment_id')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="borrow_date" value="Borrow Date" />
                    <x-text-input id="borrow_date" type="date" name="borrow_date" :value="old('borrow_date')" required />
                    <x-input-error :messages="$errors->get('borrow_date')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="return_date" value="Return Date" />
                    <x-text-input id="return_date" type="date" name="return_date" :value="old('return_date')" required />
                    <x-input-error :messages="$errors->get('return_date')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="reason" value="Reason (optional)" />
                <textarea id="reason" name="reason" rows="3"
                          class="block w-full form-field">{{ old('reason') }}</textarea>
                <x-input-error :messages="$errors->get('reason')" class="mt-2" />
            </div>

            <x-primary-button class="w-full">Book Equipment</x-primary-button>
        </form>
    </div>

    <div class="card lg:col-span-3">
        <h2 class="font-semibold text-gray-800 mb-4">Current Availability</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Code</th>
                        <th class="py-2 pr-4">Category</th>
                        <th class="py-2 pr-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($equipmentList as $item)
                        <tr class="border-b border-gray-100 last:border-0">
                            <td class="py-2 pr-4 font-medium">{{ $item['code'] }}</td>
                            <td class="py-2 pr-4">{{ $item['category'] }}</td>
                            <td class="py-2 pr-4">
                                @if ($item['available'])
                                    <span class="badge-green">Available</span>
                                @else
                                    <span class="badge-red">Booked until {{ $item['available_from'] }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
