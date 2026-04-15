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
$('#editmodifydiscountModal').on('show.bs.modal', function (e) {
    const button = $(e.relatedTarget);

    editcallDiscount(button);
});

function editcallDiscount(button) {

    let csrfToken = button.data('csrf');
    let contractmodifiedid = button.data('contractmodifiedid');
    let discountid = button.data('discountid');
    let url = button.data('url');

    $.ajax({
        url: url,
        type: 'GET',
        data: {
            _token: csrfToken,
            contract_modified_id: contractmodifiedid,
            id:discountid
        },
        success: function (res) {
            console.log('res',res.item)
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

function addProductDiscountModify() {
    const modal = $('#discountModifyModal');
    const contractModifiedId = $('#addDiscountmodify').data('contractmodifiedid');
    const discountType = $('#discountType').val();
    const csrfToken = $('#addDiscountmodify').data('csrf');

    const discountName = $('#discountName').val().trim();
    const discountValue = parseFloat($('#discountAmount').val());

    
    const btn = $('#addDiscountmodify');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

    if (!discountName || isNaN(discountValue) || discountValue <= 0) {
        showModalMessage(modal, 'Please provide valid discount name and value', 'danger');
        btn.prop('disabled', false).html('Update Discount');
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
        btn.prop('disabled', false).html('Update Discount');
        return;
    }

    $.ajax({
        url: $('#addDiscountmodify').data('url'), // Set your route for discount
        type: 'POST',
        data: {
            _token: csrfToken,
            contract_modified_id: contractModifiedId,
            products: products,
            discount_type: discountType
        },
        success: function(res) {
            if (res.status) {
                Toastify({
                    text: res.message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "toast-success"
                }).showToast();
                // Close modal and refresh totals
                setTimeout(() => modal.modal('hide'), 1000);

                // Optionally render discounts in table footers
                //renderDiscounts(res.discount_id, discountName, discountType, discountValue);
                location.reload();
                // Recalculate totals
                //updateTotals();
                
            } else {
                   Toastify({
                    text: res.message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "toast-error"
                }).showToast();
                //showModalMessage(modal, res.message, 'danger');
                btn.prop('disabled', false).html('Update Discount');
            }
            //window.location.reload();
        },
        error: function(xhr) {
            let msg = 'Something went wrong';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors).map(arr => arr.join('<br>')).join('<br>');
            }
            btn.prop('disabled', false).html('Update Discount');
               Toastify({
                    text: msg,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "toast-error"
                }).showToast();
            showModalMessage(modal, msg, 'danger');
        }
    });
}

$('#updateDiscount').on('click', function () {

    const button = $(this);
    const modal  = $('#editmodifydiscountModal');

    const csrfToken  = button.data('csrf');
    const contractModifiedId = button.data('contractmodifiedid');
    const discountId = button.data('discountid');
    const url        = button.data('url');

    const discountName  = modal.find('#editdiscountName').val().trim();
    const discountValue = modal.find('#editdiscountAmount').val();
    const discountType  = modal.find('#editdiscountType').val();
    const btn = $('#updateDiscount');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
    

    if (!discountName || !discountValue || discountValue <= 0) {
        alert('Please enter valid discount details');
        btn.prop('disabled', false).html('Update Discount');
        return;
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: csrfToken,
            contract_modified_id: contractModifiedId,
            discount_id: discountId,
            name: discountName,
            value: discountValue,
            type: discountType
        },
        success: function (res) {

            if (res.status) {
                Toastify({
                    text: res.message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "toast-success"
                }).showToast();
                // Close modal
                modal.modal('hide');

                // OPTIONAL: refresh page or totals
                location.reload(); // simplest & safest
                // OR call updateTotals();

            } else {
                Toastify({
                    text: res.message || 'Update failed',
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "toast-error"
                }).showToast();
            }
        },
        error: function (err) {
            console.error(err.responseText);
            Toastify({
                text: 'Something went wrong',
                duration: 3000,
                gravity: "top",
                position: "right",
                className: "toast-error"
            }).showToast();
        }
    });
});

// Delete discount click
$(document).on('click', '.delete-modify-discount', function () {
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
                Toastify({
                    text: res.message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "toast-success"
                }).showToast();
                button.closest('tr').remove();

                // Recalculate totals
                //updateTotals();
                location.reload();
            } else {
                Toastify({
                    text: res.message || 'Something went wrong',
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "toast-error"
                }).showToast();
            }
        },
        error: function (err) {
            console.error(err.responseText);
            Toastify({
                text: 'Error deleting discount',
                duration: 3000,
                gravity: "top",
                position: "right",
                className: "toast-error"
            }).showToast();
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
