<script @if (isset($nonce)) nonce="{{ $nonce }}" @endif>
    if (!window.__SYSTEM._routes) {
        window.__SYSTEM._routes = {
            "logout": '{{ route('logout') }}'
        };
    }
</script>
