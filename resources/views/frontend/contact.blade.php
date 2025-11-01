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

    <section id="contact" class="contact section">

        <div class="container" data-aos="fade">
            <div class="row gy-5 gx-lg-5">

                <div class="col-lg-4">
                    <div class="info p-4 border rounded shadow-sm bg-light">
                        <h3 class="mb-3">{{ $contact->short_title }}</h3>
                        <p class="mb-4">{!! $contact->short_description !!}</p>

                        <div class="info-item mb-3">
                            <h5 class="mb-1">Location:</h5>
                            <p class="mb-0">{{ $company->address1 }}</p>
                        </div>

                        <div class="info-item mb-3">
                            <h5 class="mb-1">Email:</h5>
                            <p class="mb-0">{{ $company->email1 }}</p>
                        </div>

                        <div class="info-item mb-3">
                            <h5 class="mb-1">Call:</h5>
                            <p class="mb-0">{{ $company->phone1 }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST"
                        class="php-email-form p-4 border rounded shadow-sm bg-light">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" name="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror" placeholder="First Name"
                                    value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <input type="text" name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror" placeholder="Last Name"
                                    value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Your Email"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror" placeholder="Phone"
                                    value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                placeholder="Subject" value="{{ old('subject') }}">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" placeholder="Message"
                                rows="5" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row align-items-center">
                            <div class="col-auto d-flex align-items-center gap-2 mb-3 mb-md-0">
                                <span id="captcha-question" class="fw-bold text-dark"></span>
                                <input type="number" id="captcha-answer" class="form-control form-control-sm"
                                    style="width: 80px;" placeholder="Answer" required>
                                <div id="captcha-error" class="text-danger d-none">Incorrect</div>
                            </div>

                            <div class="col text-center">
                                <button type="submit" id="submit-btn" class="btn btn-primary px-5">Send</button>
                                <div id="sending-text" class="d-none mt-2">Sending...</div>
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
                if (userAnswer !== correctAnswer) {
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