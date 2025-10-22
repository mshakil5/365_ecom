@extends('frontend.master')

@section('content')
  <div class="page-title light-background">
    <div class="container">
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="current">Privacy Policy</li>
        </ol>
      </nav>
    </div>
  </div>

  <section id="privacy" class="privacy section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Privacy Policy</h2>
    </div>

    <div class="container" data-aos="fade-up">
      <div class="row">
        <div class="col-12">
          <div class="privacy-content">
            {!! $companyPrivacy->privacy_policy !!}
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection