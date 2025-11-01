@extends('frontend.pages.master')

@section('content')
    <div class="breadcrumb-section">
        <div class="breadcrumb-wrapper">
            <div class="container">
                <div class="row">
                    <div
                        class="col-12 d-flex justify-content-between justify-content-md-between  align-items-center flex-md-row flex-column">
                        <h3 class="breadcrumb-title"></h3>
                        <div class="breadcrumb-nav">
                            <nav aria-label="breadcrumb">
                                <ul>
                                    <li><a href="{{ route('home') }}">Home</a></li>
                                    <li aria-current="page">Terms & Conditions</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="terms" class="terms section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Terms & Conditions</h2>
        </div>

        <div class="container" data-aos="fade-up">
            <div class="row">
                <div class="col-12">
                    <div class="terms-content">
                        {!! $companyDetails->terms_and_conditions !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
