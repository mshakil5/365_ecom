<script>
    $(document).ready(function() {
        const debounceTime = 300;
        let timer, selected = -1;

        function handleSearch($input, $dropdown, $icon) {
            let query = $input.val().trim();
            const layout = $input.data('layout');
            if (query.length < 2) {
                $dropdown.hide().empty();
                $icon.attr('class', 'icon-search');
                selected = -1;
                return;
            }

            $icon.attr('class', 'fa fa-spinner fa-spin');

            $.ajax({
                url: "{{ route('search.products') }}",
                type: "GET",
                data: {
                    q: query
                },
                success: function(res) {
                    let html = '';
                    const itemsPerRow = layout === 'desktop' ? 2 : 1;

                    if (res.length > 0) {
                        html = `<div class="row g-0">`;
                        res.forEach((p, i) => {
                            let nameHighlighted = p.name.replace(new RegExp(query, 'gi'),
                                match => `<mark>${match}</mark>`);
                            html += `
                              <div class="col-${12/itemsPerRow} border-bottom ${i%itemsPerRow===0 ? 'border-end' : ''}">
                                  <a href="/product/${p.slug}" class="d-flex flex-column p-2 text-decoration-none text-dark">
                                      <div style="flex:0 0 auto;">
                                          <img src="${p.feature_image}" class="img-fluid w-100" style="height:150px; object-fit:cover;">
                                      </div>
                                      <div class="mt-2">
                                          <div>${nameHighlighted}</div>
                                          <span class="product-default-price">
                                              ${p.del_price && p.del_price > p.price
                                                  ? `<del class="product-default-price-off">£${parseFloat(p.del_price).toFixed(2)}</del>` : ''}
                                              £${parseFloat(p.price).toFixed(2)}
                                          </span>
                                      </div>
                                  </a>
                              </div>
                          `;
                            if ((i + 1) % itemsPerRow === 0 && i < res.length - 1) html +=
                                `</div><div class="row g-0">`;
                        });
                        html += `</div>`;

                        html += `
                          <div class="p-2 text-center border-top">
                              <form action="{{ route('products.show', 'search-products') }}" method="GET">
                                  <input type="hidden" name="query" value="${query}">
                                  <button type="submit" class="contact-submit-btn w-100">View All Results</button>
                              </form>
                          </div>
                      `;
                    } else {
                        html =
                            `<div class="p-3 text-center text-muted">No products found for "<strong>${query}</strong>"</div>`;
                    }

                    $dropdown.html(html).show();
                    $icon.attr('class', 'icon-search');
                    selected = -1;
                },
                error(xhr, status, error) {
                    console.error(xhr.responseText);
                    $icon.attr('class', 'icon-search');
                }
            });

        }

        $('.searchInput').on('input search', function() {
            const $this = $(this);
            const $dropdown = $this.siblings('.searchDropdown');
            const $icon = $this.siblings('.searchBtn').find('i');
            clearTimeout(timer);
            timer = setTimeout(() => handleSearch($this, $dropdown, $icon), debounceTime);
        });

        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        if (!SpeechRecognition) return;

        const recognition = new SpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'en-US';

        $('.voiceSearchBtn').on('click', function() {
            const $input = $(this).siblings('.searchInput');
            recognition.start();
            $(this).find('i').addClass('fa-spin');
            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                $input.val(transcript).trigger('input');
            }
            recognition.onend = () => $(this).find('i').removeClass('fa-spin');
        });

        $('<style>')
            .prop('type', 'text/css')
            .html(
                '.searchDropdown a.active { background-color: #f0f0f0; } .searchDropdown a { cursor: pointer; }'
                )
            .appendTo('head');
    });
</script>