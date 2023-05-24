<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
    <div class="container-fluid d-flex flex-column p-0">
        @include('components.logo')
        <hr class="sidebar-divider my-0">
        <ul class="navbar-nav text-light" id="accordionSidebar">
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('super_admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('super_admin.dashboard') }}"><i
                        class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <li class="nav-item"><a
                    class="nav-link {{ request()->routeIs('super_admin.management.admin.*') ? 'active' : '' }}"
                    href="{{ route('super_admin.management.admin.list') }}"><i
                        class="fa-solid fa-user-tie"></i><span>Admin Management</span></a></li>
            <li class="nav-item"><a
                    class="nav-link {{ request()->routeIs('super_admin.system.manual.*') ? 'active' : '' }}"
                    href="{{ route('super_admin.system.manual.list') }}"><i class="fas fa-book-reader"></i><span>User
                        Manual</span></a></li>
            <li class="nav-item"><a
                    class="nav-link {{ request()->routeIs('super_admin.system.health.*') ? 'active' : '' }}"
                    href="{{ route('super_admin.system.health.view') }}"><i class="far fa-heartbeat"></i><span>System
                        Health</span></a></li>
        </ul>
        <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle"
                type="button"></button></div>
    </div>
</nav>
