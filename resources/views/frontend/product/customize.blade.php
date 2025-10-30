@extends('frontend.pages.master')

@section('content')
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --black: #0b0b0b;
            --red: #dc2026;
            --white: #ffffff;
            --muted: #8a8a8a;
            --panel: #111217;
        }

        html,
        body {
            height: 100%
        }

        body {
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
            background: var(--white);
            color: #222;
            -webkit-font-smoothing: antialiased;
        }

        .top-strip {
            background: var(--black);
            color: var(--white);
            padding: 6px 0;
            font-size: 14px;
        }

        .top-strip .item {
            display: flex;
            align-items: center;
            gap: .6rem;
            justify-content: center;
        }

        .top-strip i {
            color: var(--red);
        }

        .progress-wrapper {
            background: #fff;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .checkout-progress {
            display: flex;
            gap: 24px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }

        .stage {
            text-align: center;
            color: #333;
            width: 190px;
        }

        .stage .circle {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #111;
            color: #fff;
            border: 3px solid #222;
            margin: 0 auto 8px;
        }

        .stage.active .circle {
            background: var(--red);
            border-color: var(--red);
        }

        .stage.complete .circle {
            background: #198754;
            border-color: #198754;
        }

        .main {
            padding: 22px 0;
        }

        .card-panel {
            border-radius: 10px;
            background: #fff;
            border: 1px solid #e9e9e9;
            padding: 14px;
        }

        .card-panel-dark {
            border-radius: 10px;
            background: var(--panel);
            color: var(--white);
            padding: 14px;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .preview-shell {
            background: #f7f7f7;
            border-radius: 10px;
            padding: 12px;
            min-height: 520px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .preview-canvas {
            width: 320px;
            height: 420px;
            position: relative;
            border-radius: 10px;
            background-size: cover;
            background-position: center;
            box-shadow: 0 18px 40px rgba(13, 20, 30, 0.08);
            transform-origin: center center;
        }

        .layer-item {
            position: absolute;
            transform-origin: center center;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: auto;
            cursor: grab;
            background: transparent;
            overflow: visible;
        }

        .layer-item img {
            max-width: 100%;
            max-height: 100%;
            display: block;
            user-select: none;
            -webkit-user-drag: none;
        }

        .layer-text {
            white-space: nowrap;
            display: inline-block;
            padding: 2px 4px;
        }

        .option-card {
            border-radius: 8px;
            border: 1px solid #ececec;
            padding: 10px;
            cursor: pointer;
            transition: all .12s ease;
            background: #fff;
        }

        .option-card.active {
            border-color: var(--red);
            box-shadow: 0 8px 24px rgba(220, 32, 38, 0.08);
            transform: translateY(-4px);
        }

        .small-muted {
            color: var(--muted);
            font-size: 13px;
        }

        .toolbar {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .zoom-controls {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .loader-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(11, 11, 11, 0.6);
            color: #fff;
            z-index: 999;
            display: none;
            flex-direction: column;
            gap: 10px;
            border-radius: 10px;
        }

        .inspector {
            margin-top: 12px;
            background: #fff;
            border-radius: 8px;
            padding: 10px;
            border: 1px solid #eee;
        }

        .inspector h6 {
            margin: 0 0 8px 0;
            font-size: 14px;
        }

        @media (max-width:991px) {
            .preview-shell {
                min-height: 420px;
            }

            .preview-canvas {
                width: 260px;
                height: 360px;
            }
        }

        @media (max-width:575px) {
            .preview-shell {
                min-height: 360px;
                padding: 8px;
            }

            .preview-canvas {
                width: 220px;
                height: 300px;
            }

            .stage {
                width: 130px;
                font-size: 12px;
            }
        }

        .btn-red {
            background: var(--red);
            color: #fff;
            border: none;
        }

        .btn-red:hover {
            background: #b51f31;
        }

        .layer-item.selected {
            outline: 3px dashed rgba(220, 32, 38, 0.45);
        }

        .list-group-item.active {
            background: linear-gradient(90deg, rgba(220, 32, 38, 0.08), rgba(220, 32, 38, 0.02));
            border-color: #f0c0c2;
        }

        .view-btns .btn {
            min-width: 76px;
        }
    </style>

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
                                    <li aria-current="page">Product Customizer</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="main">
        <div class="container">
            <div class="row">

                <div class="col-lg-8">
                    <div class="card-panel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Product Customiser</h5>
                            <div class="toolbar"></div>
                        </div>

                        <div id="panels">
                            <div class="mb-3">
                                <h6>1. Choose product</h6>
                                <div class="row g-2">
                                    <!-- product data now contains view-specific images -->
                                    <div class="col-12 col-md-12">
                                        <div class="option-card product-option active"
                                            data-product='@json($dataProduct)'>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <img src="{{ $dataProduct['image'] }}" alt="{{ $dataProduct['name'] }}"
                                                        class="img-fluid"
                                                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $dataProduct['name'] }}</div>
                                                    <div class="small-muted">From £{{ number_format($dataProduct['price'] ?? $dataProduct['price'] ?? 0, 2) }}</div>
                                                    <div class="small-muted">Qty: {{ $dataProduct['quantity'] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <h6>2. Print method</h6>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="option-card method-option active"
                                            data-method='{"id":"print","label":"Print","maxWidthCm":30,"setup":5.99}'>
                                            <div class="d-flex align-items-start">
                                                <div class="me-2"><i class="fa-solid fa-print fa-2x"
                                                        style="color:var(--red)"></i></div>
                                                <div>
                                                    <div class="fw-bold">Print</div>
                                                    <div class="small-muted">Max Width: 30cm · Setup £5.99</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="option-card method-option"
                                            data-method='{"id":"embroidery","label":"Embroidery","maxWidthCm":25,"setup":9.99}'>
                                            <div class="d-flex align-items-start">
                                                <div class="me-2"><i class="fa-solid fa-pen-nib fa-2x"
                                                        style="color:var(--red)"></i></div>
                                                <div>
                                                    <div class="fw-bold">Embroidery</div>
                                                    <div class="small-muted">Max Width: 25cm · Setup £9.99</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <h6>3. Position Guideline</h6>

                                <div class="accordion" id="positionAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#positionCollapse">
                                                Select Position
                                            </button>
                                        </h2>
                                        <div id="positionCollapse" class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <!-- Position buttons -->
                                                <div class="row g-2 mb-3">
                                                    @foreach ($guidelines as $guideline)
                                                        <div class="col-6 col-md-4 col-lg-3 mb-2">
                                                            <button class="btn btn-outline-dark w-100 pos-btn"
                                                                data-pos="{{ $guideline->position }}"
                                                                data-image="{{ asset('images/guidelines/' . $guideline->image) }}">
                                                                {{ $guideline->position }}
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <!-- Position image -->
                                                <div class="text-center">
                                                    <img id="positionImage" src="" class="img-fluid"
                                                        style="display: none;">
                                                    <div id="positionPlaceholder" class="text-muted">
                                                        Click on a position to see guidelines
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <h6>4. Upload / Add layers</h6>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Add image(s)</label>
                                        <input class="form-control" id="addImagesInput" type="file" accept="image/*"
                                            multiple>
                                        <div class="small-muted mt-1">Supported: PNG, JPG. Max per file: 6 MB.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Add text</label>
                                        <input class="form-control" id="addTextInput"
                                            placeholder="Enter text (press Add)" />
                                        <div class="d-flex gap-2 mt-2 align-items-center">
                                            <select id="fontFamily" class="form-select form-select-sm"
                                                style="max-width:170px">
                                                <option value="Inter,system-ui">Inter</option>
                                                <option value="Montserrat,system-ui">Montserrat</option>
                                                <option value="Georgia,serif">Georgia</option>
                                                <option value="Impact,sans-serif">Impact</option>
                                                <option value="Courier New,monospace">Courier New</option>
                                                <option value="Verdana,sans-serif">Verdana</option>
                                            </select>

                                            <div class="input-group input-group-sm" style="max-width:140px">
                                                <input id="fontSizeInput" type="number" class="form-control"
                                                    value="28" min="8" max="200">
                                                <span class="input-group-text">px</span>
                                            </div>

                                            <div class="btn-group" role="group" aria-label="Font styles">
                                                <button id="boldToggle" class="btn btn-outline-secondary btn-sm"
                                                    title="Bold"><i class="fa-solid fa-bold"></i></button>
                                                <button id="italicToggle" class="btn btn-outline-secondary btn-sm"
                                                    title="Italic"><i class="fa-solid fa-italic"></i></button>
                                                <button id="underlineToggle" class="btn btn-outline-secondary btn-sm"
                                                    title="Underline"><i class="fa-solid fa-underline"></i></button>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center mt-2">
                                            <input id="textColorInput" type="color" value="#000000"
                                                title="Text color">
                                            <div class="d-flex gap-1 ms-2">
                                                <button class="btn btn-sm btn-outline-dark color-swatch"
                                                    data-color="#000000" style="background:#000;color:#fff"></button>
                                                <button class="btn btn-sm btn-outline-dark color-swatch"
                                                    data-color="#ffffff" style="background:#fff"></button>
                                                <button class="btn btn-sm btn-outline-dark color-swatch"
                                                    data-color="#dc2026" style="background:var(--red)"></button>
                                                <button class="btn btn-sm btn-outline-dark color-swatch"
                                                    data-color="#1b9dd9" style="background:#1b9dd9"></button>
                                            </div>
                                            <button id="addTextBtn" class="btn btn-red btn-sm ms-auto">Add Text</button>
                                        </div>

                                        <div class="small-muted mt-2">You can add multiple images and text layers. Use the
                                            Layers panel below to edit/delete specific layers.</div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <h6>5. Layers (current view)</h6>
                                <div id="layersList" class="list-group mb-2" style="max-height:220px; overflow:auto;">
                                </div>
                                <div class="d-flex gap-2">
                                    <button id="deleteLayerBtn" class="btn btn-outline-danger btn-sm ms-auto"
                                        title="Delete selected layer"><i class="bi bi-trash"></i> Delete</button>
                                </div>
                                <div class="small-muted mt-2">Select a layer from the list to edit or delete it.</div>
                            </div>
                            <input type="hidden" id="customizationData" name="customization_data">

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card-panel-dark position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong class="text-white">Live Preview</strong>
                                <div class="small-muted text-white-50">Choose view & layers</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <div class="d-flex gap-2 align-items-center">
                                <div class="view-btns btn-group btn-group-sm me-2" role="group" aria-label="Views">
                                    <button class="btn btn-outline-light" data-view="front">Front</button>
                                    <button class="btn btn-outline-light" data-view="back">Back</button>
                                    <button class="btn btn-outline-light" data-view="left">Left</button>
                                    <button class="btn btn-outline-light" data-view="right">Right</button>
                                </div>

                            </div>
                        </div>


                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <div class="d-flex gap-2 align-items-center">
                                <div class="zoom-controls">
                                    <button id="zoomOut" class="btn btn-sm btn-outline-light" title="Zoom out"><i
                                            class="bi bi-zoom-out"></i></button>
                                    <div class="input-group input-group-sm" style="width:72px;">
                                        <input id="zoomLevel" type="text" class="form-control text-center"
                                            value="100%" readonly>
                                    </div>
                                    <button id="zoomIn" class="btn btn-sm btn-outline-light" title="Zoom in"><i
                                            class="bi bi-zoom-in"></i></button>
                                </div>

                                <div class="ms-2">
                                    <button id="downloadPngBtn" class="btn btn-sm btn-light" title="Download PNG"><i
                                            class="bi bi-download"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="preview-shell" id="previewShell" tabindex="0" aria-label="Design preview area">
                            <div class="preview-canvas" id="previewCanvas" role="img" aria-label="Product preview">
                                <div class="loader-overlay" id="loaderOverlay">
                                    <div class="spinner-border text-light" role="status"></div>
                                    <div>Rendering preview...</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3" id="previewMeta">
                            <div class="small-muted">Product: <span id="currentProductName"
                                    class="fw-bold">T-Shirt</span></div>
                            <div class="small-muted">Method: <span id="currentMethod" class="fw-bold">Print</span></div>
                            <div class="small-muted">View: <span id="currentViewLabel" class="fw-bold">Front</span></div>
                        </div>

                        <hr class="mt-2 mb-2">

                        <div id="inspector" class="inspector" style="display:none">
                            <h6>Layer inspector</h6>
                            <div id="inspectorContent"></div>
                        </div>

                        <div class="d-flex gap-2 mt-2">
                            <div class="mt-2 text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="small-muted">Quantity: <span class="fw-bold">{{ $dataProduct['quantity'] ?? 1 }}</span></div>
                                    <div class="small-muted">Total: <span id="totalPrice" class="fw-bold">£0.00</span></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script>
        const PRODUCT_QTY = {{ $dataProduct['quantity'] ?? 0 }};
        const PRODUCT_BASE_PRICE = {{ $dataProduct['price'] ?? 0 }};
        const PRINT_SETUP_COST = 5.99;
        const EMBROIDERY_SETUP_COST = 9.99;
    </script>

    <script>
        class ProductCustomiserSlimViews {
            constructor() {
                this.previewCanvas = document.getElementById('previewCanvas');
                this.layersList = document.getElementById('layersList');
                this.loader = document.getElementById('loaderOverlay');
                this.inspector = document.getElementById('inspector');
                this.inspectorContent = document.getElementById('inspectorContent');
                this.selectedLayerId = null;
                this.currentView = 'front';

                const firstProductEl = document.querySelector('.product-option[data-product]');
                const defaultProduct = firstProductEl ? JSON.parse(firstProductEl.getAttribute('data-product')) : {
                    id: 'tshirt',
                    name: 'T-Shirt',
                    img: {
                        front: '',
                        back: '',
                        left: '',
                        right: ''
                    },
                    baseWidthCm: 30
                };

                this.state = {
                    product: defaultProduct,
                    method: {
                        id: 'print',
                        label: 'Print',
                        maxWidthCm: 30,
                        setup: 5.99
                    },
                    position: 'centre-chest',
                    layers: [],
                    zoom: 1,
                    quantity: 1,
                };

                this.pricing = {
                    basePrice: PRODUCT_BASE_PRICE,
                    quantity: PRODUCT_QTY,
                    printSetup: PRINT_SETUP_COST,
                    embroiderySetup: EMBROIDERY_SETUP_COST
                };

                this.customizationData = [];

                this.updateHiddenField(); 

                // apply default view background
                this.applyViewBackground();

                this.initUI();
                this.render();
            }

            updateHiddenField() {
                this.cleanupArray();
                const dataString = JSON.stringify(this.customizationData);
                document.getElementById('customizationData').value = dataString;
                console.log('Customization Data (Array):', this.customizationData);
            }

          recalcPrice() {
              const basePrice = this.pricing.basePrice;
              const quantity = this.pricing.quantity;
              
              // Count layers by method type
              let printLayersCount = 0;
              let embroideryLayersCount = 0;
              
              this.customizationData.forEach(layer => {
                  if (layer.method === 'print') {
                      printLayersCount++;
                  } else if (layer.method === 'embroidery') {
                      embroideryLayersCount++;
                  }
              });
              
              // Calculate total: 
              // (base price × quantity) + (print setup × print layers × quantity) + (embroidery setup × embroidery layers × quantity)
              const baseTotal = basePrice * quantity;
              const printTotal = this.pricing.printSetup * printLayersCount * quantity;
              const embroideryTotal = this.pricing.embroiderySetup * embroideryLayersCount * quantity;
              
              const total = baseTotal + printTotal + embroideryTotal;
              
              document.getElementById('totalPrice').textContent = '£' + total.toFixed(2);
              
              console.log('Price Calculation:', {
                  basePrice,
                  quantity,
                  printLayersCount,
                  embroideryLayersCount,
                  baseTotal,
                  printTotal,
                  embroideryTotal,
                  total
              });
          }

            cleanupArray() {
                Object.keys(this.customizationData).forEach(key => {
                    if (key !== 'length' && isNaN(parseInt(key))) {
                        delete this.customizationData[key];
                    }
                });
            }

            initUI() {
                this.bindProductOptions();
                this.bindMethodOptions();
                this.bindPositionButtons();
                this.bindAddImage();
                this.bindAddText();
                this.bindLayerControls();
                this.bindZoomControls();
                this.bindDownloadPng();
                this.bindQtyAndPricing();
                this.initTooltips();
                this.initLayerDragHandlers();
                this.bindViewButtons();

                $('#boldToggle,#italicToggle,#underlineToggle').on('click', function() {
                    $(this).toggleClass('active');
                });
                $('#previewShell').on('click', function() {
                    $(this).focus();
                });
            }

            bindViewButtons() {
                const btns = document.querySelectorAll('.view-btns [data-view]');
                btns.forEach(b => {
                    b.addEventListener('click', () => {
                        btns.forEach(x => x.classList.remove('active'));
                        b.classList.add('active');
                        this.currentView = b.getAttribute('data-view');
                        document.getElementById('currentViewLabel').textContent = this.capitalize(this
                            .currentView);
                        this.applyViewBackground();
                        this.render();
                    });
                });
                // set default active
                const defaultBtn = document.querySelector('.view-btns [data-view="front"]');
                if (defaultBtn) defaultBtn.classList.add('active');
            }

            applyViewBackground() {
                const imgObj = this.state.product.img || {};
                const url = (imgObj[this.currentView] || '');
                if (url) this.previewCanvas.style.backgroundImage = `url('${url}')`;
                else this.previewCanvas.style.backgroundImage = "";
            }

            bindProductOptions() {
                document.querySelectorAll('.product-option').forEach(el => {
                    el.addEventListener('click', () => {
                        document.querySelectorAll('.product-option').forEach(x => x.classList.remove(
                            'active'));
                        el.classList.add('active');
                        const p = JSON.parse(el.getAttribute('data-product'));
                        this.state.product = p;
                        this.applyViewBackground();
                        document.getElementById('currentProductName').textContent = p.name;
                        this.render();
                        this.recalcPrice();
                    });
                });
            }

            bindMethodOptions() {
                document.querySelectorAll('.method-option').forEach(el => {
                    el.addEventListener('click', () => {
                        document.querySelectorAll('.method-option').forEach(x => x.classList.remove(
                            'active'));
                        el.classList.add('active');
                        const m = JSON.parse(el.getAttribute('data-method'));
                        this.state.method = m;
                        // this.customizationData.forEach(layer => {
                        //     layer.method = m.id;
                        // });

                        this.state.layers
                        .filter(layer => layer.view === this.currentView)
                        .forEach(layer => {
                            const dataIndex = this.customizationData.findIndex(l => l.layerId === layer.id);
                            if (dataIndex !== -1) {
                                this.customizationData[dataIndex].method = m.id;
                            }
                        });
                        document.getElementById('currentMethod').textContent = m.label;
                        this.recalcPrice();
                        this.updateHiddenField();
                    });
                });
            }

            bindPositionButtons() {
                const btns = document.querySelectorAll('.pos-btn');
                btns.forEach(b => {
                    b.addEventListener('click', () => {
                        btns.forEach(x => x.classList.remove('active'));
                        b.classList.add('active');
                        this.state.position = b.getAttribute('data-pos');
                        // this.customizationData.position = b.getAttribute('data-pos');
                        this.updateHiddenField();
                    });
                });
            }

            bindAddImage() {
                const input = document.getElementById('addImagesInput');
                input.addEventListener('change', (e) => {
                    const files = Array.from(e.target.files || []);
                    files.forEach(file => {
                        if (!file.type.startsWith('image/')) return;
                        if (file.size > 6 * 1024 * 1024) {
                            alert('File too large (max 6MB)');
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = (ev) => {
                            const src = ev.target.result;
                            const approxWidth = Math.round(this.previewCanvas.clientWidth * 0.5);
                            this.addLayer({
                                type: 'image',
                                src,
                                pos: this.state.position,
                                widthPx: approxWidth,
                                heightPx: null,
                                rotate: 0,
                                opacity: 1,
                                borderRadiusPx: 0,
                                bgColor: 'transparent',
                                draggable: true,
                                editable: true,
                                view: this.currentView
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                    input.value = '';
                });
            }

            bindAddText() {
                document.getElementById('addTextBtn').addEventListener('click', () => {
                    const text = document.getElementById('addTextInput').value.trim();
                    if (!text) {
                        alert('Enter text first');
                        return;
                    }
                    const fontFamily = document.getElementById('fontFamily').value;
                    const fontSize = Math.max(8, parseInt(document.getElementById('fontSizeInput').value ||
                    28));
                    const bold = document.getElementById('boldToggle').classList.contains('active');
                    const italic = document.getElementById('italicToggle').classList.contains('active');
                    const underline = document.getElementById('underlineToggle').classList.contains('active');
                    const color = document.getElementById('textColorInput').value || '#000';
                    this.addLayer({
                        type: 'text',
                        text,
                        pos: this.state.position,
                        fontFamily,
                        fontSize,
                        bold,
                        italic,
                        underline,
                        color,
                        opacity: 1,
                        widthPx: null,
                        bgColor: 'transparent',
                        rotate: 0,
                        draggable: true,
                        editable: true,
                        view: this.currentView
                    });
                    document.getElementById('addTextInput').value = '';
                });
            }

            bindLayerControls() {
                this.layersList.addEventListener('click', (e) => {
                    const li = e.target.closest('li');
                    if (!li) return;
                    this.selectLayer(li.getAttribute('data-id'));
                });

                document.getElementById('deleteLayerBtn').addEventListener('click', () => {
                    const id = this.selectedLayerId;
                    if (!id) return alert('Select a layer to delete');
                    if (!confirm('Delete selected layer?')) return;
                    this.removeLayer(id);
                });
            }

            bindZoomControls() {
                document.getElementById('zoomIn').addEventListener('click', () => this.setZoom(this.state.zoom + 0.1));
                document.getElementById('zoomOut').addEventListener('click', () => this.setZoom(Math.max(0.3, this.state
                    .zoom - 0.1)));
                document.getElementById('previewShell').addEventListener('wheel', (e) => {
                    if (e.ctrlKey) {
                        e.preventDefault();
                        const delta = e.deltaY > 0 ? -0.05 : 0.05;
                        this.setZoom(Math.min(2.5, Math.max(0.3, this.state.zoom + delta)));
                    }
                }, {
                    passive: false
                });
            }

            bindDownloadPng() {
                document.getElementById('downloadPngBtn').addEventListener('click', () => this.exportPNG());
            }

            bindQtyAndPricing() {
                this.recalcPrice();
            }

            initTooltips() {
                const tipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
                tipTriggerList.forEach((el) => new bootstrap.Tooltip(el, {
                    container: 'body'
                }));
            }

            addLayer(opts) {
                const id = 'ly_' + Math.random().toString(36).slice(2, 9);
                const layer = Object.assign({
                    id,
                    type: 'image',
                    pos: this.state.position,
                    widthPx: 120,
                    heightPx: null,
                    rotate: 0,
                    opacity: 1,
                    borderRadiusPx: 0,
                    bgColor: 'transparent',
                    zIndex: (this.state.layers.length ? Math.max(...this.state.layers.map(l => l.zIndex || 0)) :
                        0) + 1,
                    leftPct: 50,
                    topPct: 34,
                    draggable: true,
                    editable: true,
                    view: this.currentView
                }, opts);
                this.state.layers.push(layer);

                const layerObject = {
                    productId: this.state.product.id,
                    method: this.state.method.id,
                    position: this.state.position,
                    type: layer.type,
                    data: layer.type === 'text' ? {
                        text: layer.text,
                        fontFamily: layer.fontFamily,
                        fontSize: layer.fontSize,
                        color: layer.color,
                        bold: layer.bold,
                        italic: layer.italic,
                        underline: layer.underline
                    } : {
                        src: layer.src,
                        width: layer.widthPx,
                        height: layer.heightPx
                    },
                    zIndex: layer.zIndex,
                    layerId: layer.id
                };

                this.customizationData.push(layerObject);

                this.render();
                this.selectLayer(layer.id);
                this.updateHiddenField();
                this.recalcPrice();

                const newLayerIndex = this.customizationData.findIndex(l => l.layerId === layer.id);
                if (newLayerIndex !== -1) {
                    this.customizationData[newLayerIndex].method = this.state.method.id;
                }
            }

            removeLayer(id) {
                this.state.layers = this.state.layers.filter(l => l.id !== id);

                this.customizationData = this.customizationData.filter(l => l.layerId !== id);

                if (this.selectedLayerId === id) this.selectedLayerId = null;
                this.hideInspector();
                this.render();
                this.updateHiddenField();
                this.recalcPrice();
            }

            selectLayer(id) {
                this.selectedLayerId = id;
                Array.from(this.layersList.children).forEach(li => li.classList.toggle('active', li.getAttribute(
                    'data-id') === id));
                Array.from(this.previewCanvas.querySelectorAll('.layer-item')).forEach(n => n.classList.toggle(
                    'selected', n.dataset.id === id));
                this.showInspectorFor(id);
            }

            showInspectorFor(id) {
                const layer = this.state.layers.find(l => l.id === id);
                if (!layer) {
                    this.hideInspector();
                    return;
                }
                this.inspector.style.display = 'block';
                this.inspectorContent.innerHTML = ''; // build UI
                // Common header
                const header = document.createElement('div');
                header.innerHTML = `<div class="d-flex align-items-center justify-content-between mb-2">
        <div><strong>${layer.type === 'image' ? 'Image layer' : 'Text layer'}</strong><div class="small-muted">id: ${layer.id}</div></div>
        <div><small class="text-muted">view: ${this.capitalize(layer.view)}</small></div>
      </div>`;
                this.inspectorContent.appendChild(header);

                // Editable toggles (lock draggable / lock edit)
                const toggles = document.createElement('div');
                toggles.className = 'mb-2 d-flex gap-2';
                toggles.innerHTML = `
        <div class="form-check form-switch">
          <input class="form-check-input" id="insLockDrag" type="checkbox" ${!layer.draggable ? 'checked' : ''}>
          <label class="form-check-label small-muted" for="insLockDrag">Lock position</label>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" id="insLockEdit" type="checkbox" ${!layer.editable ? 'checked' : ''}>
          <label class="form-check-label small-muted" for="insLockEdit">Lock edit</label>
        </div>`;
                this.inspectorContent.appendChild(toggles);

                // wire toggles
                toggles.querySelector('#insLockDrag').addEventListener('change', (e) => {
                    layer.draggable = !e.target.checked; // checked means locked -> draggable=false
                });
                toggles.querySelector('#insLockEdit').addEventListener('change', (e) => {
                    layer.editable = !e.target.checked;
                    // disable edit/remove buttons in layer list for this layer
                    this.render();
                });

                if (layer.type === 'image') {
                    // image controls: width, height, border-radius, bg color, opacity, rotate
                    const html = document.createElement('div');
                    html.innerHTML = `
          <div class="mb-2">
            <label class="form-label small-muted">Width (px)</label>
            <input id="insWidth" type="number" class="form-control form-control-sm" value="${layer.widthPx || ''}" min="20">
          </div>
          <div class="mb-2">
            <label class="form-label small-muted">Height (px) — leave blank to keep aspect</label>
            <input id="insHeight" type="number" class="form-control form-control-sm" value="${layer.heightPx || ''}" min="10">
          </div>
          <div class="mb-2">
            <label class="form-label small-muted">Border radius (px)</label>
            <input id="insRadius" type="number" class="form-control form-control-sm" value="${layer.borderRadiusPx || 0}" min="0">
          </div>
          <div class="mb-2">
            <label class="form-label small-muted">Background color (under image)</label>
            <input id="insBgColor" type="color" class="form-control form-control-sm" value="${layer.bgColor && layer.bgColor !== 'transparent' ? layer.bgColor : '#ffffff'}">
            <div class="small-muted mt-1">Choose transparent color by setting to white and then selecting transparency in preview is not supported — use 'transparent' in code if needed.</div>
          </div>
          <div class="mb-2">
            <label class="form-label small-muted">Opacity</label>
            <input id="insOpacity" type="range" min="0" max="1" step="0.05" value="${layer.opacity}">
            <div class="small-muted">Value: <span id="insOpacityVal">${layer.opacity}</span></div>
          </div>
          <div class="mb-2">
            <label class="form-label small-muted">Rotate (degrees)</label>
            <input id="insRotate" type="number" class="form-control form-control-sm" value="${layer.rotate || 0}" step="1">
          </div>
          <div class="d-flex gap-2">
            <button id="insApply" class="btn btn-sm btn-primary">Apply</button>
            <button id="insDelete" class="btn btn-sm btn-outline-danger">Delete</button>
          </div>
        `;
                    this.inspectorContent.appendChild(html);

                    // wire controls
                    html.querySelector('#insOpacity').addEventListener('input', (e) => {
                        html.querySelector('#insOpacityVal').textContent = e.target.value;
                    });

                    html.querySelector('#insApply').addEventListener('click', () => {
                        const w = parseInt(html.querySelector('#insWidth').value) || null;
                        const h = parseInt(html.querySelector('#insHeight').value) || null;
                        layer.widthPx = w;
                        layer.heightPx = h;
                        layer.borderRadiusPx = parseInt(html.querySelector('#insRadius').value) || 0;
                        const bgc = html.querySelector('#insBgColor').value || 'transparent';
                        layer.bgColor = bgc;
                        layer.opacity = parseFloat(html.querySelector('#insOpacity').value) || 1;
                        layer.rotate = parseFloat(html.querySelector('#insRotate').value) || 0;

                        const layerData = this.customizationData.find(l => l.layerId === layer.id);
                        if (layerData && layerData.type === 'image') {
                            layerData.data.width = w;
                            layerData.data.height = h;
                            // layerData.method = this.state.method.id;
                            // layerData.position = this.state.position;
                        }

                        this.render();

                        this.updateHiddenField();
                    });

                    html.querySelector('#insDelete').addEventListener('click', () => {
                        if (!confirm('Delete this layer?')) return;
                        this.removeLayer(layer.id);
                    });

                } else if (layer.type === 'text') {
                    const html = document.createElement('div');
                    html.innerHTML = `
          <div class="mb-2">
            <label class="form-label small-muted">Text</label>
            <input id="insText" type="text" class="form-control form-control-sm" value="${this.escapeHtml(layer.text || '')}">
          </div>
          <div class="mb-2">
            <label class="form-label small-muted">Font family</label>
            <input id="insFont" type="text" class="form-control form-control-sm" value="${layer.fontFamily || 'Inter, system-ui'}">
          </div>
          <div class="mb-2">
            <label class="form-label small-muted">Font size (px)</label>
            <input id="insFontSize" type="number" class="form-control form-control-sm" value="${layer.fontSize || 28}" min="6">
          </div>
          <div class="mb-2 d-flex gap-2">
            <div class="btn-group btn-group-sm" role="group" aria-label="styles">
              <button id="insBold" class="btn btn-outline-secondary ${layer.bold ? 'active' : ''}">B</button>
              <button id="insItalic" class="btn btn-outline-secondary ${layer.italic ? 'active' : ''}">I</button>
              <button id="insUnderline" class="btn btn-outline-secondary ${layer.underline ? 'active' : ''}">U</button>
            </div>
            <input id="insTextColor" type="color" value="${layer.color || '#000000'}" class="form-control form-control-sm" style="max-width:60px">
          </div>
          <div class="mb-2">
            <label class="form-label small-muted">Background color (behind text)</label>
            <input id="insTextBg" type="color" class="form-control form-control-sm" value="${layer.bgColor && layer.bgColor !== 'transparent' ? layer.bgColor : '#ffffff'}">
          </div>
          <div class="mb-2">
            <label class="form-label small-muted">Opacity</label>
            <input id="insTextOpacity" type="range" min="0" max="1" step="0.05" value="${layer.opacity}">
            <div class="small-muted">Value: <span id="insTextOpacityVal">${layer.opacity}</span></div>
          </div>
          <div class="mb-2">
            <label class="form-label small-muted">Rotate (degrees)</label>
            <input id="insTextRotate" type="number" class="form-control form-control-sm" value="${layer.rotate || 0}" step="1">
          </div>
          <div class="d-flex gap-2">
            <button id="insApplyText" class="btn btn-sm btn-primary">Apply</button>
            <button id="insDeleteText" class="btn btn-sm btn-outline-danger">Delete</button>
          </div>
        `;
                    this.inspectorContent.appendChild(html);

                    html.querySelector('#insTextOpacity').addEventListener('input', (e) => {
                        html.querySelector('#insTextOpacityVal').textContent = e.target.value;
                    });

                    html.querySelector('#insApplyText').addEventListener('click', () => {
                        const newText = html.querySelector('#insText').value;
                        layer.text = newText;
                        layer.fontFamily = html.querySelector('#insFont').value || layer.fontFamily;
                        layer.fontSize = Math.max(6, parseInt(html.querySelector('#insFontSize').value) || layer
                            .fontSize);
                        layer.bold = html.querySelector('#insBold').classList.contains('active');
                        layer.italic = html.querySelector('#insItalic').classList.contains('active');
                        layer.underline = html.querySelector('#insUnderline').classList.contains('active');
                        layer.color = html.querySelector('#insTextColor').value || '#000';
                        layer.bgColor = html.querySelector('#insTextBg').value || 'transparent';
                        layer.opacity = parseFloat(html.querySelector('#insTextOpacity').value) || 1;
                        layer.rotate = parseFloat(html.querySelector('#insTextRotate').value) || 0;

                        const layerData = this.customizationData.find(l => l.layerId === layer.id);
                        if (layerData && layerData.type === 'text') {
                            layerData.data.text = newText;
                            layerData.data.fontFamily = layer.fontFamily;
                            layerData.data.fontSize = layer.fontSize;
                            layerData.data.color = layer.color;
                            layerData.data.bold = layer.bold;
                            layerData.data.italic = layer.italic;
                            layerData.data.underline = layer.underline;
                            // layerData.method = this.state.method.id;
                            // layerData.position = this.state.position;
                        }

                        this.render();
                        this.updateHiddenField();
                    });

                    html.querySelector('#insDeleteText').addEventListener('click', () => {
                        if (!confirm('Delete this layer?')) return;
                        this.removeLayer(layer.id);
                    });
                }
            }

            hideInspector() {
                this.inspector.style.display = 'none';
                this.inspectorContent.innerHTML = '';
                this.selectedLayerId = null;
                Array.from(this.previewCanvas.querySelectorAll('.layer-item.selected')).forEach(n => n.classList.remove(
                    'selected'));
            }

            showLayerEditor(id) {
                const layer = this.state.layers.find(l => l.id === id);
                if (!layer) return;
                if (!layer.editable) {
                    alert('This layer is locked for editing');
                    return;
                }
                // keep existing prompt-based editor for quick changes, but inspector covers most now
                if (layer.type === 'text') {
                    const newText = prompt('Edit text', layer.text);
                    if (newText === null) return;
                    layer.text = newText;
                    this.render();
                } else {
                    const newW = prompt('Width in px', layer.widthPx || 120);
                    if (newW !== null) layer.widthPx = Math.max(20, parseInt(newW) || 120);
                    const newRotate = prompt('Rotate degrees (-180..180)', layer.rotate || 0);
                    if (newRotate !== null) layer.rotate = parseFloat(newRotate) || 0;
                    this.render();
                }
            }

            setZoom(z) {
                this.state.zoom = Math.min(2.5, Math.max(0.3, z));
                this.previewCanvas.style.transform = `scale(${this.state.zoom})`;
                document.getElementById('zoomLevel').value = Math.round(this.state.zoom * 100) + '%';
            }

            exportPNG() {
                this.loader.style.display = 'flex';
                const originalTransform = this.previewCanvas.style.transform;
                this.previewCanvas.style.transform = 'scale(1)';
                Array.from(this.previewCanvas.querySelectorAll('.layer-item.selected')).forEach(el => el.classList
                    .remove('selected'));
                html2canvas(this.previewCanvas, {
                        scale: 2,
                        backgroundColor: null
                    }).then(canvas => {
                        const link = document.createElement('a');
                        link.download = 'design-preview.png';
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                        link.remove();
                    }).catch((err) => {
                        console.error(err);
                        alert('Export failed');
                    })
                    .finally(() => {
                        this.previewCanvas.style.transform = originalTransform;
                        this.loader.style.display = 'none';
                    });
            }

            render() {
                // remove existing visual layer nodes
                Array.from(this.previewCanvas.querySelectorAll('.layer-item')).forEach(n => n.remove());
                // sort by zIndex ascending
                const layers = (this.state.layers || []).slice().filter(l => l.view === this.currentView).sort((a, b) =>
                    (a.zIndex || 0) - (b.zIndex || 0));
                layers.forEach(layer => {
                    const el = document.createElement('div');
                    el.className = 'layer-item';
                    el.dataset.id = layer.id;
                    // left/top in percent
                    const left = (layer.leftPct != null) ? layer.leftPct : this.positionToCss(layer.pos).left;
                    const top = (layer.topPct != null) ? layer.topPct : this.positionToCss(layer.pos).top;
                    el.style.left = left + '%';
                    el.style.top = top + '%';
                    el.style.transform = `translate(-50%,-50%) rotate(${layer.rotate || 0}deg)`;
                    el.style.opacity = (layer.opacity == null ? 1 : layer.opacity);
                    el.style.zIndex = layer.zIndex || 1;
                    if (layer.widthPx) el.style.width = layer.widthPx + 'px';
                    if (layer.heightPx) el.style.height = layer.heightPx + 'px';
                    if (layer.bgColor && layer.bgColor !== 'transparent') el.style.backgroundColor = layer
                        .bgColor;
                    if (layer.borderRadiusPx) el.style.borderRadius = (layer.borderRadiusPx || 0) + 'px';
                    if (layer.type === 'image') {
                        const img = document.createElement('img');
                        img.src = layer.src;
                        img.alt = 'Layer image';
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'contain';
                        el.appendChild(img);
                    } else if (layer.type === 'text') {
                        const span = document.createElement('div');
                        span.className = 'layer-text';
                        span.textContent = layer.text;
                        span.style.fontFamily = layer.fontFamily || 'Inter, system-ui';
                        span.style.fontSize = (layer.fontSize || 28) + 'px';
                        span.style.color = layer.color || '#000';
                        span.style.fontWeight = layer.bold ? 700 : 500;
                        span.style.fontStyle = layer.italic ? 'italic' : 'normal';
                        span.style.textDecoration = layer.underline ? 'underline' : 'none';
                        if (layer.bgColor && layer.bgColor !== 'transparent') span.style.background = layer
                            .bgColor;
                        span.style.opacity = layer.opacity == null ? 1 : layer.opacity;
                        el.appendChild(span);
                    }
                    // add pointer events for drag (only if draggable)
                    el.addEventListener('mousedown', (ev) => this.startDrag(ev, layer.id));
                    el.addEventListener('touchstart', (ev) => this.startDrag(ev, layer.id), {
                        passive: true
                    });

                    this.previewCanvas.appendChild(el);
                });

                this.refreshLayerList();
                this.recalcPrice();
                this.setZoom(this.state.zoom);
            }

            positionToCss(posKey) {
                const map = {
                    'right-chest': {
                        left: 70,
                        top: 34
                    },
                    'centre-chest': {
                        left: 50,
                        top: 34
                    },
                    'left-chest': {
                        left: 30,
                        top: 34
                    },
                    'top-chest': {
                        left: 50,
                        top: 18
                    },
                    'centre-back': {
                        left: 50,
                        top: 60
                    },
                    'top-back': {
                        left: 50,
                        top: 10
                    },
                    'shoulder-blades': {
                        left: 50,
                        top: 45
                    },
                    'bottom-back': {
                        left: 50,
                        top: 78
                    },
                    'left-sleeve': {
                        left: 12,
                        top: 45
                    },
                    'right-sleeve': {
                        left: 88,
                        top: 45
                    }
                };
                const p = map[posKey] || map['centre-chest'];
                return {
                    left: p.left,
                    top: p.top
                };
            }

            refreshLayerList() {
                this.layersList.innerHTML = '';
                const layers = (this.state.layers || []).slice().filter(l => l.view === this.currentView).sort((a, b) =>
                    (b.zIndex || 0) - (a.zIndex || 0));
                layers.forEach(l => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
                    li.setAttribute('data-id', l.id);
                    const title = (l.type === 'image') ? `Image (${l.pos})` :
                        `Text: "${(l.text || '').slice(0,30)}" (${l.pos})`;
                    // If not editable, disable edit/remove buttons
                    const disabledAttr = l.editable ? '' : 'disabled';
                    li.innerHTML = `<div><strong style="display:block">${title}</strong><small class="text-muted">${l.type} · z:${l.zIndex||0}</small></div>
                        <div class="btn-group btn-group-sm" role="group">
                          <button class="btn btn-outline-secondary edit-layer" title="Edit" ${disabledAttr}>Edit</button>
                          <button class="btn btn-outline-danger remove-layer" title="Remove" ${disabledAttr}>Del</button>
                        </div>`;
                    this.layersList.appendChild(li);

                    li.querySelector('.edit-layer').addEventListener('click', (e) => {
                        e.stopPropagation();
                        const layer = this.state.layers.find(x => x.id === l.id);
                        if (!layer.editable) {
                            alert('Layer is locked for editing');
                            return;
                        }
                        this.showLayerEditor(l.id);
                    });
                    li.querySelector('.remove-layer').addEventListener('click', (e) => {
                        e.stopPropagation();
                        const layer = this.state.layers.find(x => x.id === l.id);
                        if (!layer.editable) {
                            alert('Layer is locked for editing');
                            return;
                        }
                        if (!confirm('Delete this layer?')) return;
                        this.removeLayer(l.id);
                    });
                });
            }

            initLayerDragHandlers() {
                this._drag = {
                    active: false,
                    id: null,
                    startX: 0,
                    startY: 0,
                    initLeftPct: 0,
                    initTopPct: 0,
                    rect: null
                };
                document.addEventListener('mousemove', (ev) => this.onDragMove(ev));
                document.addEventListener('mouseup', () => this.onDragEnd());
                document.addEventListener('touchmove', (ev) => this.onDragMove(ev), {
                    passive: false
                });
                document.addEventListener('touchend', () => this.onDragEnd());
            }

            startDrag(ev, layerId) {
                ev.preventDefault();
                const layer = this.state.layers.find(l => l.id === layerId && l.view === this.currentView);
                if (!layer) return;
                if (!layer.draggable) {
                    // UX: a small flash or message
                    // eslint-disable-next-line no-console
                    console.log('Layer locked for dragging');
                    return;
                }
                const rect = this.previewCanvas.getBoundingClientRect();
                const pointer = this._getPointer(ev);
                this._drag.active = true;
                this._drag.id = layerId;
                this._drag.startX = pointer.x;
                this._drag.startY = pointer.y;
                this._drag.rect = rect;
                this._drag.initLeftPct = (layer.leftPct != null) ? layer.leftPct : (this.positionToCss(layer.pos).left);
                this._drag.initTopPct = (layer.topPct != null) ? layer.topPct : (this.positionToCss(layer.pos).top);
                this.selectLayer(layerId);
                document.body.style.userSelect = 'none';
            }

            onDragMove(ev) {
                if (!this._drag.active) return;
                ev.preventDefault();
                const pointer = this._getPointer(ev);
                const dx = pointer.x - this._drag.startX;
                const dy = pointer.y - this._drag.startY;
                const rect = this._drag.rect;
                if (!rect) return;
                const dxPct = (dx / rect.width) * 100;
                const dyPct = (dy / rect.height) * 100;
                const newLeft = Math.min(95, Math.max(5, this._drag.initLeftPct + dxPct));
                const newTop = Math.min(95, Math.max(5, this._drag.initTopPct + dyPct));
                const layer = this.state.layers.find(l => l.id === this._drag.id);
                if (!layer) return;
                layer.leftPct = newLeft;
                layer.topPct = newTop;
                const node = this.previewCanvas.querySelector(`.layer-item[data-id="${layer.id}"]`);
                if (node) {
                    node.style.left = newLeft + '%';
                    node.style.top = newTop + '%';
                }
            }

            onDragEnd() {
                if (!this._drag.active) return;
                this._drag.active = false;
                document.body.style.userSelect = '';
            }

            _getPointer(ev) {
                if (ev.touches && ev.touches[0]) return {
                    x: ev.touches[0].clientX,
                    y: ev.touches[0].clientY
                };
                return {
                    x: ev.clientX,
                    y: ev.clientY
                };
            }

            capitalize(s) {
                return s.charAt(0).toUpperCase() + s.slice(1);
            }
            escapeHtml(s) {
                return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }
        }

        // initialize
        $(function() {
            const app = new ProductCustomiserSlimViews();
            window.customiserApp = app;
            document.getElementById('zoomLevel').value = Math.round(app.state.zoom * 100) + '%';

            $('.pos-btn').click(function() {
                // Remove active from all buttons
                $('.pos-btn').removeClass('active btn-dark').addClass('btn-outline-dark');

                // Add active to clicked button
                $(this).addClass('active btn-dark').removeClass('btn-outline-dark');

                // Show image
                const imageUrl = $(this).data('image');
                $('#positionImage').attr('src', imageUrl).show();
                $('#positionPlaceholder').hide();

                // Update accordion title
                const positionName = $(this).data('pos');
                $('.accordion-button').text('Position: ' + positionName);
            });

            $('.pos-btn').first().click();

        });
    </script>
@endsection