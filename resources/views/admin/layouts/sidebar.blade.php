<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.index')}}" class="brand-link">
        <img src="{{ asset('assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                       aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <nav class="mt-4">
            <ul class="nav nav-treeview" style="list-style-type: none;">
                <li class="nav-item" style="border-radius: 10px;">
                    <a href="{{ route('admin.a-y-dashboard') }}" class="nav-link">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.a-y')}}" class="nav-link">
                        <span>Продукты</span>
                    </a>
                </li>
            </ul>

        </nav>

        <!-- Sidebar Menu -->
{{--        <nav class="mt-2">--}}
{{--            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"--}}
{{--                data-accordion="false">--}}
{{--                <!-- Add icons to the links using the .nav-icon class--}}
{{--                     with font-awesome or any other icon font library -->--}}
{{--                <li class="nav-item">--}}
{{--                    <a href="" class="nav-link">--}}
{{--                        <i class="nav-icon fas fa-th"></i>--}}
{{--                        <p>--}}
{{--                            AltinYildiz classics--}}
{{--                            <i class="right fas fa-angle-left"></i>--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('admin.a-y-dashboard') }}" class="nav-link">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Dashboard</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{route('admin.a-y')}}" class="nav-link">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Продукты</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </nav>--}}
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
