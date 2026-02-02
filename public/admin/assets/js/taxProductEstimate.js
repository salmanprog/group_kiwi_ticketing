$('#taxModal').on('shown.bs.modal', function (e) {
    e.preventDefault();
    callTax(e);
});
$('#editTaxModal').on('shown.bs.modal', function (e) {
    e.preventDefault();
    const button = $(e.relatedTarget);
    const taxId = button.data('tax-id'); // from edit button in table
    $('#updateTaxBtn').data('taxid', taxId);
    editcallTax(e);
});
function showModalMessage(modal, message, type = 'success') {
    const alertBox = modal.find('#modalAlert');
    alertBox.removeClass('d-none alert-success alert-danger')
            .addClass('alert-' + type)
            .text(message);
}

function callTax(e) {

    let button = $(e.relatedTarget);

    let csrfToken = button.data('csrf');
    let estimateId = button.data('estimateid');
    let url = button.data('url');

    $.ajax({
        url: url,
        type: 'GET',
        data: {
            _token: csrfToken,
            estimate_id: estimateId
        },
        success: function (res) {

            // clear table
            $('#taxTable tbody').empty();

            if (res.status && Array.isArray(res.item) && res.item.length > 0) {

                res.item.forEach(function (item) {

                    let row = `
                        <tr data-id="${item.id}" data-tax="${item.tax}">
                             <td>
                                <input type="checkbox" class="product-checkbox" data-id="${item.product_id}" data-price="${item.price}" data-name="${item.name}">
                            </td>
                            <td>${item.name}</td>
                            <td>${item.quantity} ${item.unit || ''}</td>
                            <td>$${parseFloat(item.price).toFixed(2)}</td>
                            <td>$${parseFloat(item.total_price).toFixed(2)}</td>
                        </tr>
                    `;

                    $('#taxTable tbody').append(row);
                });

            } else {

                $('#taxTable tbody').append(`
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No products found
                        </td>
                    </tr>
                `);
            }
        },
        error: function (err) {
            console.error(err.responseText);
            alert('Something went wrong');
        }
    });
}

$('#editTaxModal').on('shown.bs.modal', function () {

    if (window.editTaxResponse && window.editTaxResponse.tax) {
        $('#edittaxName').val(window.editTaxResponse.tax.name);
        $('#edittaxPercent').val(window.editTaxResponse.tax.percentage);
    }

});

function editcallTax(e) {

    let button = $(e.relatedTarget);

    let csrfToken = button.data('csrf');
    let estimateId = button.data('estimateid');
    let tax_id = button.data('tax-id');
    let url = button.data('url');

    $.ajax({
        url: url,
        type: 'GET',
        data: {
            _token: csrfToken,
            estimate_id: estimateId,
            tax_id: tax_id
        },
        success: function (res) {

            // clear table
            $('#editTaxTable tbody').empty();

            if (res.status && Array.isArray(res.item) && res.item.length > 0) {

                window.editTaxResponse = res;
                $('#edittaxName').val(res.tax.name);
                $('#edittaxPercent').val(res.tax.percentage);

                $('#editTaxTable tbody').empty();

                res.item.forEach(function (item) {

                    // check if THIS tax is applied to THIS item
                    let isChecked = item.item_taxes.some(
                        tax => tax.estimate_tax_id == res.tax.estimate_tax_id
                    );

                    let row = `
                        <tr data-id="${item.id}">
                            <td>
                                <input type="checkbox"
                                    class="product-checkbox"
                                    data-item-id="${item.id}"
                                    ${isChecked ? 'checked' : ''}>
                            </td>
                            <td>${item.name}</td>
                            <td>${item.quantity} ${item.unit}</td>
                            <td>$${parseFloat(item.price).toFixed(2)}</td>
                            <td>$${parseFloat(item.total_price).toFixed(2)}</td>
                        </tr>
                    `;

                    $('#editTaxTable tbody').append(row);
                });

            } else {

                $('#editTaxTable tbody').append(`
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No products found
                        </td>
                    </tr>
                `);
            }
        },
        error: function (err) {
            console.error(err.responseText);
            alert('Something went wrong');
        }
    });
}

function addTax() {
    const modal = $('#taxModal');
    const estimateId = $('#addTaxBtn').data('estimateid'); // You can store estimateId in button or modal
    const csrfToken = $('#addTaxBtn').data('csrf');

    // Collect selected products
    const products = [];
    $('#taxTable tbody tr').each(function () {
        const checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.is(':checked')) {
            const productId = $(this).data('id'); // set data-id on <tr>
            const price = parseFloat($(this).find('.price').text().replace('$', '')) || 0;

            products.push({
                id: productId,
                tax_name: $('#taxName').val(),
                tax_percent: parseFloat($('#taxPercent').val()),
                price: price
            });
        }
    });

    if (products.length === 0) {
        showModalMessage(modal, 'Please select at least one product', 'danger');
        return;
    }

    $.ajax({
        url: $('#addTaxBtn').data('url'), // your route
        type: 'POST',
        data: {
            _token: csrfToken,
            user_estimate_id: estimateId,
            products: products
        },
        success: function (res) {
            if (res.status) {
                showModalMessage(modal, res.message, 'success');
                // optionally close modal after a delay
                setTimeout(() => modal.modal('hide'), 1000);
                window.location.reload();
            } else {
                showModalMessage(modal, res.message, 'danger');
            }
        },
        error: function (xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                // Compile all validation errors
                const errors = xhr.responseJSON.errors;
                let errorMessages = '';
                for (const key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        errorMessages += errors[key].join('<br>') + '<br>';
                    }
                }
                showModalMessage(modal, errorMessages, 'danger');
            } else {
                showModalMessage(modal, 'Something went wrong', 'danger');
            }
        }
    });
}

function updateTax() {
    const modal = $('#editTaxModal');
    const taxId = $('#updateTaxBtn').data('taxid');
    const csrfToken = $('#updateTaxBtn').data('csrf');

    if (!taxId) {
        showModalMessage(modal, 'Tax ID is missing', 'danger');
        return;
    }

    const products = [];
    $('#editTaxTable tbody tr').each(function () {
        const checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.is(':checked')) {
            const productId = $(this).data('id');
            const price = parseFloat($(this).find('td:nth-child(4)').text().replace('$', '')) || 0;

            products.push({
                id: productId,
                tax_name: $('#edittaxName').val(),
                tax_percent: parseFloat($('#edittaxPercent').val()),
                price: price
            });
        }
    });

    if (products.length === 0) {
        showModalMessage(modal, 'Please select at least one product', 'danger');
        return;
    }

    $.ajax({
        url: $('#updateTaxBtn').data('url'),
        type: 'POST',
        data: {
            _token: csrfToken,
            tax_id: taxId,
            products: products
        },
        success: function (res) {
            if (res.status) {
                showModalMessage(modal, res.message, 'success');
                setTimeout(() => modal.modal('hide'), 1000);
                window.location.reload();
            } else {
                showModalMessage(modal, res.message, 'danger');
            }
        },
        error: function (xhr) {
            let msg = 'Something went wrong';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                msg = Object.values(errors).map(arr => arr.join('<br>')).join('<br>');
            }
            showModalMessage(modal, msg, 'danger');
        }
    });
}

$(document).on('click', '.delete-tax', function() {
    const button = $(this);
    const url = button.data('url');
    const csrfToken = button.data('csrf');

    if (!confirm('Are you sure you want to delete this tax?')) return;

    $.ajax({
        url: url,
        type: 'DELETE', // matches the Laravel route
        data: { _token: csrfToken },
        success: function(res) {
            if (res.status) {
                button.closest('div[data-tax-id]').remove(); // remove tax element from DOM
                window.location.reload();
            } else {
                alert(res.message || 'Something went wrong');
            }
        },
        error: function(xhr) {
            alert('Something went wrong');
            console.error(xhr.responseText);
        }
    });
});

