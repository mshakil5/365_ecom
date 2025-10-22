@extends('frontend.master')

@section('content')
  <div class="page-title light-background">
    <div class="container">
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="current">Frequently Asked Questions</li>
        </ol>
      </nav>
    </div>
  </div>
  <section id="faq" class="faq section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Frequently Asked Questions</h2>
    </div>
    <div class="container" data-aos="fade-up">
      <div class="row">
        <div class="col-12">
          <div class="custom-accordion" id="accordion-faq">
            @foreach($faqs as $index => $faq)
              <div class="accordion-item">
                <h2 class="mb-0">
                  <button class="btn btn-link {{ $index > 0 ? 'collapsed' : '' }}" 
                          type="button" 
                          data-bs-toggle="collapse" 
                          data-bs-target="#collapse-faq-{{ $faq->id }}"
                          aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                    {{ $faq->question }}
                  </button>
                </h2>

                <div id="collapse-faq-{{ $faq->id }}" 
                    class="collapse {{ $index == 0 ? 'show' : '' }}" 
                    data-bs-parent="#accordion-faq">
                  <div class="accordion-body">
                    {!! $faq->answer !!}
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    </div>
  </section>
@endsection