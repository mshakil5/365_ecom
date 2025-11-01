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
                                  <li aria-current="page">About Us</li>
                              </ul>
                          </nav>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <section id="privacy" class="privacy section">
    <div class="container section-title" data-aos="fade-up">
      <h2>About Us</h2>
    </div>

    <div class="container" data-aos="fade-up">
      <div class="row">
        <div class="col-12">
          <div class="privacy-content">
            {!! $companyDetails->about_us !!}
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection