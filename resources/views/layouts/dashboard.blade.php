<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title') &mdash; Status Gizi</title>

    <!-- General CSS Files -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- CSS Libraries -->
    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />



    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/components.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    {{-- data table --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">


</head>

<body>
    <script id="__bs_script__">
        document.write("<script async src='/browser-sync/browser-sync-client.js?v=2.27.10'><\/script>".replace("HOST",
            location.hostname));
    </script>

    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                                    class="fas fa-search"></i></a></li>
                    </ul>
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                            data-width="250">
                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                        <div class="search-backdrop"></div>
                        <div class="search-result">
                            <div class="search-header">
                                Histories
                            </div>
                            <div class="search-item">
                                <a href="#">How to hack NASA using CSS</a>
                                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="search-item">
                                <a href="#">Kodinger.com</a>
                                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="search-item">
                                <a href="#">#Stisla</a>
                                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="search-header">
                                Result
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <img class="mr-3 rounded" width="30"
                                        src="{{ asset('assets') }}/img/products/product-3-50.png" alt="product">
                                    oPhone S9 Limited Edition
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <img class="mr-3 rounded" width="30"
                                        src="{{ asset('assets') }}/img/products/product-2-50.png" alt="product">
                                    Drone X2 New Gen-7
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <img class="mr-3 rounded" width="30"
                                        src="{{ asset('assets') }}/img/products/product-1-50.png" alt="product">
                                    Headphone Blitz
                                </a>
                            </div>
                            <div class="search-header">
                                Projects
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <div class="search-icon bg-danger text-white mr-3">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    Stisla Admin Template
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <div class="search-icon bg-primary text-white mr-3">
                                        <i class="fas fa-laptop"></i>
                                    </div>
                                    Create a new Homepage Design
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                <ul class="navbar-nav navbar-right">

                    @php
                    $notifications = auth()->user()->unreadNotifications;
                @endphp
               <li class="dropdown dropdown-list-toggle">
                <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle">
                    <i class="far fa-envelope"></i>
                    @if (auth()->user()->unreadNotifications->count())
                        <span class="badge badge-pill badge-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-list dropdown-menu-right">
                    <div class="dropdown-header">Konsultasi
                        <div class="float-right">
                            <a href="{{ route('markAsRead') }}">Tandai sudah dibaca</a>

                        </div>
                    </div>
                    <div class="dropdown-list-content dropdown-list-message">
                        @foreach ($notifications as $notification)
                            <a href="{{ route('konsultasi.index') }}"
                                class="dropdown-item dropdown-item-unread">
                                <div class="dropdown-item-avatar">
                                    <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}"
                                        class="rounded-circle">
                                    <div class="is-online"></div>
                                </div>
                                <div class="dropdown-item-desc">
                                    @if (Auth::user()->role == 'remaja')
                                        <b>Halo {{ $notification->data['remaja_nama'] }}, Anda Telah Menerima
                                            Balasan dari Petugas</b>
                                        <b>Untuk Konsultasi dengan Perihal:
                                            {{ $notification->data['perihal'] }}</b>
                                        <p>Balasan: {{ Str::limit($notification->data['deskripsi'], 50) }}</p>
                                    @else
                                        <b>Notifikasi Baru!</b>
                                        <p><b>{{ $notification->data['remaja_nama'] }}</b> - <b>{{ $notification->data['perihal'] }}</b></p>
                                        <p>{{ Str::limit($notification->data['deskripsi'], 50) }}</p>
                                    @endif
                                    <div class="time">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="dropdown-footer text-center">
                        <a href="{{ route('konsultasi.index') }}">View All <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </li>
            
                


                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle nav-link-lg nav-link-user"
                            data-toggle="dropdown">
                            <img src="{{ asset('assets/img/avatar/avatar-1.png') }}" alt="image"
                                class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>

                </ul>
            </nav>


            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="index.html">STATUS GIZI REMAJA</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="index.html">St</a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Dashboard</li>

                        <li class="{{ request()->is('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}" class="nav-link active">
                                <i class="bi bi-house-door-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        @if (Auth::user()->role != 'remaja')
                            <li class="menu-header">Data Master</li>

                            <li class="{{ request()->is('remaja') ? 'active' : '' }}">
                                <a href="{{ route('remaja.index') }}" class="nav-link active">
                                    <i class="bi bi-people-fill"></i>
                                    <span>Remaja</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('petugas') ? 'active' : '' }}">
                                <a href="{{ route('petugas.index') }}" class="nav-link active">
                                    <i class="bi bi-person-badge-fill"></i>
                                    <span>Petugas</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('pengukuran') ? 'active' : '' }}">
                                <a href="{{ route('pengukuran.index') }}" class="nav-link active">
                                    <i class="bi bi-rulers"></i>
                                    <span>Pengukuran</span>
                                </a>
                            </li>

                           
                        @endif

                        @if (Auth::user()->role == 'admin')
                        <li class="{{ request()->is('users') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class="nav-link active">
                                <i class="bi bi-person-fill"></i>
                                <span>Admin</span>
                            </a>
                        </li>
                        @endif
                        <li class="{{ request()->is('konsultasi') ? 'active' : '' }}">
                            <a href="{{ route('konsultasi.index') }}" class="nav-link active">
                                <i class="bi bi-chat-dots-fill"></i>
                                <span>Konsultasi</span>
                            </a>
                        </li>
                        <li class="{{ request()->is('artikel') ? 'active' : '' }}">
                            <a href="{{ route('artikel.index') }}" class="nav-link active">
                                <i class="bi bi-file-earmark-text-fill"></i>
                                <span>Artikel</span>
                            </a>
                        </li>

                        @if (Auth::user()->role == 'remaja')
                        <li class="{{ request()->is('profil') ? 'active' : '' }}">
                            <a href="{{ route('profile') }}" class="nav-link active">
                                <i class="bi bi-chat-dots-fill"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        @endif



            </div>

            </aside>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>@yield('judul')</h1>
                </div>

            </section>
            @yield('content')
        </div>

    </div>
    </div>
    <script>
        function previewImage() {
            const image = document.querySelector('#gambar');
            const imgPreview = document.querySelector('.img-preview');

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    @yield('scripts')

   
    <!-- General JS Scripts -->>


    <!-- JavaScript Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ asset('assets') }}/js/stisla.js"></script>

    <!-- JS Libraies -->
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('assets') }}/js/scripts.js"></script>
    <script src="{{ asset('assets') }}/js/custom.js"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('assets') }}/js/page/index-0.js"></script>

    {{-- data table --}}
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        });
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->any())
        <script>
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += "{{ $error }}\n";
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: errorMessages,
            });
        </script>
    @endif

    @if (session('success') || session('error'))
        <script>
            $(document).ready(function() {
                var successMessage = "{{ session('success') }}";
                var errorMessage = "{{ session('error') }}";

                if (successMessage) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: successMessage,
                    });
                }

                if (errorMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                    });
                }
            });
        </script>
    @endif
    @yield('scripts')



</body>

</html>
