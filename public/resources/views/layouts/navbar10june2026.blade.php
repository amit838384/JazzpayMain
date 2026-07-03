<style>
.layout-rightside{
	display: none;
}
li.nav-item.active a {
	border: 1px solid #fff;
	background-color: #fff;
	color: #9b203d !important;
}

[data-layout=vertical][data-sidebar=dark] .navbar-nav .nav-sm .active .nav-link:before {
	background-color: #9b203d !important;
}
</style>
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ url('/admin') }}" class="logo logo-dark">
			<span class="logo-sm">
				<img src="{{ asset('assets/jazzpaylogo1.jpg') }}" alt="" width="100" >
			</span>
			<span class="logo-lg">
				<img src="{{ asset('assets/jazzpaylogo1.jpg') }}" alt="" width="100" >
			</span>
        </a>
        <!-- Light Logo-->
        <a href="{{ url('/admin') }}" class="logo logo-light">
			<span class="logo-sm">
				<img src="{{ asset('assets/jazzpaylogo1.jpg') }}" alt="" width="100" >
			</span>
			<span class="logo-lg">
				<img src="{{ asset('assets/jazzpaylogo1.jpg') }}" alt="" width="100" >
			</span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
        <i class="ri-record-circle-line"></i>
        </button>
    </div>
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a href="{{ url('/admin') }}" class="nav-link" data-key="t-remix">
                    <i class="ri-dashboard-line"></i> Dashboard
                    </a>
                </li>
                @canany(['create-profession', 'edit-profession', 'delete-profession'])
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarIcons" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarIcons">
                    <i class="ri-compasses-2-line"></i> <span data-key="t-icons">Staff Management</span>
                    </a>
                    <div class="collapse menu-dropdown custom-menu {{ request()->is('users') || request()->is('roles') ? 'show' : '' }}" id="sidebarIcons">
                        <ul class="nav nav-sm flex-column">
                            {{-- @canany(['create-category', 'edit-category', 'delete-category'])
                            <li class="nav-item">
                                <a href="{{ url('/categories') }}" class="nav-link" data-key="t-remix">Category Management</a>
                            </li>
                            @endcanany
                            @canany(['create-product', 'edit-product', 'delete-product'])
                            <li class="nav-item">
                                <a href="{{ url('/products') }}" class="nav-link" data-key="t-boxicons">Product Management</a>
                            </li>
                            @endcanany --}}
                            @canany(['create-user', 'edit-user', 'delete-user'])
                            <li class="nav-item {{(request()->is('users')) ? 'active' : '' }}">
                                <a href="{{ url('/users') }}" class="nav-link" data-key="t-material-design">Manage Staff</a>
                            </li>
                            @endcanany
                            @canany(['create-role', 'edit-role', 'delete-role'])
                            <li class="nav-item {{(request()->is('roles')) ? 'active' : '' }}">
                                <a href="{{ url('/roles') }}" class="nav-link" data-key="t-material-design">Role Management</a>
                            </li>
                            @endcanany
                        </ul>
                    </div>
                </li>
                @endcanany
                @canany(['create-profession', 'edit-profession', 'delete-profession'])
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#master" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="master">
                    <i class="ri-building-4-line"></i> <span data-key="t-icons">School</span>
                    </a>
                    <div class="collapse menu-dropdown custom-menu {{ request()->is('admin/school') || request()->is('admin/invite-users') || request()->is('admin/school-users') || request()->is('admin/parents') || request()->is('admin/students') ? 'show' : '' }}" id="master">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item {{(request()->is('admin/school')) ? 'active' : '' }}">
                                <a href="{{ route('admin.school') }}" class="nav-link" data-key="t-remix">Schools</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/invite-users')) ? 'active' : '' }}">
                                <a href="{{ route('admin.invite_users') }}" class="nav-link" data-key="t-remix">Invited Users</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/school-users')) ? 'active' : '' }}">
                                <a href="{{ route('admin.schoolusers') }}" class="nav-link" data-key="t-remix">Users</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/parents')) ? 'active' : '' }}">
                                <a href="{{ route('admin.parents') }}" class="nav-link" data-key="t-remix">Parents</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/students')) ? 'active' : '' }}">
                                <a href="{{ route('admin.students') }}" class="nav-link" data-key="t-remix">Students</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcanany
                @canany(['create-profession', 'edit-profession', 'delete-profession'])
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#cafeterias" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="cafeterias">
                    <i class="ri-restaurant-2-line"></i> <span data-key="t-icons">Cafeteria</span>
                    </a>
                    <div class="collapse menu-dropdown custom-menu {{ request()->is('admin/cafeterias') || request()->is('admin/cafeterias-user') || request()->is('admin/cafeteriaslist-user') || request()->is('admin/cards') ? 'show' : '' }}" id="cafeterias">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item {{(request()->is('admin/cafeterias')) ? 'active' : '' }}">
                                <a href="{{ route('admin.cafeterias') }}" class="nav-link" data-key="t-remix">Cafeterias</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/cafeterias-user')) ? 'active' : '' }}">
                                <a href="{{ route('admin.cafeterias_user') }}" class="nav-link" data-key="t-remix">Invited Users</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/cafeteriaslist-user')) ? 'active' : '' }}">
                                <a href="{{ route('admin.cafeteriaslist_user') }}" class="nav-link" data-key="t-remix">Users</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/cards')) ? 'active' : '' }}">
                                <a href="{{ route('admin.cards') }}" class="nav-link" data-key="t-remix">Card</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcanany
                @canany(['create-profession', 'edit-profession', 'delete-profession'])
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#manage_cafeteria" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="manage_cafeteria">
                    <i class="ri-home-smile-2-line"></i> <span data-key="t-icons">Manage Cafe</span>
                    </a>
                    <div class="collapse menu-dropdown custom-menu {{ request()->is('admin/menu') || request()->is('admin/dish') || request()->is('admin/dish-category') || request()->is('admin/meal-category') || request()->is('admin/ingredients-category') || request()->is('admin/assign-user') ? 'show' : '' }}" id="manage_cafeteria">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item {{(request()->is('admin/menu')) ? 'active' : '' }}">
                                <a href="{{ route('admin.menu') }}" class="nav-link" data-key="t-remix">Menu</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/dish')) ? 'active' : '' }}">
                                <a href="{{ route('admin.dish') }}" class="nav-link" data-key="t-remix">Dishes</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/dish-category')) ? 'active' : '' }}">
                                <a href="{{ route('admin.dish_category') }}" class="nav-link" data-key="t-remix">Dishes Category</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/meal-category')) ? 'active' : '' }}">
                                <a href="{{ route('admin.meal_category') }}" class="nav-link" data-key="t-remix">Meals</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/ingredients-category')) ? 'active' : '' }}">
                                <a href="{{ route('admin.ingredients_category') }}" class="nav-link" data-key="t-remix">Ingredients</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/assign-user')) ? 'active' : '' }}">
                                <a href="{{ route('admin.assign_user') }}" class="nav-link" data-key="t-remix">Assign Cafeteria</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcanany
                @canany(['create-profession', 'edit-profession', 'delete-profession'])
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#dish_sales" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="dish_sales">
                    <i class="ri-home-smile-2-line"></i> <span data-key="t-icons">Business Insights</span>
                    </a>
                    <div class="collapse menu-dropdown custom-menu {{ request()->is('admin/menu') || request()->is('admin/dish') || request()->is('admin/dish-category') || request()->is('admin/meal-category') || request()->is('admin/ingredients-category') || request()->is('admin/assign-user') ? 'show' : '' }}" id="dish_sales">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item {{(request()->is('admin/menu')) ? 'active' : '' }}">
                                <a href="{{ route('admin.dish_sales') }}" class="nav-link" data-key="t-remix">Dish Sales</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcanany
                @canany(['create-profession', 'edit-profession', 'delete-profession'])
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#order_details" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="order_details">
                    <i class="ri-home-smile-2-line"></i> <span data-key="t-icons">Order Details</span>
                    </a>
                    <div class="collapse menu-dropdown custom-menu {{ request()->is('admin/pre-orders') ? 'show' : '' }}" id="order_details">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item {{(request()->is('admin/pre-orders')) ? 'active' : '' }}">
                                <a href="{{ route('admin.pre_orders') }}" class="nav-link" data-key="t-remix">Pre-orders</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.onsitesales') }}" class="nav-link" data-key="t-remix">Onsite Sales</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-key="t-remix">PFS orders</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-key="t-remix">Reports</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-key="t-remix">Sales Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.cafe_pos_report') }}" class="nav-link" data-key="t-remix">Cafe Pos Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.brand_card_sales') }}" class="nav-link" data-key="t-remix">Brand & Car Sales</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcanany
                @canany(['create-profession', 'edit-profession', 'delete-profession'])
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#top_ups" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="top_ups">
                    <i class="ri-home-smile-2-line"></i> <span data-key="t-icons">Topups History</span>
                    </a>
                    <div class="collapse menu-dropdown custom-menu {{ request()->is('admin/topuplist') || request()->is('admin/topuplist') || request()->is('admin/credit-transfer') ? 'show' : '' }}" id="top_ups">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item {{(request()->is('admin/topuplist')) ? 'active' : '' }}">
                                <a href="{{ route('admin.topuplist_parents') }}" class="nav-link" data-key="t-remix">Parents</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/topuplist')) ? 'active' : '' }}">
                                <a href="{{ route('admin.topuplist_parents') }}" class="nav-link" data-key="t-remix">Cafeteria</a>
                            </li>
                            <li class="nav-item {{(request()->is('admin/credit-transfer')) ? 'active' : '' }}">
                                <a href="{{ route('admin.credit_transfer') }}" class="nav-link" data-key="t-remix">Credit Transfer</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcanany
                @canany(['create-profession', 'edit-profession', 'delete-profession'])
                <li class="nav-item">
                    <a href="#" class="nav-link" data-key="t-remix">
                    <i class="ri-home-smile-2-line"></i> Mails
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-key="t-remix">
                    <i class="ri-home-smile-2-line"></i> Pay for Service
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.statistics') }}" class="nav-link" data-key="t-remix">
                    <i class="ri-home-smile-2-line"></i> Statistics
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-key="t-remix">
                    <i class="ri-home-smile-2-line"></i> Platform Biling
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-key="t-remix">
                    <i class="ri-home-smile-2-line"></i> Sadaq Payment
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.alert_message') }}" class="nav-link" data-key="t-remix">
                    <i class="ri-home-smile-2-line"></i> App Message
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.app_feedback') }}" class="nav-link" data-key="t-remix">
                    <i class="ri-home-smile-2-line"></i> Feedback
                    </a>
                </li>
                @endcanany
                @auth
                @if(auth()->user()->role == 'schooladmin')
					{{--<li class="nav-item">
						<a href="{{ route('admin') }}" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> Dashboard
						</a>
					</li>--}}
					<li class="nav-item">
						<a class="nav-link menu-link" href="#manage_cafeteria" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="manage_cafeteria">
						<i class="ri-home-smile-2-line"></i> <span data-key="t-icons">School Users</span>
						</a>
						<div class="collapse menu-dropdown custom-menu {{ request()->is('admin/school-invite-users') || request()->is('admin/topuplist') ? 'show' : '' }}" id="manage_cafeteria">
							<ul class="nav nav-sm flex-column">
								<li class="nav-item">
									<a href="{{ route('admin.school_invite_users') }}" class="nav-link" data-key="t-remix">Invite Users</a>
								</li>
								<li class="nav-item">
									<a href="{{ route('admin.school_manage_users') }}" class="nav-link" data-key="t-remix">Manage Users</a>
								</li>
							</ul>
						</div>
					</li>
					<li class="nav-item">
						<a href="{{ route('admin.school_students') }}" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> Students
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('admin.school_parents') }}" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> Parents
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('admin.school_pre_orders') }}" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> Pre-Orders
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('admin.School_onsite') }}" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> Onsite Sales
						</a>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> PFS-Orders
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('admin.consumption_by_school') }}" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> Consumption Report
						</a>
					</li>
                @endif
                @endauth
                @auth
                @if(auth()->user()->role == 'cafeteriaadmin')
					<li class="nav-item">
						<a href="{{ route('admin.cafeteria_students') }}" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> Students
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('admin.cafeteria_onsite') }}" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> POS (Onsite)
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('admin.cafeteria_pre_orders') }}" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> Pre-Orders
						</a>
					</li>
					{{-- <li class="nav-item">
						<a href="#" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> PFS-orders
						</a>
					</li> --}}
					<li class="nav-item">
						<a href="#" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> Cafe Topup
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('admin.cafeteria_cards') }}" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> Cards
						</a>
					</li>

                    <li class="nav-item">
						<a href="{{ route('admin.cafeteria_Plans_list') }}" class="nav-link" data-key="t-remix">
						<i class="ri-home-smile-2-line"></i> Subscription Plans
						</a>
					</li>
                @endif
                @endauth
            </ul>
        </div>
    </div>
</div>
<div class="vertical-overlay"></div>