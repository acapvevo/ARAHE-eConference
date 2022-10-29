<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
    <div class="container-fluid d-flex flex-column p-0">
        @include('components.logo')
        <hr class="sidebar-divider my-0">
        <ul class="navbar-nav text-light" id="accordionSidebar">
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item"><a
                    class="nav-link {{ request()->routeIs('admin.competition.form.*') || request()->routeIs('admin.competition.rubric.*') ? 'active' : '' }}"
                    href="{{ route('admin.competition.form.list') }}"><i
                        class="fab fa-wpforms"></i><span>Forms</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.system.manual.*') ? 'active' : '' }}"
                    href="{{ route('admin.system.manual.view') }}"><i class="fas fa-book-reader"></i><span>User
                        Manual</span></a></li>
        </ul>
        <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle"
                type="button"></button></div>
    </div>
</nav>
