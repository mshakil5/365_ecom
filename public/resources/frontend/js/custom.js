$(document).on('blur change', '.quantity-input', function () {
    let input = $(this);
    let max = parseInt(input.attr('max'));
    let min = 1;
    let value = parseInt(input.val());

    if (isNaN(value) || value < min) {
        input.val(min);
    } else if (value > max) {
        input.val(max);
        toastr.warning('Maximum stock limit reached!');
    }
});