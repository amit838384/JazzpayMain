
<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box horizontal-logo">
                    <a href="index.html" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/assets/cajunction_logo.jpeg') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/assets/cajunction_logo.jpeg') }}" alt="" height="17">
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/assets/cajunction_logo.jpeg') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/assets/cajunction_logo.jpeg') }}" alt="" height="17">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <!-- App Search-->
               
            </div>

            <div class="d-flex align-items-center">

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="{{ URL::asset('build/assets/images/users/avatar-1.jpg') }}"
                                alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">Admin</span>
                                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">Super Admin</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="showtime">
                        <!-- item-->
                        <!-- <h6 class="dropdown-header">Welcome Anna!</h6> -->
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
							<i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> 
							<span class="align-middle">Change Password</span>
						</a>
                       
                    </div>
                        <div class="dropdown-divider"></div> 
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
							<i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> 
							<span class="align-middle" data-key="t-logout">Logout</span>
						</a>
						 <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>