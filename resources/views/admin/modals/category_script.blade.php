<script>
  $(document).ready(function() {
      $('#newCategory').on('submit', function(e) {
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
                  $('#addCategoryModal').modal('hide');
                  form[0].reset();
                  if(response.category) {
                      let select = $('.category_id');
                      select.append(`<option value="${response.category.id}" selected>${response.category.name}</option>`);
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