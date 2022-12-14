<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
    <div class="container-fluid d-flex flex-column p-0">
        @include('components.logo')
        <hr class="sidebar-divider my-0">
        <ul class="navbar-nav text-light" id="accordionSidebar">
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('reviewer.dashboard') ? 'active' : '' }}"
                    href="{{ route('reviewer.dashboard') }}"><i
                        class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <li class="nav-item"><a
                    class="nav-link {{ request()->routeIs('reviewer.submission.review.*') ? 'active' : '' }}"
                    href="{{ route('reviewer.submission.review.list') }}"><i
                        class="fas fa-file-signature"></i><span>Give Review</span></a></li>
            <li class="nav-item"><a
                    class="nav-link {{ request()->routeIs('reviewer.system.manual.*') ? 'active' : '' }}"
                    href="{{ route('reviewer.system.manual.view') }}"><i class="fas fa-book-reader"></i><span>User
                        Manual</span></a></li>
        </ul>
        <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle"
                type="button"></button></div>
    </div>
</nav>
