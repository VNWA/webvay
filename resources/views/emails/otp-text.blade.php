{{ config('app.name') }} — {{ __('Verification') }}

{{ __('Your code:') }} {{ $plainCode }}

{{ __('Expires in :m minutes.', ['m' => 5]) }}
