@if (session('success'))
    <script>
        $.notify({
            // options
            message: '{{ session('success') }}'
        },{
            // settings
            type: 'success'
        });
    </script>
@endif