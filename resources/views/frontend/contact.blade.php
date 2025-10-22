@extends('frontend.master')

@section('content')
  <div class="page-title light-background">
    <div class="container">
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="current">Contact</li>
        </ol>
      </nav>
    </div>
  </div>

  <section id="contact" class="contact section">

    <div class="container" data-aos="fade">

      <div class="row gy-5 gx-lg-5">

        <div class="col-lg-4">

          <div class="info">
            <h3>{{ $contact->short_title }}</h3>
            <p>{!! $contact->short_description !!}</p>

            <div class="info-item d-flex">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h4>Location:</h4>
                <p>{{ $company->address1 }}</p>
              </div>
            </div>

            <div class="info-item d-flex">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h4>Email:</h4>
                <p>{{ $company->email1 }}</p>
              </div>
            </div>

            <div class="info-item d-flex">
              <i class="bi bi-phone flex-shrink-0"></i>
              <div>
                <h4>Call:</h4>
                <p>{{ $company->phone1 }}</p>
              </div>
            </div>

          </div>

        </div>

        <div class="col-lg-8">
          @if(session('success'))
              <div class="alert alert-success mt-3">{{ session('success') }}</div>
          @endif
          <form action="{{ route('contact.store') }}" method="POST" role="form" class="php-email-form">
              @csrf
              <div class="row">
                  <div class="col-md-6 form-group">
                      <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                            id="first_name" placeholder="First Name" value="{{ old('first_name') }}" required autofocus>
                      @error('first_name')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                  </div>
                  <div class="col-md-6 form-group mt-3 mt-md-0">
                      <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                            id="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required>
                      @error('last_name')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                  </div>
              </div>

              <div class="row mt-3">
                  <div class="col-md-6 form-group">
                      <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" placeholder="Your Email" value="{{ old('email') }}" required>
                      @error('email')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                  </div>

                  <div class="col-md-6 form-group">
                      <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            id="phone" placeholder="Phone" value="{{ old('phone') }}" required>
                      @error('phone')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                  </div>
              </div>

              <div class="form-group mt-3">
                  <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                        id="subject" placeholder="Subject" value="{{ old('subject') }}">
                  @error('subject')
                      <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
              </div>

              <div class="form-group mt-3">
                  <textarea name="message" class="form-control @error('message') is-invalid @enderror"
                            placeholder="Message" required rows="3">{{ old('message') }}</textarea>
                  @error('message')
                      <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
              </div>

            <div class="row mt-3 align-items-center">
                <div class="col-auto d-flex align-items-center gap-2">
                    <span id="captcha-question" class="fw-bold text-dark"></span>
                    <input type="number" id="captcha-answer" class="form-control form-control-sm" style="width: 80px;" placeholder="Answer" required>
                    <div id="captcha-error" class="text-danger d-none">Incorrect</div>
                </div>

                <div class="col text-center">
                    <button type="submit" id="submit-btn" class="btn btn-primary">Send</button>
                    <div id="sending-text" class="d-none">Sending...</div>
                </div>
            </div>
          </form>
        </div>

      </div>

    </div>

  </section>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        let num1 = Math.floor(Math.random() * 10) + 1;
        let num2 = Math.floor(Math.random() * 10) + 1;
        let correctAnswer = num1 + num2;
        $('#captcha-question').text(`What is ${num1} + ${num2}? *`);

        $('.php-email-form').on('submit', function(e) {
            let userAnswer = parseInt($('#captcha-answer').val());
            if(userAnswer !== correctAnswer) {
                e.preventDefault();
                $('#captcha-error').removeClass('d-none').text('Incorrect answer');
            } else {
                $('#captcha-error').addClass('d-none');
                $('#sending-text').removeClass('d-none');
            }
        });
    });
</script>
@endsection