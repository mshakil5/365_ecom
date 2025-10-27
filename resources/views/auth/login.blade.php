@extends('frontend.pages.master')

@section('content')

<div class="breadcrumb-section">
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between justify-content-md-between  align-items-center flex-md-row flex-column">
                    <h3 class="breadcrumb-title">Login</h3>
                    <div class="breadcrumb-nav">
                        <nav aria-label="breadcrumb">
                            <ul>
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li class="active" aria-current="page">Login</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="customer_login">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-6">
                <div class="account_form" data-aos="fade-up"  data-aos-delay="0">
                    <h3>login</h3>
                        @if (session('message'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="default-form-box mb-20">
                            <label>Email <span>*</span></label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="default-form-box mb-20">
                            <label>Password <span>*</span></label>
                            <input type="password"  class="form-control" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="login_submit">
                            <button class="mb-20" type="submit">login</button>
                            <a href="{{ route('password.request') }}" class="d-none">Lost your password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection