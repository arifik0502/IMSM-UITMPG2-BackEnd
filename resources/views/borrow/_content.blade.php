@if (session('success'))
    <div class="alert-success mb-6" role="status">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
    <div class="card lg:col-span-2 h-fit">
        <h2 class="section-title mb-4">Book Equipment</h2>

        <form method="POST" action="{{ route('borrow.store') }}" class="space-y-4">
            @csrf

            @guest
                <div class="field">
                    <x-input-label for="guest_name" value="Full Name" />
                    <x-text-input id="guest_name" type="text" name="guest_name" :value="old('guest_name')" required />
                    <x-input-error :messages="$errors->get('guest_name')" />
                </div>

                <div class="field">
                    <x-input-label for="guest_email" value="Email" />
                    <x-text-input id="guest_email" type="email" name="guest_email" :value="old('guest_email')" required />
                    <x-input-error :messages="$errors->get('guest_email')" />
                </div>
            @endguest

            <div class="field">
                <x-input-label for="equipment_id" value="Equipment" />
                <select id="equipment_id" name="equipment_id" required
                        class="form-field">
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
                <x-input-error :messages="$errors->get('equipment_id')" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="field">
                    <x-input-label for="borrow_date" value="Borrow Date" />
                    <x-text-input id="borrow_date" type="date" name="borrow_date" :value="old('borrow_date')" required />
                    <x-input-error :messages="$errors->get('borrow_date')" />
                </div>
                <div class="field">
                    <x-input-label for="return_date" value="Return Date" />
                    <x-text-input id="return_date" type="date" name="return_date" :value="old('return_date')" required />
                    <x-input-error :messages="$errors->get('return_date')" />
                </div>
            </div>

            <div class="field">
                <x-input-label for="reason" value="Reason (optional)" />
                <textarea id="reason" name="reason" rows="3"
                          class="form-field">{{ old('reason') }}</textarea>
                <x-input-error :messages="$errors->get('reason')" />
            </div>

            <x-primary-button class="w-full">Book Equipment</x-primary-button>
        </form>
    </div>

    <div class="card lg:col-span-3">
        <h2 class="section-title mb-4">Current Availability</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Category</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($equipmentList as $item)
                        <tr>
                            <td class="cell-strong">{{ $item['code'] }}</td>
                            <td>{{ $item['category'] }}</td>
                            <td>
                                @if ($item['available'])
                                    <span class="badge-success">Available</span>
                                @else
                                    <span class="badge-error">Booked until {{ $item['available_from'] }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
