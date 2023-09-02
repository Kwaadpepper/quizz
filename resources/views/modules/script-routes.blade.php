<script @if (isset($nonce)) nonce="{{ $nonce }}" @endif>
    if (!window.__SYSTEM._routes) {
        window.__SYSTEM._routes = {
            logout: '{{ route('logout') }}',
            ajax: {
                users: {
                    getLogged: '{{ route('ajax.users.getLogged') }}'
                },
                teams: {
                    index: '{{ route('ajax.users.teams.index') }}'
                }
            }
        };
    }
</script>
