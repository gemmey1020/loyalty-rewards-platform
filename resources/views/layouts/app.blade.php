<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <title>@yield('title', 'Loyalty')</title>
    <title>@yield('title', 'Loyalty')</title>
    <link rel="icon" href="{{ asset('template/images/Posh_Center-1-removebg-preview.png') }}" type="image/gif"
        sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Rentaly - Multipurpose Vehicle Car Rental Website Template" name="description">
    <meta content="" name="keywords">
    <meta content="" name="author">
    <!-- CSS Files -->
    <link href="{{ asset('template/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bootstrap">
    <link href="{{ asset('template/css/mdb.min.css') }}" rel="stylesheet" type="text/css" id="mdb">
    <link href="{{ asset('template/css/plugins.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/css/coloring.css') }}" rel="stylesheet" type="text/css">
    <link id="colors" href="{{ asset('template/css/colors/scheme-01.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/css/custom.css') }}" rel="stylesheet" type="text/css">
    <!-- DataTables 2 CSS -->
    <link href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" />

    @stack('css')

    <style>
        table.dataTable th,
        table.dataTable td {
            text-align: center !important;
            vertical-align: middle !important;
        }

        .language-switcher a {
            color: #ffffff;
            text-decoration: none;
            padding: 2px 5px;
            transition: all 0.3s ease;
        }

        .language-switcher .active-lang {
            color: #0e69b2;
            font-weight: bold;
        }

        /* Style the DataTables buttons to match Bootstrap/MDB */
        .dt-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .dt-button {
            background-color: #0c7a7a !important;
            color: white !important;
            border: none !important;
            padding: 8px 14px !important;
            border-radius: 6px !important;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .dt-button:hover {
            background-color: #085d5d !important;
            color: white !important;
        }
    </style>
</head>

<body onload="initialize()">
    <div id="wrapper">
        <!-- page preloader begin -->
        <div id="de-preloader"></div>
        <!-- page preloader close -->

        <!-- header begin -->
        @include('partials.header')
        <!-- header close -->

        <!-- content begin -->
        <div class="no-bottom no-top" id="content">
            @yield('content')
        </div>
        <!-- content close -->

        <a href="#" id="back-to-top"></a>

        <!-- footer begin -->
        @include('partials.footer')
        <!-- footer close -->
    </div>

    @stack('scripts')

    <!-- Javascript Files -->
    <script src="{{ asset('template/js/plugins.js') }}"></script>
    <script src="{{ asset('template/js/designesia.js') }}"></script>

    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#invoicesTable').DataTable({
                dom: 'Bfrtip', // Add buttons to the DOM
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                scrollX: true, // Enable horizontal scrolling
                scrollCollapse: true, // Enable scroll collapse
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Search invoices...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ invoices",
                    paginate: {
                        next: '›',
                        previous: '‹'
                    }
                },
                order: [
                    [4, "desc"]
                ], // Order by date (column 4) by default
                responsive: false, // Disable default responsive behavior in favor of scrollX
                autoWidth: false // Disable auto width to honor column min-width
            });

            // Handle window resize to adjust table layout
            $(window).on('resize', function() {
                $('#invoicesTable').DataTable().columns.adjust();
            });

            // Same configuration for other tables
            $('#recentInvoicesTable').DataTable({
                dom: 'Bfrtip', // Add buttons to the DOM
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                language: {

                    search: "",
                    searchPlaceholder: "🔍 Search invoices...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ invoices",
                    paginate: {
                        next: '›',
                        previous: '‹'
                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
                responsive: false,
                autoWidth: false
            });
            $('#usersTable').DataTable({
                dom: 'Bfrtip', // Add buttons to the DOM
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                language: {
                    search: "",
                    searchPlaceholder: "🔍 Search invoices...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ users",
                    paginate: {
                        next: '›',
                        previous: '‹'
                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
                responsive: false,
                autoWidth: false
            });
        });
    </script>

    <!-- DataTables Export Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
</body>

</html>
