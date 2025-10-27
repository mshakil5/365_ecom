<script>
  $(document).ready(function() {
      function updateCartCount() {
          let cart = JSON.parse(localStorage.getItem('cart')) || [];
          $('.cartCount').text(cart.length);
      }

      $(document).on('click', '.add-to-cart', function(e) {
          e.preventDefault();

          let productId = $(this).data('product-id');
          let productName = $(this).data('product-name');
          let productImage = $(this).data('image');

          if (!productId) return;

          let cart = JSON.parse(localStorage.getItem('cart')) || [];
          let existingItem = cart.find(item => item.productId === productId);
          if (existingItem) {
              existingItem.quantity += 1;
          } else {
              cart.push({ productId: productId, quantity: 1 });
          }
          localStorage.setItem('cart', JSON.stringify(cart));
          updateCartCount();

          $('#product-name').text(productName);
          $('#product-image').attr('src', productImage);

          $('#customize-btn').attr('href', '/product/customize/' + productId);

          $('#offcanvas').addClass('show');
          $('.offcanvas-overlay').show();

          toastr.success("Added to cart");
      });

      $('.offcanvas-close, .offcanvas-overlay').on('click', function() {
          $('#offcanvas').removeClass('show');
          $('.offcanvas-overlay').hide();
      });

      $('#customize-btn').off('click').on('click', function() {
          $('#offcanvas').removeClass('show');
          $('.offcanvas-overlay').hide();
      });

      updateCartCount();
  });
</script>