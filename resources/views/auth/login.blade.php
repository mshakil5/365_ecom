@extends('frontend.pages.master')

@section('content')
<section class="pricing section light-background">
  <div class="container">
    <div class="row gy-4 justify-content-center">
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="pricing-item recommended">
          <h2 class="text-center mb-4">Login</h2>
          <p class="text-center mb-4">Sign in to start your session</p>

          <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
              <input id="email" type="email"
                     class="form-control @error('email') is-invalid @enderror"
                     name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                     placeholder="Email">
              @error('email')
                <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <input id="password" type="password"
                     class="form-control @error('password') is-invalid @enderror"
                     name="password" required autocomplete="current-password"
                     placeholder="Password">
              @error('password')
                <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="text-center mt-4">
              <button type="submit" class="btn-buy w-100">Sign In</button>
            </div>

            <div class="text-center mt-3">
              <a href="{{ route('password.request') }}">Forgot Your Password?</a>
            </div>

            <div class="text-center mt-3">
              <a href="{{ route('register') }}">Don't have an account? Register</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</section>
@endsection
