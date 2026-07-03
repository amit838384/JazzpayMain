@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <p>This is your application dashboard.</p>
                    @canany(['create-role', 'edit-role', 'delete-role'])
                        <a class="btn btn-primary" href="{{ route('roles.index') }}">
                            <i class="bi bi-person-fill-gear"></i> Manage Roles</a>
                    @endcanany
                    @canany(['create-user', 'edit-user', 'delete-user'])
                        <a class="btn btn-success" href="{{ route('users.index') }}">
                            <i class="bi bi-people"></i> CA-Junction Users</a>
                    @endcanany
                 
                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
    </div>
	


	<div class="row" style="display: none;">
		<div class="col-xl-3 col-md-6">
			<!-- card -->
			<div class="card card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1 overflow-hidden">
							<p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Earnings</p>
						</div>
						<div class="flex-shrink-0">
							<h5 class="text-success fs-14 mb-0">
								<i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 %
							</h5>
						</div>
					</div>
					<div class="d-flex align-items-end justify-content-between mt-4">
						<div>
							<h4 class="fs-22 fw-semibold ff-secondary mb-4">$<span class="counter-value" data-target="559.25">0</span>k </h4>
							<a href="#" class="text-decoration-underline">View net earnings</a>
						</div>
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-soft-success rounded fs-3">
								<i class="bx bx-dollar-circle text-success"></i>
							</span>
						</div>
					</div>
				</div><!-- end card body -->
			</div><!-- end card -->
		</div><!-- end col -->

		<div class="col-xl-3 col-md-6">
			<!-- card -->
			<div class="card card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1 overflow-hidden">
						 <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Orders</p>
						</div>
						<div class="flex-shrink-0">
							<h5 class="text-danger fs-14 mb-0">
								<i class="ri-arrow-right-down-line fs-13 align-middle"></i> -3.57 %
							</h5>
						</div>
					</div>
					<div class="d-flex align-items-end justify-content-between mt-4">
						<div>
							<h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="36894">0</span></h4>
							<a href="#" class="text-decoration-underline">View all orders</a>
						</div>
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-soft-info rounded fs-3">
								<i class="bx bx-shopping-bag text-info"></i>
							</span>
						</div>
					</div>
				</div><!-- end card body -->
			</div><!-- end card -->
		</div><!-- end col -->

		<div class="col-xl-3 col-md-6">
			<!-- card -->
			<div class="card card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1 overflow-hidden">
							<p class="text-uppercase fw-medium text-muted text-truncate mb-0">Customers</p>
						</div>
						<div class="flex-shrink-0">
							<h5 class="text-success fs-14 mb-0">
								<i class="ri-arrow-right-up-line fs-13 align-middle"></i> +29.08 %
							</h5>
						</div>
					</div>
					<div class="d-flex align-items-end justify-content-between mt-4">
						<div>
							<h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="183.35">0</span>M </h4>
							<a href="#" class="text-decoration-underline">See details</a>
						</div>
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-soft-warning rounded fs-3">
								<i class="bx bx-user-circle text-warning"></i>
							</span>
						</div>
					</div>
				</div><!-- end card body -->
			</div><!-- end card -->
		</div><!-- end col -->

		<div class="col-xl-3 col-md-6">
			<!-- card -->
			<div class="card card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1 overflow-hidden">
							<p class="text-uppercase fw-medium text-muted text-truncate mb-0"> My Balance</p>
						</div>
						<div class="flex-shrink-0">
							<h5 class="text-muted fs-14 mb-0">
								+0.00 %
							</h5>
						</div>
					</div>
					<div class="d-flex align-items-end justify-content-between mt-4">
						<div>
							<h4 class="fs-22 fw-semibold ff-secondary mb-4">$<span class="counter-value" data-target="165.89">0</span>k </h4>
							<a href="#" class="text-decoration-underline">Withdraw money</a>
						</div>
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-soft-primary rounded fs-3">
								<i class="bx bx-wallet text-primary"></i>
							</span>
						</div>
					</div>
				</div><!-- end card body -->
			</div><!-- end card -->
		</div><!-- end col -->
	</div> <!-- end row-->


	<div class="row">
		<div class="col-xl-3 col-md-6">
			<!-- card -->
			<div class="card card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1 overflow-hidden">
							<p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Professions</p>
						</div>
					
					</div>
					<div class="d-flex align-items-end justify-content-between mt-4">
						<div>
							{{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $profession_count }}">0</span>	</h4> --}}
						
						</div>
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-soft-success rounded fs-3">
								<i class="bx bx-server text-success"></i>
							</span>
						</div>
					</div>
				</div><!-- end card body -->
			</div><!-- end card -->
		</div><!-- end col -->

		<div class="col-xl-3 col-md-6">
			<!-- card -->
			<div class="card card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1 overflow-hidden">
						 <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Services</p>
						</div>
					
					</div>
					<div class="d-flex align-items-end justify-content-between mt-4">
						<div>
							{{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $professionService_count }}">0</span></h4> --}}
					
						</div>
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-soft-info rounded fs-3">
								<i class="bx bx-shield text-info"></i>
							</span>
						</div>
					</div>
				</div><!-- end card body -->
			</div><!-- end card -->
		</div><!-- end col -->

		<div class="col-xl-3 col-md-6">
			<!-- card -->
			<div class="card card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1 overflow-hidden">
							<p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Professionals</p>
						</div>
						
					</div>
					<div class="d-flex align-items-end justify-content-between mt-4">
						<div>
							{{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $Professional_user_count }}">0</span></h4> --}}
						
						</div>
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-soft-warning rounded fs-3">
								<i class="bx bx-user-circle text-warning"></i>
							</span>
						</div>
					</div>
				</div><!-- end card body -->
			</div><!-- end card -->
		</div><!-- end col -->

		<div class="col-xl-3 col-md-6">
			<!-- card -->
			<div class="card card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1 overflow-hidden">
							<p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Clients</p>
						</div>
					
					</div>
					<div class="d-flex align-items-end justify-content-between mt-4">
						<div>
							{{-- <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $Client_user_count }}">0</span> </h4> --}}

						</div>
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-soft-primary rounded fs-3">
								<i class="bx bx-user-circle text-primary"></i>
							</span>
						</div>
					</div>
				</div><!-- end card body -->
			</div><!-- end card -->
		</div><!-- end col -->
	</div> <!-- end row-->
</div>
@endsection