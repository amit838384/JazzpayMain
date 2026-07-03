<head>
    <meta charset="utf-8" />
    <title>Dashboard | Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Admin & Dashboard Jazzpay" name="description" />
    <meta content="Themesbrand" name="author" />    <!-- CSRF Token -->
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	
    <link rel="shortcut icon" href="{{ URL::asset('build/assets/cajunction_logo.jpeg') }}">
    <link href="{{ URL::asset('build/assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ URL::asset('build/assets/js/layout.js') }}"></script>
    <link href="{{ URL::asset('build/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

</head>

