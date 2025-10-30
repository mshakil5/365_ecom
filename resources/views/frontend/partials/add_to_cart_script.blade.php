<script>
    $(document).ready(function() {

        function getSelectedSizes() {
            let sizes = [];
            $('.qty-input').each(function() {
                let qty = parseInt($(this).val());
                if (qty > 0) {
                    sizes.push({
                        size_id: $(this).data('size-id'),
                        ean: $(this).data('ean') || null,
                        quantity: qty
                    });
                }
            });
            return sizes;
        }

        function updateCartCount() {
            $.ajax({
                url: "{{ route('cart.getCount') }}",
                method: "GET",
                success: function(res) {
                    $('.cartCount').text(res.count || 0);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        $('.add-to-cart-btn').on('click', function(e) {
            e.preventDefault();
            let action = $(this).data('action');
            let productId = $(this).data('product-id');
            let productName = $(this).data('product-name');
            let productImage = $(this).data('image');
            let selectedColorId = $('input[name="color"]:checked').val();
            let sizes = getSelectedSizes();

            if (sizes.length === 0) {
                toastr.warning("Please select quantity.");
                return;
            }

            $.ajax({
                url: "{{ route('cart.addSession') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    color_id: selectedColorId,
                    sizes: sizes,
                    product_name: productName,
                    product_image: productImage
                },
                success: function(res) {
                    toastr.success("Product added to cart!");
                    console.log('Session cart:', res.cart);
                    updateCartCount();

                    if (action === 'cart') {
                        $('#product-name').text(productName);
                        $('#product-image').attr('src', productImage);
                        $('#offcanvas').addClass('show');
                        $('.offcanvas-overlay').show();
                    } else if (action === 'customize') {
                        setTimeout(() => {
                            const encodedId = btoa(productId); // Base64 encode
                            window.location.href = "/customize?product=" +
                            encodedId;
                        }, 500);
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.cart-delete-btn', function(e) {
            e.preventDefault();
            let row = $(this).closest('tr');
            row.remove();

            let total = 0;
            $('.item-subtotal').each(function() {
                total += parseFloat($(this).text());
            });

            $('#cart-total').text('£' + total.toFixed(2));
            updateCartCount();

            if($('.table-cart tbody tr').length === 0){
                $('#checkout-wrapper').hide();
                $('.table-cart tbody').html('<tr><td colspan="5" class="text-center">Cart is empty</td></tr>');
            }
        });

        $('.offcanvas-close, .offcanvas-overlay').on('click', function() {
            $('#offcanvas').removeClass('show');
            $('.offcanvas-overlay').hide();
        });

        // Plus/Minus buttons for quantity
        $('.plus-btn').on('click', function() {
            const input = $(this).prev('.qty-input');
            input.val(parseInt(input.val() || 0) + 1);
        });

        $('.minus-btn').on('click', function() {
            const input = $(this).next('.qty-input');
            if (parseInt(input.val() || 0) > 0) {
                input.val(parseInt(input.val() || 0) - 1);
            }
        });

        // Initial cart count on page load
        updateCartCount();
    });
</script>