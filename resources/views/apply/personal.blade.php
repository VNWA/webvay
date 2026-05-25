<x-layouts.app title="{{ __('Personal details') }}">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
        @include('apply.partials.stepper', ['step' => 1])
        <div class="apply-card">
            <h1 class="text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">{{ __('Personal information') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('Tell us about yourself. All fields are required.') }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ __('apply_phone_hint') }}</p>

            <form method="post" action="{{ route('apply.ref.personal.save', ['reference' => $application->reference]) }}" class="mt-8 grid gap-5 sm:grid-cols-2">
                @csrf
                <div class="sm:col-span-2">
                    <label class="apply-label" for="full_name">{{ __('Full name') }}</label>
                    <input id="full_name" name="full_name" value="{{ old('full_name', $profile->full_name) }}" required class="apply-input">
                </div>
                <div>
                    <label class="apply-label" for="birthday">{{ __('Birthday') }}</label>
                    <input id="birthday" type="date" name="birthday" value="{{ old('birthday', optional($profile->birthday)->format('Y-m-d')) }}" required class="apply-input">
                </div>
                <div>
                    <label class="apply-label" for="gender">{{ __('Gender') }}</label>
                    <select id="gender" name="gender" required class="apply-input">
                        <option value="" disabled @selected(! old('gender', $profile->gender))>{{ __('Select') }}</option>
                        @foreach (['male' => __('Male'), 'female' => __('Female'), 'other' => __('Other')] as $value => $label)
                            <option value="{{ $value }}" @selected(old('gender', $profile->gender) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="apply-label" for="phone">{{ __('Phone number') }}</label>
                    <input id="phone" name="phone" type="tel" inputmode="tel" autocomplete="tel"
                        placeholder="0912345678 hoặc +84912345678"
                        value="{{ old('phone', $profile->phone ?? $application->user?->phone) }}" required class="apply-input">
                </div>
                <div class="sm:col-span-2">
                    <label class="apply-label" for="cccd_number">{{ __('CCCD number') }}</label>
                    <input id="cccd_number" name="cccd_number" value="{{ old('cccd_number', $profile->cccd_number) }}" required class="apply-input">
                </div>
                <div class="sm:col-span-2">
                    <label class="apply-label" for="address">{{ __('Address') }}</label>
                    <textarea id="address" name="address" rows="3" required class="apply-textarea">{{ old('address', $profile->address) }}</textarea>
                </div>
                <div>
                    <label class="apply-label" for="province">{{ __('Province') }}</label>
                    <input id="province" name="province" value="{{ old('province', $profile->province) }}" required class="apply-input">
                </div>
                <div>
                    <label class="apply-label" for="district">{{ __('District') }}</label>
                    <input id="district" name="district" value="{{ old('district', $profile->district) }}" required class="apply-input">
                </div>
                <div class="sm:col-span-2">
                    <label class="apply-label" for="ward">{{ __('Ward') }}</label>
                    <input id="ward" name="ward" value="{{ old('ward', $profile->ward) }}" required class="apply-input">
                </div>
                <div class="sm:col-span-2 flex justify-end border-t border-slate-100 pt-6">
                    <button type="submit" class="apply-btn-primary">{{ __('Continue') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
