<script>
  $(document).ready(function() {
      $('#newBrand').on('submit', function(e) {
          e.preventDefault();

          let form = $(this);
          let formData = new FormData(this);

          $.ajax({
              url: form.attr('action'),
              type: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              success: function(response) {
                  $('#addBrandModal').modal('hide');
                  form[0].reset();

                  if (response.brand) {
                      let select = $('.brand_id');
                      select.append(`<option value="${response.brand.id}" selected>${response.brand.name}</option>`);
                      select.val(response.brand.id).trigger('change');
                  }

                  showSuccess(response.message);
              },
              error: function(xhr, status, error) {
                  if (xhr.status === 422) {
                      let firstError = Object.values(xhr.responseJSON.errors)[0][0];
                      showError(firstError);
                  } else {
                      showError(xhr.responseJSON?.message ?? "Something went wrong!");
                  }
                  console.error(xhr.responseText);
              }
          });
      });
  });
</script>