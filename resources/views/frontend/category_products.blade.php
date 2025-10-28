@extends('frontend.pages.master')

@section('content')

<div class="shop-section mt-5">
    <div class="container">
        <h3 class="section-title mb-4">{{ $category->name }}</h3>

        <div class="row">
            @foreach ($products as $product)
                @include('frontend.partials.single_product', ['product' => $product])
            @endforeach
        </div>
    </div>
</div>


@endsection