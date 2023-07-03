<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FC MARINA | ADMIN</title>


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css')}}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <!-- endinject -->


    <link rel="stylesheet" href="{{asset('assets/vendors/dropify/dist/dropify.min.css')}}">
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo3/style.css')}}">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png')}}" />
</head>

<body>



    <div class="main-wrapper">

        <!-- partial:../../partials/_navbar.html -->
        <div class="horizontal-menu">
            <nav class="navbar top-navbar">
                <div class="container">
                    <div class="navbar-content">
                        <a href="#" class="navbar-brand">
                            FC Marina
                        </a>
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img class="wd-30 ht-30 rounded-circle" src="https://via.placeholder.com/30x30"
                                        alt="profile">
                                </a>
                                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                                    <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                                        <div class="mb-3">
                                            <img class="wd-80 ht-80 rounded-circle"
                                                src="https://via.placeholder.com/80x80" alt="">
                                        </div>
                                        <div class="text-center">
                                            <p class="tx-16 fw-bolder">{{auth()->user()->name}}</p>
                                            <!-- <p class="tx-12 text-muted">amiahburton@gmail.com</p> -->
                                        </div>
                                    </div>
                                    <ul class="list-unstyled p-1">
                                        <li class="dropdown-item py-2">
                                            <a href="{{url('logout')}}" class="text-body ms-0">
                                                <i class="me-2 icon-md" data-feather="log-out"></i>
                                                <span>Log Out</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                            data-toggle="horizontal-menu-toggle">
                            <i data-feather="menu"></i>
                        </button>
                    </div>
                </div>
            </nav>
            <nav class="bottom-navbar">
                <div class="container">
                    <ul class="nav page-navigation">
                        <li class="nav-item">
                            <a class="nav-link" href="../../dashboard.html">
                                <i class="link-icon" data-feather="box"></i>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </li>



                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/admin/stadium-bookings')}}">
                                <i class="link-icon" data-feather="box"></i>
                                <span class="menu-title">Bookings</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="link-icon" data-feather=""></i>
                                <span class="menu-title">Stadiums</span>
                                <i class="link-arrow"></i>
                            </a>
                            
                            <div class="submenu">
                                <ul class="submenu-item">
                                    <li class="nav-item"><a class="nav-link" href="{{url('/admin/stadiums')}}"><i
                                                class="link-icon" data-feather="box"></i>All Stadiums</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{url('/admin/stadiums/create')}}"><i
                                                class="link-icon" data-feather="box"></i>Create New Stadium</a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/admin/app-users')}}">
                                <i class="link-icon" data-feather="users"></i>
                                <span class="menu-title">App Users</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/app-users')}}">
                                <i class="link-icon" data-feather="box"></i>
                                <span class="menu-title">Masters</span>
                            </a>
                        </li>







                        <li class="nav-item">

                        </li>
                        <li class="nav-item">

                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- partial -->

        <div class="page-wrapper">
            @yield('content')
            <!-- partial:../../partials/_footer.html -->
            <footer class="footer border-top">
                <div
                    class="container d-flex flex-column flex-md-row align-items-center justify-content-between py-3 small">
                    <p class="text-muted mb-1 mb-md-0">Powered by iMerge</p>
                </div>
            </footer>
            <!-- partial -->
        </div>
    </div>


    <script src="{{ asset('assets/vendors/core/core.js')}}"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js')}}"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="{{ asset('assets/vendors/feather-icons/feather.min.js')}}"></script>
    <script src="{{ asset('assets/js/template.js')}}"></script>

    <script src="{{asset('assets/vendors/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('/assets/js/tinymce.js')}}"></script>

    <script src="{{asset('assets/vendors/dropify/dist/dropify.min.js')}}"></script>
    <script src="{{asset('assets/js/dropify.js')}}"></script>
    <!-- endinject -->

    <!-- Custom js for this page -->
    <script src="{{ asset('assets/js/data-table.js')}}"></script>
</body>

</html>