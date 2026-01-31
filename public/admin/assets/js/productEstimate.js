// Helper: show messages in modal
function showModalMessage(modal, message, type = 'success') {
    const alertBox = modal.find('#modalAlert');
    alertBox.removeClass('d-none alert-success alert-danger')
            .addClass('alert-' + type)
            .text(message);
}

// Clear modal messages on close
$('.modal').on('hidden.bs.modal', function () {
    $(this).find('#modalAlert').addClass('d-none').text('');
});

// Function to recalculate totals
function updateTotals() {
    let subtotal = 0;

    $('#productTable tbody tr').each(function() {
        const totalText = $(this).find('.item-total').text().replace('$', '');
        subtotal += parseFloat(totalText) || 0;
    });

    const tax = subtotal * 0.10;       // 10% Tax
    const gratuity = subtotal * 0.05;  // 5% Gratuity
    const total = subtotal + tax + gratuity;

    $('#subtotal').text('$' + subtotal.toFixed(2));
    $('#tax').text('$' + tax.toFixed(2));
    $('#gratuity').text('$' + gratuity.toFixed(2));
    $('#total').text('$' + total.toFixed(2));
}

// Add products dynamically
$('#addProductsBtn').on('click', function (e) {
    e.preventDefault();

    let products = [];

    $('.product-checkbox:checked').each(function () {
        let productId = $(this).data('id');
        let qty = $('.product-qty[data-id="' + productId + '"]').val();
        let price = $(this).data('price');
        let name = $(this).data('name');

        products.push({
            product_id: productId,
            qty: qty,
            price: price,
            name: name
        });
    });

    if (products.length === 0) {
        showModalMessage($('#productModal'), 'Please select at least one product', 'danger');
        return;
    }

    let url = $(this).data('url');       
    let csrfToken = $(this).data('csrf'); 
    let estimateId = $(this).data('estimateid');

    $.ajax({
        url: url,
        type: "POST",
        data: {
            _token: csrfToken,
            user_estimate_id: estimateId,
            products: products
        },
        success: function (res) {
            if(res.status){
                // Close modal
                $('#productModal').modal('hide');

                // Append new rows dynamically
                res.items.forEach(function(item){
                    let row = `
                        <tr>
                            <td>${item.name}</td>
                            <td>${item.quantity} ${item.unit || ''}</td>
                            <td>$${parseFloat(item.price).toFixed(2)}</td>
                            <td class="item-total">$${parseFloat(item.total_price).toFixed(2)}</td>
                            <td class="no-print">
                                <button class="btn btn-sm btn-primary edit-item"
                                        data-url="${$('#addProductsBtn').data('update-url')}"
                                        data-estimateid="${estimateId}"
                                        data-csrf="${csrfToken}"
                                        data-id="${item.id}"
                                        data-name="${item.name}"
                                        data-quantity="${item.quantity}"
                                        data-unit="${item.unit || ''}"
                                        data-price="${item.price}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger remove-item"
                                        data-url="${$('#addProductsBtn').data('delete-url')}"
                                        data-id="${item.id}"
                                        data-estimateid="${estimateId}"
                                        data-csrf="${csrfToken}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    $('#productTable tbody').append(row);
                });

                // Update totals
                updateTotals();

            } else {
                showModalMessage($('#productModal'), res.message || 'Unable to add products', 'danger');
            }
        },
        error: function(err){
            console.error(err.responseText);
            showModalMessage($('#productModal'), 'Something went wrong', 'danger');
        }
    });
});


// Edit item
$(document).on('click', '.edit-item', function() {
    let btn = $(this);
    let modal = $('#editProductModal');

    modal.find('input[name="item_id"]').val(btn.data('id'));
    modal.find('input[name="name"]').val(btn.data('name'));
    modal.find('input[name="quantity"]').val(btn.data('quantity'));
    modal.find('input[name="unit"]').val(btn.data('unit'));
    modal.find('input[name="price"]').val(btn.data('price'));

    modal.data('url', btn.data('url'));
    modal.data('csrf', btn.data('csrf'));
    modal.data('estimateid', btn.data('estimateid'));

    modal.modal('show');
});

// Submit edited item
$('#editProductForm').on('submit', function(e){
    e.preventDefault();
    let modal = $('#editProductModal');
    let formData = {
        _token: modal.data('csrf'),
        item_id: modal.find('input[name="item_id"]').val(),
        quantity: modal.find('input[name="quantity"]').val(),
        unit: modal.find('input[name="unit"]').val(),
        price: modal.find('input[name="price"]').val(),
        estimate_id: modal.data('estimateid')
    };

    $.ajax({
        url: modal.data('url'),
        type: 'POST',
        data: formData,
        success: function(res){
            showModalMessage(modal, res.message, 'success');

            // Update table row
            let row = $('#productTable tbody tr[data-id="'+res.item.id+'"]');
            row.find('td:eq(0)').text(res.item.name);
            row.find('td:eq(1)').text(res.item.quantity + ' ' + (res.item.unit || ''));
            row.find('td:eq(2)').text('$' + parseFloat(res.item.price).toFixed(2));
            row.find('td:eq(3)').text('$' + parseFloat(res.item.total_price).toFixed(2));

            setTimeout(() => modal.modal('hide'), 1000);
            updateTotals();
        },
        error: function(err){
            showModalMessage(modal, 'Something went wrong', 'danger');
        }
    });
});

// Delete item
$(document).on('click', '.remove-item', function(){
    if(!confirm('Are you sure?')) return;

    let btn = $(this);
    let url = btn.data('url');
    let csrf = btn.data('csrf');
    let itemId = btn.data('id');
    let estimateId = btn.data('estimateid');

    $.ajax({
        url: url,
        type: 'POST',
        data: { _token: csrf, item_id: itemId, estimate_id: estimateId },
        success: function(res){
            if(res.status){
                $('#productTable tbody tr[data-id="'+itemId+'"]').remove();
                updateTotals();
                showModalMessage($('#productModal'), res.message, 'success');
            } else {
                showModalMessage($('#productModal'), 'Unable to delete item', 'danger');
            }
        },
        error: function(err){
            showModalMessage($('#productModal'), 'Something went wrong', 'danger');
        }
    });
});

// Initialize totals on page load
$(document).ready(updateTotals);
