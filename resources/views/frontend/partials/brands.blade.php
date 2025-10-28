@php
    $brands = App\Models\Partner::where('status', 1)->latest()->get();
@endphp

<div class="shop-section mt-5">
    <div class="container">
        <div id="trusted-brands" class="card-grid subNavScroll">
            <div class="container">
                <div class="section-content d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <h3 class="section-title" data-aos="fade-up" data-aos-delay="0">Trusted by leading brands</h3>
                </div>

                <div class="grid-container gridSpacing-large eight d-flex flex-wrap justify-content-center gap-4 mt-4">
                    @foreach ($brands as $brand)
                        <div class="grid-card">
                            <div class="card">
                                <x-img 
                                    :path="'images/partners/' . ($brand->image ?? 'default.png')" 
                                    :alt="$brand->name ?? ''" 
                                    class="no-border" 
                                    width="150" 
                                    height="150"
                                />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>