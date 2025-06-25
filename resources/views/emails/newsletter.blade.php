@php
    App::setLocale($locale ?? config('app.locale'));
    $text = $newsletter->body[App::getLocale()] ?? reset($newsletter->body);
@endphp

{!! $newsletter->html !!}

{{--@component('mail::message')--}}
{{--    # {{ $newsletter->title }}--}}

{{--    {!! $newsletter->html !!}--}}

{{--    @component('mail::button', ['url' => config('app.url')])--}}
{{--        Перейти на сайт--}}
{{--    @endcomponent--}}

{{--    _Ви отримали цей лист, бо підписані на новини сайту {{ config('app.name') }}._--}}
{{--@endcomponent--}}
