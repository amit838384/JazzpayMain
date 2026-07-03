@extends('layouts.app')

@section('content')

{{-- ///----Admin Login---/// --}}
	<!-- end auth page content -->

	   <!-- auth-page wrapper -->
	   <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
											 <a href="{{ url('/') }}" class="d-inline-block auth-logo">
								<img src="{{ asset('assets/jazzpaylogo1.jpg') }}" alt="" style="    max-width: 96px;">
							</a> 
                           
                                            </div>
                                            <div class="mt-auto">
                                                <div class="mb-3">
                                                    <i class="ri-double-quotes-l display-4 text-success"></i>
                                                </div>

                                          
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div>
                                            <h5 class="text-primary">Welcome Back !</h5>
                                            <p class="text-muted">Sign in to continue to Admin Dashboard.</p>
                                        </div>
										@if (session('error'))
							<div class="alert alert-danger">
								{{ session('error') }}
							</div>
						@endif

                                        <div class="mt-4">
											<form method="POST" action="{{ route('login') }}">
												@csrf
												<div class="mb-3">
													<label for="username" class="form-label">Username</label>
													{{--<input type="text" class="form-control" id="username" placeholder="Enter username">--}}
													<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
													@error('email')
														<span class="invalid-feedback" role="alert">
															<strong>{{ $message }}</strong>
														</span>
													@enderror
												</div>
			
												<div class="mb-3">
			
													<label class="form-label" for="password-input">Password</label>
													<div class="position-relative auth-pass-inputgroup mb-3">
														{{--<input type="password" class="form-control pe-5" placeholder="Enter password" id="password-input">--}}
														<input id="password-input" type="password" class="form-control pe-5 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
														@error('password')
															<span class="invalid-feedback" role="alert">
																<strong>{{ $message }}</strong>
															</span>
														@enderror
														<!-- <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button> -->
													</div>
												</div>
			
			
			
												<div class="mt-4">
													<button class="btn btn-success w-100" type="submit" >{{ __('Sign In') }}</button>
												</div>
			
			
											</form>
                                        </div>

                                       
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0">&copy;
                                <script>document.write(new Date().getFullYear())</script> Spring House. Crafted with <i class="mdi mdi-heart text-danger"></i> Doubleklick Designs
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->


	<!-- end Footer -->
</div>
<!-- end auth-page-wrapper -->
@endsection
