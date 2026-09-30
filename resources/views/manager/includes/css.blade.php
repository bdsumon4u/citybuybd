
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
    .bg-danger-light {
        background-color: #ff000e38 !important;
    }
    .dark-theme .bg-danger-light,
    .dark-theme td.bg-danger-light,
    .dark-theme th.bg-danger-light,
    .dark-theme .table tbody tr td.bg-danger-light,
    .dark-theme .table tbody tr th.bg-danger-light,
    .dark-theme .table-bordered tbody tr td.bg-danger-light,
    .dark-theme .table-striped tbody tr:nth-of-type(odd) td.bg-danger-light,
    .dark-theme .table-striped tbody tr:nth-of-type(even) td.bg-danger-light,
    .dark-theme .table-hover tbody tr:hover td.bg-danger-light {
        background-color: rgba(220, 38, 38, 0.45) !important;
        border-left: 4px solid #ef4444 !important;
    }

    /* Date input default calendar icon white in dark mode */
    .dark-theme input[type="date"],
    .dark-theme input[type="datetime-local"],
    .dark-theme input[type="month"],
    .dark-theme input[type="time"],
    html.dark-theme input[type="date"],
    html.dark-theme input[type="datetime-local"],
    html.dark-theme input[type="month"],
    html.dark-theme input[type="time"],
    body.dark-theme input[type="date"],
    body.dark-theme input[type="datetime-local"],
    body.dark-theme input[type="month"],
    body.dark-theme input[type="time"] {
        color-scheme: dark !important;
    }
    .dark-theme input[type="date"]::-webkit-calendar-picker-indicator,
    .dark-theme input[type="datetime-local"]::-webkit-calendar-picker-indicator,
    .dark-theme input[type="month"]::-webkit-calendar-picker-indicator,
    .dark-theme input[type="time"]::-webkit-calendar-picker-indicator,
    html.dark-theme input[type="date"]::-webkit-calendar-picker-indicator,
    html.dark-theme input[type="datetime-local"]::-webkit-calendar-picker-indicator,
    html.dark-theme input[type="month"]::-webkit-calendar-picker-indicator,
    html.dark-theme input[type="time"]::-webkit-calendar-picker-indicator,
    body.dark-theme input[type="date"]::-webkit-calendar-picker-indicator,
    body.dark-theme input[type="datetime-local"]::-webkit-calendar-picker-indicator,
    body.dark-theme input[type="month"]::-webkit-calendar-picker-indicator,
    body.dark-theme input[type="time"]::-webkit-calendar-picker-indicator,
    .dark-theme input::-webkit-calendar-picker-indicator,
    html.dark-theme input::-webkit-calendar-picker-indicator,
    body.dark-theme input::-webkit-calendar-picker-indicator {
        filter: brightness(0) invert(1) !important;
        -webkit-filter: brightness(0) invert(1) !important;
        cursor: pointer !important;
        opacity: 1 !important;
    }

    /* QC Button Modal white icon in dark mode */
    .dark-theme .qc_btn_modal,
    .dark-theme .qc_btn_modal.btn-icon,
    .dark-theme .qc_btn_modal.btn-icon i,
    .dark-theme a.qc_btn_modal,
    .dark-theme a.qc_btn_modal.btn-icon,
    .dark-theme a.qc_btn_modal.btn-icon i,
    .dark-theme .btn.qc_btn_modal,
    .dark-theme .btn.qc_btn_modal.btn-icon,
    .dark-theme .btn.qc_btn_modal.btn-icon i {
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
        background-color: rgba(255, 255, 255, 0.08) !important;
    }
    .dark-theme .qc_btn_modal:hover,
    .dark-theme .qc_btn_modal.btn-icon:hover,
    .dark-theme a.qc_btn_modal:hover,
    .dark-theme a.qc_btn_modal.btn-icon:hover,
    .dark-theme .btn.qc_btn_modal:hover,
    .dark-theme .btn.qc_btn_modal.btn-icon:hover {
        background-color: rgba(56, 189, 248, 0.25) !important;
        border-color: #38bdf8 !important;
        color: #38bdf8 !important;
    }
    .dark-theme .qc_btn_modal:hover i,
    .dark-theme .qc_btn_modal.btn-icon:hover i,
    .dark-theme a.qc_btn_modal:hover i,
    .dark-theme a.qc_btn_modal.btn-icon:hover i,
    .dark-theme .btn.qc_btn_modal:hover i,
    .dark-theme .btn.qc_btn_modal.btn-icon:hover i {
        color: #38bdf8 !important;
    }
</style>
