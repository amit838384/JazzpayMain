@extends('layouts.app')

@section('content')
<div class="container">
    
	




	<div class="row">
		<div class="col-xl-3 col-md-6">
			<!-- card -->
		<a href="{{ url('/admin/profession') }}">

			<!-- <div class="card card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1 overflow-hidden">
							<p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Professions</p>
						</div>
					
					</div>
					<div class="d-flex align-items-end justify-content-between mt-4">
						<div>
							<h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="">0</span>	</h4>
						
						</div>
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-soft-success rounded fs-3">
								<i class="bx bx-server text-success"></i>
							</span>
						</div>
					</div>
				</div>
			</div> -->
			</a>
		</div><!-- end col -->

		 <!-- <div class="col-xl-3 col-md-6">
		
			<a href="{{ url('/admin/service') }}">
			<div class="card card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1 overflow-hidden">
						 <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Services</p>
						</div>
					
					</div>
					<div class="d-flex align-items-end justify-content-between mt-4">
						<div>
							<h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="">0</span></h4>
					
						</div>
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-soft-info rounded fs-3">
								<i class="bx bx-shield text-info"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
		</a>
		</div> -->

		<!-- <div class="col-xl-3 col-md-6">
			<a href="{{ url('/admin/professionals') }}">
			<div class="card card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1 overflow-hidden">
							<p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Professionals</p>
						</div>
						
					</div>
					<div class="d-flex align-items-end justify-content-between mt-4">
						<div>
							<h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="">0</span></h4>
						
						</div>
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-soft-warning rounded fs-3">
								<i class="bx bx-user-circle text-warning"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div> -->

		<!-- <div class="col-xl-3 col-md-6">
			<a href="{{ url('/admin/clients') }}">
			<div class="card card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="flex-grow-1 overflow-hidden">
							<p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Clients</p>
						</div>
					
					</div>
					<div class="d-flex align-items-end justify-content-between mt-4">
						<div>
							<h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="">0</span> </h4>

						</div>
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-soft-primary rounded fs-3">
								<i class="bx bx-user-circle text-primary"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
		</a>
		</div> -->
	</div> 
</div>
@endsection