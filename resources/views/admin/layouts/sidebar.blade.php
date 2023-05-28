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
                    href="{{ route('admin.competition.form.list') }}"><i class="fab fa-wpforms"></i><span>Form
                        Setting</span></a></li>
            <li class="nav-item"><a
                    class="nav-link {{ request()->routeIs('admin.submission.registration.*') ? 'active' : '' }}"
                    href="{{ route('admin.submission.registration.list') }}"><i
                        class="fa-solid fa-user-group"></i><span>Registration Management</span></a></li>
            <li class="nav-item"><a
                    class="nav-link {{ request()->routeIs('admin.submission.package.*') ? 'active' : '' }}"
                    href="{{ route('admin.submission.package.list') }}"><i
                        class="fa-solid fa-users-between-lines"></i><span>Package Management</span></a></li>
            <li class="nav-item"><a
                    class="nav-link {{ request()->routeIs('admin.submission.assign.*') ? 'active' : '' }}"
                    href="{{ route('admin.submission.assign.list') }}"><i class="fas fa-user-check"></i><span>Assign
                        Reviewer</span></a></li>
            <li class="nav-item"><a
                    class="nav-link {{ request()->routeIs('admin.submission.paper.*') ? 'active' : '' }}"
                    href="{{ route('admin.submission.paper.list') }}"><i class="far fa-file-alt"></i><span>View
                        Papers</span></a></li>
            <li class="nav-item"><a
                    class="nav-link {{ request()->routeIs('admin.member.participant.*') ? 'active' : '' }}"
                    href="{{ route('admin.member.participant.list') }}"><i
                        class="fas fa-user-graduate"></i><span>Participant Management</span></a></li>
            <li class="nav-item"><a
                    class="nav-link {{ request()->routeIs('admin.member.reviewer.*') ? 'active' : '' }}"
                    href="{{ route('admin.member.reviewer.list') }}"><i class="fas fa-user-tie"></i><span>Reviewer
                        Management</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.payment.bill.*') ? 'active' : '' }}"
                    href="{{ route('admin.payment.bill.list') }}"><i class="fas fa-money-check"></i><span>Payment
                        Management</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.system.manual.*') ? 'active' : '' }}"
                    href="{{ route('admin.system.manual.view') }}"><i class="fas fa-book-reader"></i><span>User
                        Manual</span></a></li>
        </ul>
        <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle"
                type="button"></button></div>
    </div>
</nav>
