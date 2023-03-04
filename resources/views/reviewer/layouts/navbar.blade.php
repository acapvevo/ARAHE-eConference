<nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop"
            type="button"><i class="fas fa-bars"></i></button>
        <ul class="navbar-nav flex-nowrap ms-auto">
            <div class="d-none d-sm-block topbar-divider"></div>
            <li class="nav-item dropdown no-arrow">
                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false"
                        data-bs-toggle="dropdown" href="#"><span
                            class="d-none d-lg-inline me-2 text-gray-600 small">{{ Auth::user()->participant->name }}
                            (Reviewer)</span><img class="border rounded-circle img-profile"
                            src="{{ Auth::user()->getImageURL() }}"></a>
                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                        <a class="dropdown-item" href="{{ route('reviewer.user.profile.view') }}">
                            <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile
                        </a>
                        <a class="dropdown-item" href="{{ route('reviewer.user.setting.view') }}">
                            <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('reviewer.logout') }}" method="post">
                            @csrf
                            <a class="dropdown-item" href="#"
                                onclick="event.preventDefault(); this.closest('form').submit();"><i
                                    class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
