<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="index3.html" class="brand-link">
        <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                {{-- <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Starter Pages
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Active Page</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inactive Page</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}
                <li class="nav-item">
                    <router-link to="/admin/dashboard" active-class="active" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/admin/appointments"
                        :class="$route.path.startsWith('/admin/appointments') ? 'active' : ''" class="nav-link">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p> Appointments </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/admin/users" active-class="active" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p> Users </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/admin/settings" active-class="active" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p> Settings </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/admin/profile" active-class="active" class="nav-link">
                        <i class="nav-icon fas fa-user-alt"></i>
                        <p> Profile </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" class="nav-link" active-class="active" method="Post">
                        @csrf
                        <a onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p> Logout </p>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
