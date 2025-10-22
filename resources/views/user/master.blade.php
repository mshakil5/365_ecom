@extends('frontend.master')

@section('content')
<section class="contact section">
    <div class="container" data-aos="fade">
        <div class="row gy-5 gx-lg-5">
            <div class="col-lg-3">
                @include('user.sidebar')
            </div>
            <div class="col-lg-9">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        @yield('user-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection