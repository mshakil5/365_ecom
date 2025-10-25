@extends('frontend.pages.master')

@section('content')

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --card-radius: 14px;
      --muted: #6c757d;
      --accent: #0d6efd;
      --shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
    }

    body {
      font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: #f6f8fb;
      color: #111827;
    }

    .page-header {
      padding: 36px 0 18px;
    }

    .shop-toolbar {
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:1rem;
      margin-bottom:18px;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 20px;
    }

    @media (max-width: 1200px) {
      .product-grid { grid-template-columns: repeat(4, 1fr); }
    }
    @media (max-width: 992px) {
      .product-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 720px) {
      .product-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 420px) {
      .product-grid { grid-template-columns: 1fr; }
    }

    .product-card {
      background: #fff;
      border-radius: var(--card-radius);
      padding: 12px;
      box-shadow: var(--shadow);
      transition: transform .18s ease, box-shadow .18s ease;
      display:flex;
      flex-direction:column;
      min-height: 320px;
    }
    .product-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 30px rgba(15,23,42,0.08);
    }

    .img-wrap {
      width:100%;
      aspect-ratio: 1/1;
      border-radius: 10px;
      overflow:hidden;
      display:flex;
      align-items:center;
      justify-content:center;
      background: linear-gradient(180deg, #fafbff 0%, #f5f7fb 100%);
      margin-bottom:10px;
      position:relative;
    }

    .img-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display:block;
    }

    .badge-top {
      position:absolute;
      top:10px;
      left:10px;
      padding:6px 8px;
      font-size:12px;
      border-radius:8px;
      background: rgba(13,110,253,0.08);
      color: var(--accent);
      font-weight:600;
      backdrop-filter: blur(2px);
    }

    .product-title {
      font-weight:600;
      font-size: 0.95rem;
      margin-bottom:6px;
      color:#0f1724;
    }

    .price-row {
      display:flex;
      align-items:center;
      gap:8px;
      margin-bottom:8px;
    }

    .price-current {
      font-weight:700;
      font-size:1.05rem;
    }
    .price-old {
      color:var(--muted);
      text-decoration:line-through;
      font-size:0.9rem;
    }

    .swatches {
      display:flex;
      gap:8px;
      align-items:center;
      margin-top:auto;
    }
    .swatch {
      width:18px;
      height:18px;
      border-radius:50%;
      border:2px solid #fff;
      box-shadow: 0 1px 0 rgba(0,0,0,0.06);
      cursor:pointer;
      transition: transform .12s;
      display:inline-block;
    }
    .swatch:hover { transform: scale(1.12); }

    .meta-row {
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:8px;
      margin-top:10px;
    }

    .actions {
      display:flex;
      gap:8px;
      align-items:center;
    }
    .btn-ghost {
      border: 1px solid #e6e9ef;
      background: transparent;
      padding:6px 9px;
      border-radius:8px;
      font-size:0.92rem;
    }

    .rating {
      display:flex;
      align-items:center;
      gap:6px;
      color: #f59e0b;
      font-weight:600;
      font-size:0.9rem;
    }

    .topbar-select {
      min-width: 220px;
    }

    .muted { color: var(--muted); font-size:0.9rem; }
    .flex-gap { display:flex; gap:10px; align-items:center; }
  </style>

  <div class="shop-section mt-5">
      <div class="container">
          <h3 class="section-title mb-4">Products</h3>

          <div class="row" id="productGrid"></div>

          <div class="text-center my-3">
              <button id="see-more-btn" class="btn btn-success" data-page="1">See More</button>
          </div>
      </div>
  </div>

@endsection

@section('script')
<script>
  $(document).ready(function(){
      const colors = ['#0d6efd', '#000000', '#f97316', '#198754', '#6f42c1', '#fd7e14'];

      function loadProducts(){
          $.ajax({
              url: "{{ route('products.latest') }}",
              success: function(res){
                  res.products.forEach(product => {
                      let swatchesHtml = '';
                      let shuffledColors = [...colors].sort(() => 0.5 - Math.random()).slice(0, 3);
                      shuffledColors.forEach(color => {
                          swatchesHtml += `<div class="swatch" style="background:${color}" title="${color}"></div>`;
                      });

                      let html = `
                      <div class="col-xl-3 col-lg-4 col-sm-6 col-12 mb-4">
                          <article class="product-card border-around p-3" data-product-id="${product.id}">
                              <div class="img-wrap position-relative mb-2">
                                  ${product.is_new ? '<span class="badge-top position-absolute top-0 start-0 bg-success text-white px-2 py-1">New</span>' : ''}
                                  <img src="${product.image}" alt="${product.product_name_api}" class="img-fluid" style="height:230px; object-fit:cover;">
                              </div>
                              <div class="product-title fw-bold mb-1">
                                  <a href="/product/${product.id}" class="text-dark text-decoration-none">${product.product_name_api}</a>
                              </div>
                              <div class="price-row d-flex gap-2 align-items-center mb-2">
                                  <div class="price-current fw-bold">£${parseFloat(product.price_single).toFixed(2)}</div>
                                  ${product.del_price && product.del_price > product.price_single ? `<div class="price-old text-muted text-decoration-line-through">£${parseFloat(product.del_price).toFixed(2)}</div>` : ''}
                              </div>
                              <div class="muted mb-2">${product.short_desc || ''}</div>
                              <div class="meta-row d-flex justify-content-between align-items-center mb-2">
                                  <div class="rating text-warning"><i class="bi bi-star-fill"></i> ${product.rating || 4.5}</div>
                                  <div class="actions d-flex gap-1">
                                      <button class="btn btn-sm btn-primary">Add</button>
                                      <button class="btn btn-sm btn-outline-secondary" title="Wishlist"><i class="bi bi-heart"></i></button>
                                  </div>
                              </div>
                              <div class="swatches d-flex gap-1" aria-label="Available colors">
                                  ${swatchesHtml}
                              </div>
                          </article>
                      </div>`;
                      $('#productGrid').append(html);
                  });
              }
          });
      }

      loadProducts();

      $('#see-more-btn').click(function(){
          loadProducts();
      });
  });
</script>
@endsection