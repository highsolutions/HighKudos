@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('notification.greetings.whoops')
@else
# @lang('notification.greetings.hello')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{!! $line !!}}

@endforeach

{{-- Action Button --}}
@isset($actionText)
@php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
@endphp
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{!! $actionText !!}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{!! $line !!}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{!! $salutation !!}
@else
@lang('notification.regards.regards'),<br>{{ config('notification.regards.team') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
{!! trans('notification.action text', compact('actionText', 'actionUrl')) !!}
@endcomponent
@endisset
@endcomponent
