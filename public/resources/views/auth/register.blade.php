@extends('layouts.app')
@section('content')
<div class="auth-page-wrapper pt-5">
	<!-- auth page bg -->
	<div class="auth-one-bg-position auth-one-bg" id="auth-particles">
		<div class="bg-overlay"></div>

		<div class="shape">
			<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
				<path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
			</svg>
		</div>
	</div>

	<!-- auth page content -->
	<div class="auth-page-content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="text-center mt-sm-5 mb-4 text-white-50">
						<div>
							<a href="{{ url('/') }}" class="d-inline-block auth-logo">
								<img src="{{ URL::asset('assets/jazzpaylogo.jpg') }}" alt="" height="50">
							</a>
						</div>
					</div>
				</div>
			</div>
			<!-- end row -->

			<div class="row justify-content-center">
				<div class="col-md-8 col-lg-6 col-xl-5">
					<div class="card mt-4">

						<div class="card-body p-4">
							<div class="text-center mt-2">
								<h5 class="text-primary">Create New Account</h5>
							</div>
							<div class="p-2 mt-4">
								<form method="POST" action="{{ route('school.signup.submit') }}">
									@csrf

									
							<input type="hidden" name="invite_code" value="{{ $user->invite_code }}">
							<input type="hidden" name="email" value="{{ $user->email }}">
							<input type="hidden" name="name" value="{{ $user->name }}">

									<div class="mb-3">
										<label for="useremail" class="form-label">{{ __('Email Address') }}</label>
										<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email" readonly>
										@error('email')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
										@enderror
										<div class="invalid-feedback">
											Please enter email
										</div>
									</div>

									<div class="mb-3">
										<label for="role" class="form-label">{{ __('Role') }}</label>
										<input id="role" type="text" class="form-control @error('role') is-invalid @enderror" name="role" value="{{ $user->role }}" required autocomplete="role" readonly>
										@error('role')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
										@enderror
										<div class="invalid-feedback">
											Please enter role
										</div>
									</div>


									<div class="mb-3">
										<label for="useremail" class="form-label">{{ __('Name') }}</label>
										<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>
										@error('name')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
										@enderror
										<div class="invalid-feedback">
											Please enter email
										</div>
									</div>
									
									

									<div class="mb-3">
										<label class="form-label" for="password-input">{{ __('Password') }}</label>
										<div class="position-relative auth-pass-inputgroup">
										
											<input type="password" class="form-control pe-5 password-input @error('password') is-invalid @enderror" id="password-input" name="password" autocomplete="new-password" onpaste="return false" >
											@error('password')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
											
											<button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" onclick="togglePasswordVisibility('password-input', this)"><i class="ri-eye-fill align-middle"></i></button>
											<div class="invalid-feedback">
												Please enter password
											</div>
										</div>
									</div>
									
									<div class="mb-3">
										<label class="form-label" for="password-input">{{ __('Confirm Password') }}</label>
										<div class="position-relative auth-pass-inputgroup">
											<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">						
											
											<button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" onclick="togglePasswordVisibility('password-confirm', this)"><i class="ri-eye-fill align-middle"></i></button>
											<div class="invalid-feedback">
												Please enter password
											</div>
										</div>
									</div>
									

									<div id="password-contain" class="p-3 bg-light mb-2 rounded">
										<h5 class="fs-13">Password must contain:</h5>
										<p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8 characters</b></p>
										<p id="pass-lower" class="invalid fs-12 mb-2">At <b>lowercase</b> letter (a-z)</p>
										<p id="pass-upper" class="invalid fs-12 mb-2">At least <b>uppercase</b> letter (A-Z)</p>
										<p id="pass-number" class="invalid fs-12 mb-0">A least <b>number</b> (0-9)</p>
									</div>

									<div class="mt-4">
										<button class="btn btn-success w-100" type="submit">Sign Up</button>
									</div>

									
								</form>

							</div>
						</div>
						<!-- end card body -->
					</div>
					<!-- end card -->

					<div class="mt-4 text-center">
						<p class="mb-0">Already have an account ? <a href="{{ url('/login') }}" class="fw-semibold text-primary text-decoration-underline"> Signin </a> </p>
					</div>

				</div>
			</div>
			<!-- end row -->
		</div>
		<!-- end container -->
	</div>
	<!-- end auth page content -->

	
</div>
<!-- end auth-page-wrapper -->


<script>
    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('ri-eye-fill');
            icon.classList.add('ri-eye-off-line');
        } else {
            input.type = 'password';
            icon.classList.remove('ri-eye-off-line');
            icon.classList.add('ri-eye-fill');
        }
    }
</script>

@endsection
