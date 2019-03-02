@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{-- TODO: change path to logo --}}
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" style="height: 90px" />
        @endcomponent
    @endslot

    {{-- Body --}}
    {!! $slot !!}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {!! $subcopy !!}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {!! date('Y') !!} {!! config('app.name') !!}. @lang('regards.copyrights')
        @endcomponent
    @endslot
@endcomponent
