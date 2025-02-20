<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> مشروع الملكية</title>
    <link href="{{ asset('web/image/favicon.png') }}" rel="icon" />


    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('web/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web/css/bootstrap.min.css') }}">

    <!-- datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.bootstrap5.min.css">


    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&family=Readex+Pro:wght@160..700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('web/css/style.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('web/toastr/toastr.min.css') }}" />

    <style>
        .dt-empty {
            text-align: center !important;
            font-weight: bold;
            font-size: 16px;
            color: #666;
        }

        /* For Chrome, Safari, and Edge */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* For Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

    </style>



</head>
