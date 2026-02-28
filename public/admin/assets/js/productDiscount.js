// Helper: show messages in modal
function showModalMessage(modal, message, type = 'success') {
    const alertBox = modal.find('#modalAlert');
    alertBox.removeClass('d-none alert-success alert-danger')
            .addClass('alert-' + type)
            .text(message);
}

// Clear modal messages on close
$('#discountModal').on('show.bs.modal', function(e) {
    const button = $(e.relatedTarget);
    $('#discountForm').data('estimateid', button.data('estimateid'));
    $('#discountForm').data('csrf', button.data('csrf'));
});
$('#editdiscountModal').on('show.bs.modal', function (e) {
    const button = $(e.relatedTarget);

    editcallDiscount(button);
});

function editcallDiscount(button) {

    let csrfToken = button.data('csrf');
    let estimateId = button.data('estimateid');
    let discountid = button.data('discountid');
    let url = button.data('url');

    $.ajax({
        url: url,
        type: 'GET',
        data: {
            _token: csrfToken,
            estimate_id: estimateId,
            id:discountid
        },
        success: function (res) {

            if (res.status && Array.isArray(res.item) && res.item.length > 0) {

                const discount = res.item[0]; // 👈 KEY LINE

                // Populate modal inputs
                $('#editdiscountType option[value="' + discount.type + '"]').prop('selected', true);
                $('#editdiscountName').val(discount.name);
                $('#editdiscountAmount').val(discount.value);

                // Store discount id for update
                $('#updateDiscount').data('discountid', discount.id);

            } else {
                alert('Discount not found');
            }
        },
        error: function (err) {
            console.error(err.responseText);
            alert('Something went wrong');
        }
    });
}

function addProductDiscount() {
    const modal = $('#discountModal');
    const estimateId = $('#addDiscount').data('estimateid');
    const discountType = $('#discountType').val();
    const csrfToken = $('#addDiscount').data('csrf');

    const discountName = $('#discountName').val().trim();
    const discountValue = parseFloat($('#discountAmount').val());

    if (!discountName || isNaN(discountValue) || discountValue <= 0) {
        showModalMessage(modal, 'Please provide valid discount name and value', 'danger');
        return;
    }

    // Collect all products from the table
    const products = [];
    $('#productTable tbody tr').each(function() {
        const productId = $(this).data('id');
        const priceText = $(this).find('.item-total').text().replace('$', '').replace(',', '');
        const price = parseFloat(priceText) || 0;

        products.push({
            id: productId,
            price: price,
            discount_name: discountName,
            discount_value: discountValue
        });
    });

    if (products.length === 0) {
        showModalMessage(modal, 'No products found to apply discount', 'danger');
        return;
    }

    $.ajax({
        url: $('#addDiscount').data('url'), // Set your route for discount
        type: 'POST',
        data: {
            _token: csrfToken,
            user_estimate_id: estimateId,
            products: products,
            discount_type: discountType
        },
        success: function(res) {
            if (res.status) {
                showModalMessage(modal, res.message, 'success');
                // Close modal and refresh totals
                setTimeout(() => modal.modal('hide'), 1000);

                // Optionally render discounts in table footers
                //renderDiscounts(res.discount_id, discountName, discountType, discountValue);
                location.reload();
                // Recalculate totals
                //updateTotals();
                
            } else {
                showModalMessage(modal, res.message, 'danger');
            }
            //window.location.reload();
        },
        error: function(xhr) {
            let msg = 'Something went wrong';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).map(arr => arr.join('<br>')).join('<br>');
            }
            showModalMessage(modal, msg, 'danger');
        }
    });
}

$('#updateDiscount').on('click', function () {

    const button = $(this);
    const modal  = $('#editdiscountModal');

    const csrfToken  = button.data('csrf');
    const estimateId = button.data('estimateid');
    const discountId = button.data('discountid');
    const url        = button.data('url');

    const discountName  = modal.find('#editdiscountName').val().trim();
    const discountValue = modal.find('#editdiscountAmount').val();
    const discountType  = modal.find('#editdiscountType').val();

    if (!discountName || !discountValue || discountValue <= 0) {
        alert('Please enter valid discount details');
        return;
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: csrfToken,
            estimate_id: estimateId,
            discount_id: discountId,
            name: discountName,
            value: discountValue,
            type: discountType
        },
        success: function (res) {

            if (res.status) {
                alert('Discount updated successfully');

                // Close modal
                modal.modal('hide');

                // OPTIONAL: refresh page or totals
                location.reload(); // simplest & safest
                // OR call updateTotals();

            } else {
                alert(res.message || 'Update failed');
            }
        },
        error: function (err) {
            console.error(err.responseText);
            alert('Something went wrong');
        }
    });
});

// Delete discount click
$(document).on('click', '.delete-discount', function () {
    const button = $(this);
    const url = button.data('url');
    const csrfToken = button.data('csrf');

    if (!confirm('Are you sure you want to delete this discount?')) return;

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: csrfToken,
            _method: 'DELETE' // Laravel expects DELETE method
        },
        success: function (res) {
            if (res.status) {
                // Remove discount row from table
                button.closest('tr').remove();

                // Recalculate totals
                //updateTotals();
                location.reload();
            } else {
                alert(res.message || 'Something went wrong');
            }
        },
        error: function (err) {
            console.error(err.responseText);
            alert('Error deleting discount');
        }
    });
});

// Optional: dynamically render discount in table footer
function renderDiscounts(id, name, type, value) {
    let footer = $('#productTable tfoot');
    let row = `
        <tr class="discount-row" data-discount-id="${id}">
            <th colspan="3" class="text-end">Discount (${name}):</th>
            <th id="discount_amount">
                ${type === 'percent' ? value + '%' : '$' + parseFloat(value).toFixed(2)}
            </th>
            <th></th>
        </tr>
    `;
    // Remove old discount row if exists
    footer.find('.discount-row').remove();
    footer.find('tr.fw-bold').before(row);
}
