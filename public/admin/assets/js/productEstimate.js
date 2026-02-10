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


// function updateTotals() {
//     let subtotal = 0;
//     let totalTax = 0;
//     let gratuityRate = 0; // 5% gratuity
//     let total = 0;

//     $('#productTable tbody tr').each(function() {
//         const rowTotalText = $(this).find('.item-total').text().replace('$', '').replace(',', '');
//         const rowTotal = parseFloat(rowTotalText) || 0;

//         subtotal += rowTotal;

//         // Calculate taxes for this row
//         let rowTax = 0;
//         const taxDiv = $(this).find('small.text-muted'); // contains taxes like "VAT (10%), Service (5%)"
//         if (taxDiv.length) {
//             const taxText = taxDiv.text(); // e.g., "VAT (10%), Service (5%)"
//             const taxMatches = taxText.match(/\(([\d.]+)%\)/g); // ["(10%)", "(5%)"]
//             if (taxMatches) {
//                 taxMatches.forEach(t => {
//                     const percent = parseFloat(t.replace('(', '').replace('%)', '')) || 0;
//                     rowTax += rowTotal * (percent / 100);
//                 });
//             }
//         }

//         totalTax += rowTax;
//     });

//     const gratuityAmount = subtotal * gratuityRate;
//     total = subtotal + totalTax + gratuityAmount;

//     $('#subtotal').text('$' + subtotal.toFixed(2));
//     $('#tax_amount').text('$' + totalTax.toFixed(2));
//     $('#gratuity').text ? $('#gratuity').text('$' + gratuityAmount.toFixed(2)) : null;
//     $('#total').text('$' + total.toFixed(2));
// }
// function updateTotals() {
//     let subtotal = 0;
//     let totalTax = 0;
//     let gratuityRate = 0; // update if you add gratuity input
//     let total = 0;

//     /* -----------------------------
//        1️⃣ Subtotal (from products)
//     ------------------------------ */
//     $('#productTable tbody tr').each(function () {
//         const rowText = $(this)
//             .find('.item-total')
//             .text()
//             .replace('$', '')
//             .replace(/,/g, '')
//             .trim();

//         const rowTotal = parseFloat(rowText) || 0;
//         subtotal += rowTotal;
//     });

//     /* -----------------------------
//        2️⃣ Tax (from tfoot)
//        supports multiple taxes
//     ------------------------------ */
//     $('#productTable tfoot small.fw-semibold').each(function () {
//         const match = $(this).text().match(/([\d.]+)\s*%/);
//         if (match) {
//             const percent = parseFloat(match[1]) || 0;
//             totalTax += subtotal * (percent / 100);
//         }
//     });

//     /* -----------------------------
//        3️⃣ Discount (percent only)
//     ------------------------------ */
//     const discountCell = $('.discount_percent').first();
//     const discountPercent = discountCell.length
//         ? parseFloat(discountCell.text().replace('%', '').trim()) || 0
//         : 0;

//     const discountAmount = subtotal * (discountPercent / 100);

//     /* -----------------------------
//        4️⃣ Gratuity (optional)
//     ------------------------------ */
//     const gratuityAmount = subtotal * gratuityRate;

//     /* -----------------------------
//        5️⃣ Total
//     ------------------------------ */
//     total = subtotal - discountAmount + totalTax + gratuityAmount;

//     /* -----------------------------
//        6️⃣ Remaining amount
//     ------------------------------ */
//     const paidAmount = parseFloat($('#paidAmount').val()) || 0;
//     const remainingTotal = total - paidAmount;

//     /* -----------------------------
//        7️⃣ Update UI
//     ------------------------------ */
//     $('#subtotal').text('$' + subtotal.toFixed(2));
//     $('#tax_amount').text('$' + totalTax.toFixed(2));
//     $('#discount_amount').text('-$' + discountAmount.toFixed(2));
//     $('#gratuity').length && $('#gratuity').text('$' + gratuityAmount.toFixed(2));
//     $('#total').text('$' + total.toFixed(2));
//     $('#remainingTotal').text('$' + remainingTotal.toFixed(2));
//     $('#remainingTotalInput').val(remainingTotal.toFixed(2));
//     $('#total_amount').val(remainingTotal.toFixed(2));
// }


function updateTotals() {
    let subtotal = 0;
    let totalTax = 0;
    let totalDiscount = 0;
    let gratuityRate = 0; // change if you have a gratuity input
    let gratuityAmount = 0;
    let total = 0;

    // 1️⃣ Subtotal & Item Taxes
    $('#productTable tbody tr').each(function () {
        const rowTotalText = $(this).find('.item-total').text()
            .replace('$','')
            .replace(/,/g,'')
            .trim();
        const rowTotal = parseFloat(rowTotalText) || 0;

        subtotal += rowTotal;

        const taxesData = $(this).find('small[data-taxes]').data('taxes') || [];
        taxesData.forEach(tax => {
            const percent = parseFloat(tax.percent) || 0;
            const taxAmount = parseFloat((rowTotal * (percent / 100)).toFixed(2)); // round per-item-tax
            totalTax += taxAmount;
        });
    });

    // 2️⃣ Discounts (percent discounts applied on subtotal + tax)
    totalDiscount = 0;
    $('.discount_percent').each(function () {
        const discountPercent = parseFloat($(this).text().replace('%','').trim()) || 0;
        const discountAmount = parseFloat(((subtotal + totalTax) * (discountPercent / 100)).toFixed(2));
        totalDiscount += discountAmount;
    });

    // 3️⃣ Gratuity (optional)
    gratuityAmount = parseFloat(((subtotal + totalTax - totalDiscount) * gratuityRate).toFixed(2));

    // 4️⃣ Total
    total = subtotal + totalTax - totalDiscount + gratuityAmount;

    // 5️⃣ Remaining after paid
    const paidAmount = parseFloat($('#paidAmount').val()) || 0;
    const remainingTotal = total - paidAmount;

    // 6️⃣ Update UI
    $('#subtotal').text('$' + subtotal.toFixed(2));
    $('#tax_amount').text('$' + totalTax.toFixed(2));
    $('#discount_amount').text('-$' + totalDiscount.toFixed(2));
    if($('#gratuity').length) {
        $('#gratuity').text('$' + gratuityAmount.toFixed(2));
    }
    $('#total').text('$' + total.toFixed(2));
    $('#remainingTotal').text('$' + remainingTotal.toFixed(2));
    $('#remainingTotalInput').val(remainingTotal.toFixed(2));
    $('#total_amount').val(remainingTotal.toFixed(2));
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
                window.location.reload();
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


$(document).on('click', '.save-note', function () {

    const btn = $(this);
    const url = btn.data('url');
    const csrf = btn.data('csrf');
    const estimateId = btn.data('estimateid');

    const note = $('#estimate_note').val();
    const terms_and_condition = $('#terms_and_condition').val();

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: csrf,
            estimate_id: estimateId,
            note: note,
            terms_and_condition:terms_and_condition
        },
        success: function (res) {
            $('#formMessage')
                .removeClass('text-danger text-success')
                .addClass(res.status ? 'text-success' : 'text-danger')
                .text(res.message)
                .fadeIn();

            // Optional: update print preview instantly
            $('.print-value').html('<strong>Note:</strong> ' + note);
        },
        error: function () {
            $('#formMessage')
                .removeClass('text-success')
                .addClass('text-danger')
                .text('Failed to save note')
                .fadeIn();
        }
    });
});


$(document).on('click', '.send-to-client', function () {

    const btn = $(this);
    const url = btn.data('url');
    const csrf = btn.data('csrf');
    const estimateId = btn.data('estimateid');
    const slug = btn.data('slug');
    const status = btn.data('status');

    const issue_date   = $('input[name="estimate_date"]').val();
    const eventDate      = $('input[name="event_date"]').val() ?? null;
    const valid_until = $('input[name="expiration_date"]').val();

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: csrf,
            estimate_id: estimateId,
            slug: slug,
            issue_date: issue_date,
            eventDate: eventDate,
            valid_until: valid_until,
            is_installment:1,
            mail_send:1,
            status: status,
        },
        success: function (res) {
            $('#formMessage')
                .removeClass('text-danger text-success')
                .addClass(res.status ? 'text-success' : 'text-danger')
                .text(res.message)
                .fadeIn();

            // Optional: update print preview instantly
            window.location.reload();
        },
        error: function () {
            $('#formMessage')
                .removeClass('text-success')
                .addClass('text-danger')
                .text('Failed to sending client')
                .fadeIn();
        }
    });
});


// Initialize totals on page load
$(document).ready(updateTotals);