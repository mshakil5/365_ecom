@extends('frontend.master')

@section('content')
  <div class="page-title light-background">
    <div class="container">
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="current">Terms & Conditions</li>
        </ol>
      </nav>
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