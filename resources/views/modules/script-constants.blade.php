@php
    $config = ['app.name', 'app.locale', 'app.timezone'];
    $config = Arr::undot(Arr::mapWithKeys($config, fn($prop) => [$prop => config($prop)]));
@endphp

<script @if (isset($nonce)) nonce="{{ $nonce }}" @endif>
    if (!window.__SYSTEM) {
        window.__SYSTEM = {};
    }
    window.__SYSTEM._config = @json($config);
    window.__SYSTEM._locale = '{{ app()->getLocale() }}';
    window.__SYSTEM._translations = @json(cache(sprintf('translations.%s', app()->getLocale())) ?? []);
    window.__SYSTEM._asset = '{{ asset('') }}';
</script>
