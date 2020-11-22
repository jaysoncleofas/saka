@if (session('status'))
    <script>
    iziToast.info({
        title: 'Success',
        message: '{{ session('status') }}',
        position: 'topRight'
    });
    </script>
@endif

@if (session('notification'))
    <script>
    iziToast.{{ session('type') }}({
        title: '',
        message: '{{ session('notification') }}',
        position: 'topRight'
    });
    </script>
@endif
