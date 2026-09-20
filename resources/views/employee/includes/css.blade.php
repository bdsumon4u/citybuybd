
<!-- vendor css -->
<link href="{{ asset('backend/lib/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
<link href="{{ asset('backend/lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
<link href="{{ asset('backend/lib/rickshaw/rickshaw.min.css')}}" rel="stylesheet">
<link href="{{ asset('backend/lib/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{ asset('backend/css/bootstrap-tagsinput.css')}}" rel="stylesheet">


<!-- Bracket CSS -->
<link rel="stylesheet" href="{{ asset('backend/css/bracket.css')}}">
<link id="themeSkin" rel="stylesheet" href="" data-dark-href="{{ asset('backend/css/bracket.dark.css')}}">
<link rel="stylesheet" href="{{ asset('backend/css/custom.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script>
    (function() {
        var theme = localStorage.getItem('admin_theme') || 'light';
        if (theme === 'dark') {
            document.documentElement.classList.add('dark-theme');
            var skin = document.getElementById('themeSkin');
            if (skin) skin.href = "{{ asset('backend/css/bracket.dark.css') }}";
        }
    })();
</script>
<style type="text/css">
    .bg-danger-light{
        background-color: #ff000e38 !important;

    }
</style>
