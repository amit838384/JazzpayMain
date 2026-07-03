<style>
	.layout-rightside{
		display: none;
		
	}


</style>


<div class="app-menu navbar-menu">  
	<!-- LOGO -->
	<div class="navbar-brand-box">
		<a href="{{ url('/admin') }}" class="logo logo-dark">
		<span class="logo-sm">
			<img src="{{ asset('assets/jazzpaylogo.jpg') }}" alt="" width="100" >
		</span>

		<span class="logo-lg">
			<img src="{{ asset('assets/jazzpaylogo.jpg') }}" alt="" width="100" >
		</span>

		</a>
		<!-- Light Logo-->
		<a href="{{ url('/admin') }}" class="logo logo-light">
		<span class="logo-sm">
			<img src="{{ asset('assets/jazzpaylogo.jpg') }}" alt="" width="100" >
		</span>

		<span class="logo-lg">
			<img src="{{ asset('assets/jazzpaylogo.jpg') }}" alt="" width="100" >
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
					<a class="nav-link menu-link" href="#sidebarIcons" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarIcons">
						<i class="ri-compasses-2-line"></i> <span data-key="t-icons">Staff Management</span>
					</a>
					<div class="collapse menu-dropdown" id="sidebarIcons">
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
							<li class="nav-item">
								<a href="{{ url('/users') }}" class="nav-link" data-key="t-material-design">Manage Staff</a>
							</li>
							@endcanany
							@canany(['create-role', 'edit-role', 'delete-role'])
							<li class="nav-item">
								<a href="{{ url('/roles') }}" class="nav-link" data-key="t-material-design">Role Management</a>
							</li>
							 @endcanany
							
						</ul>
					</div>
				</li>

				{{-- //---------Master------------- --}}
				@canany(['create-profession', 'edit-profession', 'delete-profession'])
				 <li class="nav-item">
					<a class="nav-link menu-link" href="#master" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="master">
						<i class="ri-building-4-line"></i> <span data-key="t-icons">School</span>
					</a>
					<div class="collapse menu-dropdown" id="master">
						<ul class="nav nav-sm flex-column">
							
							<li class="nav-item">
								<a href="{{ route('admin.school') }}" class="nav-link" data-key="t-remix">Schools</a>
							</li>

							<li class="nav-item">
								<a href="{{ route('admin.invite_users') }}" class="nav-link" data-key="t-remix">Invited Users</a>
							</li>

							<li class="nav-item">
								<a href="{{ route('admin.schoolusers') }}" class="nav-link" data-key="t-remix">Users</a>
							</li>

							<li class="nav-item">
								<a href="{{ route('admin.parents') }}" class="nav-link" data-key="t-remix">Parents</a>
							</li>

							<li class="nav-item">
								<a href="{{ route('admin.students') }}" class="nav-link" data-key="t-remix">Students</a>
							</li>

						</ul>
					</div>
				</li> 
				@endcanany




	


			</ul>
		</div>
	</div>
</div>

<div class="vertical-overlay"></div>