$(document).ready(function() {

    function updatePrice() {
        let selectedOption = $('#product option:selected');
        let price = parseFloat(selectedOption.data('price')) || 0;
        let name = selectedOption.data('name') || '';
        let qty = parseFloat($('#product_qty').val()) || 1;

        let total = price;

        $('#product_price').val(total.toFixed(2));
        $('#product_name').val(name);
    }

    // When product changes
    $('#product').on('change', function() {
        updatePrice();
    });

    // When quantity changes
    $('#product_qty').on('input', function() {
        updatePrice();
    });


    $('#contractForm').on('submit', function (e) {
    e.preventDefault();

    let form = $(this);
    let url = form.attr('action');
    let formData = new FormData(this);

    // Show loader
    $('#contractLoader').show();

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function (response) {
            // Hide loader
            $('#contractLoader').hide();

            // Clear table
            let tbody = $('#md_productTable tbody');
            tbody.empty();

            if (!response.estimates || response.estimates.length === 0) {
                tbody.append(`<tr class="no-items">
                                <td colspan="5" class="text-center">No products added yet.</td>
                              </tr>`);
                $('#md_subtotal').text('$0.00');
                $('#tax_amount').text('$0.00');
                $('#total').text('$0.00');

                showMessage('No products added yet.', 'info');
                return;
            }

            let subtotal = 0;
            let taxTotal = 0;

            response.estimates.forEach(function(estimate){
                if(!estimate.items || estimate.items.length === 0) return;

                estimate.items.forEach(function(item){
                    let quantity = parseFloat(item.quantity) || 0;
                    let price = parseFloat(item.price) || 0;
                    let total = quantity * price;
                    subtotal += total;

                    // Render taxes
                    let taxesHtml = '';
                    if(item.item_taxes && item.item_taxes.length){
                        item.item_taxes.forEach(function(tax){
                            let taxAmount = total * parseFloat(tax.percentage)/100 || 0;
                            taxTotal += taxAmount;
                            taxesHtml += `<small class="text-muted d-block">Apply Taxes: ${tax.name} (${tax.percentage}%)</small>`;
                        });
                    }

                    // Append row
                    tbody.append(`
                        <tr data-id="${item.id}">
                            <td>${item.name}${taxesHtml}</td>
                            <td>${quantity} ${item.unit ?? ''}</td>
                            <td>$${price.toFixed(2)}</td>
                            <td class="item-total">$${total.toFixed(2)}</td>
                            <td class="no-print">
                                <button class="btn btn-sm btn-danger remove-item" data-id="${item.id}">Delete</button>
                            </td>
                        </tr>
                    `);
                });
            });

            // Update totals
            $('#md_subtotal').text(`$${subtotal.toFixed(2)}`);
            $('#tax_amount').text(`$${taxTotal.toFixed(2)}`);
            $('#total').text(`$${(subtotal + taxTotal).toFixed(2)}`);

            // Show success message
            showMessage('Contract products saved successfully!', 'success');

            // Optionally close modal
            $('#modifyContractModal').modal('hide');
        },
        error: function (xhr) {
            $('#contractLoader').hide();
            let errorMsg = 'Something went wrong!';
            if(xhr.responseJSON && xhr.responseJSON.message){
                errorMsg = xhr.responseJSON.message;
            }
            showMessage(errorMsg, 'danger');
            console.error(xhr.responseText);
        }
    });
});
});

function showMessage(message, type = 'info') {
    let msgDiv = $(`
        <div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `);
    $('#contractMessages').html(msgDiv);
}

$(document).on('click', 'button[data-bs-target="#modifyContractModal"]', function () {
    let contractId = $(this).data('id');
    let url = $(this).data('url');

    $('#contractLoader').show();

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            $('#contractLoader').hide();
            
            let tbody = $('#md_productTable tbody');
            tbody.empty();

            if (!response.estimates || response.estimates.length === 0) {
                tbody.append(`<tr class="no-items">
                                <td colspan="5" class="text-center">No products added yet.</td>
                              </tr>`);
                $('#md_subtotal').text('$0.00');
                $('#md_tax_amount').text('$0.00');
                $('#md_total').text('$0.00');
                $('#remaining_total').val(0);
                $('#total_amount').val(0);
                $('#remainingTotal').text('$0.00');
                return;
            }

            let subtotal = 0;
            let totalTax = 0;

            // Clear installments container
            let installmentsContainer = $('#dynamicInputsContainer');
            installmentsContainer.empty();

            response.estimates.forEach(function(estimate){
                if(!estimate.items || estimate.items.length === 0) return;

                // Loop through items
                estimate.items.forEach(function(item){
                    let quantity = parseFloat(item.quantity) || 0;
                    let price = parseFloat(item.price) || 0;
                    let total = quantity * price;
                    subtotal += total;

                    // Item-level taxes
                    let taxesHtml = '';
                    if(item.item_taxes && item.item_taxes.length){
                        item.item_taxes.forEach(function(tax){
                            let taxAmount = total * parseFloat(tax.percentage)/100;
                            totalTax += taxAmount;
                            taxesHtml += `<small class="text-muted d-block">Apply Taxes: ${tax.name} (${tax.percentage}%)</small>`;
                        });
                    }

                    tbody.append(`
                        <tr data-id="${item.id}">
                            <td>${item.name}${taxesHtml}</td>
                            <td>${quantity} ${item.unit ?? ''}</td>
                            <td>$${price.toFixed(2)}</td>
                            <td class="item-total">$${total.toFixed(2)}</td>
                            <td class="no-print">
                                <button type="button" class="btn btn-sm btn-danger md-remove-item" data-url="/portal/contract/modify/delete" data-contract_id="${contractId}" data-product_id="${item.id}">Delete</button>
                            </td>
                        </tr>
                    `);
                });

                // Append estimate-level taxes if needed
                if (estimate.taxes && estimate.taxes.length) {
                    let taxBlocks = '';
                    estimate.taxes.forEach(function(tax){
                        taxBlocks += `
                            <div class="border rounded px-2 py-1 d-flex align-items-center gap-1" data-tax-id="${tax.id}">
                                <small class="fw-semibold">${tax.name} (${tax.percent}%)</small>
                                <button class="btn btn-sm btn-link text-primary edit-tax" type="button"
                                    data-id="${contractId}"
                                    data-tax-id="${tax.id}"
                                    data-url="/portal/contract/modify/${contractId}/details"
                                    data-update-url="/portal/estimate/tax/update/${tax.id}"
                                    data-csrf="${$('meta[name="csrf-token"]').attr('content')}"
                                    data-estimateid="${estimate.id}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editTaxModal">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-link text-danger delete-tax" type="button"
                                    data-contract-id="${contractId}"
                                    data-tax-id="${tax.id}"
                                    data-url="/portal/estimate/tax/delete/${tax.id}"
                                    data-csrf="${$('meta[name="csrf-token"]').attr('content')}">
                                    Delete
                                </button>
                            </div>
                        `;
                    });

                    tbody.append(`
                        <tr>
                            <th colspan="4" class="text-end">Tax:
                                <div class="d-flex flex-wrap gap-2 justify-content-end">
                                    ${taxBlocks}
                                </div>
                            </th>
                            <th id="tax_amount">$${totalTax.toFixed(2)}</th>
                        </tr>
                    `);
                }

                // Append installments
                if (estimate.installments && estimate.installments.length) {
                    estimate.installments.forEach(function(inst, idx){
                        installmentsContainer.append(`
                            <div class="row mb-2 installment-row">
                                <div class="col-md-5">
                                    <input type="number"
                                        name="installments[${idx}][amount]"
                                        class="form-control inst-amount"
                                        value="${inst.amount}"
                                        step="0.01" min="0" required>
                                </div>
                                <div class="col-md-5">
                                    <input type="date"
                                        name="installments[${idx}][date]"
                                        class="form-control inst-date"
                                        value="${inst.installment_date}"
                                        min="${new Date().toISOString().split('T')[0]}" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100 btn-remove">Remove</button>
                                </div>
                            </div>
                        `);
                    });
                }
            });

            // Update totals
            let grandTotal = subtotal + totalTax;
            $('#md_subtotal').text(`$${subtotal.toFixed(2)}`);
            $('#md_tax_amount').text(`$${totalTax.toFixed(2)}`);
            $('#md_total').text(`$${grandTotal.toFixed(2)}`);
            $('#remaining_total').val(grandTotal.toFixed(2));
            $('#total_amount').val(grandTotal.toFixed(2));
            $('#remainingTotal').text(`$${grandTotal.toFixed(2)}`);
        },
        error: function(xhr){
            $('#contractLoader').hide();
            console.error(xhr.responseText);
            alert('Failed to load contract details.');
        }
    });
});



$(document).on('click', 'button[data-bs-target="#taxModal"]', function () {
    let contractId = $(this).data('id');
    let url = $(this).data('url');
    $('#contractLoader').show();
    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {

            $('#contractLoader').hide();
            let tbody = $('#md_producttaxTable tbody');
            tbody.empty();

            if (!response.estimates || response.estimates.length === 0) {
                tbody.append(`<tr class="no-items">
                                <td colspan="5" class="text-center">No products added yet.</td>
                              </tr>`);
                $('#md_tx_subtotal').text('$0.00');
                $('#md_tax_amount').text('$0.00');
                $('#md_tx_total').text('$0.00');
                return;
            }

            let subtotal = 0;
            let taxTotal = 0;

            response.estimates.forEach(function(estimate){
                if(!estimate.items || estimate.items.length === 0) return;

                estimate.items.forEach(function(item){

                    let quantity = parseFloat(item.quantity) || 0;
                    let price = parseFloat(item.price) || 0;
                    let total = quantity * price;
                    subtotal += total;

                    // Render taxes for item
                    let taxesHtml = '';
                    if(item.item_taxes && item.item_taxes.length){
                        item.item_taxes.forEach(function(tax){
                            let taxAmount = total * parseFloat(tax.percentage)/100 || 0;
                            taxTotal += taxAmount;
                            taxesHtml += `<small class="text-muted d-block">Apply Taxes: ${tax.name} (${tax.percentage}%)</small>`;
                        });
                    }

                    // Append item row
                    tbody.append(`
                        <tr data-id="${item.id}">
                            <td><input type="checkbox" name="selected_products[]" value="${item.id}">${item.name}</td>
                            <td>${item.name}${taxesHtml}</td>
                            <td>${quantity} ${item.unit ?? ''}</td>
                            <td>$${price.toFixed(2)}</td>
                            <td class="item-total">$${total.toFixed(2)}</td>
                        </tr>
                    `);
                });
            });

            // Update footer totals without discount
            $('#md_tx_subtotal').text(`$${subtotal.toFixed(2)}`);
            $('#md_tax_amount').text(`$${taxTotal.toFixed(2)}`);
            $('#md_tx_total').text(`$${(subtotal + taxTotal).toFixed(2)}`);
        },
        error: function(xhr){
            console.error(xhr.responseText);
            alert('Failed to load contract details.');
        }
    });
});

$(document).on('click', 'button[data-bs-target="#editTaxModal"]', function () {
    let contractId = $(this).data('id');
    let taxId = $(this).data('tax-id'); 
    let url = `/portal/contract/${contractId}/tax/${taxId}/edit`; // route pointing to controller

    $('#contractLoader').show();

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            $('#contractLoader').hide();

            let tbody = $('#md_edit_producttaxTable tbody');
            tbody.empty();

            // Pre-fill tax name and percent
            if(response.tax) {
                $('#edit_tax_id').val(response.tax.id);
                $('#md_edit_tax_name').val(response.tax.name);
                $('#md_edit_tax_percent').val(response.tax.percent);
            }

            let subtotal = 0;
            let taxTotal = 0;

            response.contract.estimates.forEach(function(estimate){
                if(!estimate.items || estimate.items.length === 0) return;

                estimate.items.forEach(function(item){
                    let quantity = parseFloat(item.quantity) || 0;
                    let price = parseFloat(item.price) || 0;
                    let total = quantity * price;
                    subtotal += total;

                    // Check if this item already has this tax applied
                    let isChecked = '';
                    if(item.item_taxes && item.item_taxes.length){
                        item.item_taxes.forEach(function(tax){
                            if(tax.estimate_tax_id == taxId){
                                isChecked = 'checked';
                            }
                            let taxAmount = total * parseFloat(tax.percentage)/100 || 0;
                            taxTotal += taxAmount;
                        });
                    }

                    tbody.append(`
                        <tr data-id="${item.id}">
                            <td><input type="checkbox" name="selected_products[]" value="${item.id}" ${isChecked}></td>
                            <td>${item.name}</td>
                            <td>${quantity} ${item.unit ?? ''}</td>
                            <td>$${price.toFixed(2)}</td>
                            <td class="item-total">$${total.toFixed(2)}</td>
                        </tr>
                    `);
                });
            });

            // Update totals
            $('#md_edit_tx_subtotal').text(`$${subtotal.toFixed(2)}`);
            $('#md_edit_tax_amount').text(`$${taxTotal.toFixed(2)}`);
            $('#md_edit_tx_total').text(`$${(subtotal + taxTotal).toFixed(2)}`);
        },
        error: function(xhr){
            console.error(xhr.responseText);
            alert('Failed to load contract details.');
        }
    });
});

$('#contracteditTaxForm').on('submit', function(e){
    e.preventDefault();

    $('#contractLoader').show();

    let form = $(this);
    let formData = form.serialize();

    $.ajax({
        url: '/portal/estimate/tax/update', // your update route
        type: 'POST',
        data: formData,
        success: function(response){
            $('#contractLoader').hide();

            $('#editTaxModal').modal('hide');

            alert('Tax updated successfully.');

            // Reload modify contract modal
            reloadModifyContract($('input[name="contract_id"]').val());
        },
        error: function(xhr){
            $('#contractLoader').hide();
            console.log(xhr.responseText);
            alert('Failed to update tax.');
        }
    });
});


$(document).on('click', '.md-remove-item', function () {

    let button     = $(this);
    let url        = button.data('url');
    let contractId = button.data('contract_id');
    let productId  = button.data('product_id');
    let row        = button.closest('tr');

    if (!confirm('Are you sure you want to delete this item?')) {
        return;
    }
     $('#contractLoader').show();
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            contract_id: contractId, 
            product_id: productId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
             $('#contractLoader').hide();
            let tbody = $('#md_productTable tbody');
            tbody.empty();

            if (!response.estimates || response.estimates.length === 0) {
                tbody.append(`<tr class="no-items">
                                <td colspan="5" class="text-center">No products added yet.</td>
                              </tr>`);
                $('#subtotal').text('$0.00');
                $('#tax_amount').text('$0.00');
                $('#total').text('$0.00');
                return;
            }

            let subtotal = 0;
            let taxTotal = 0;

            response.estimates.forEach(function(estimate){
                if(!estimate.items || estimate.items.length === 0) return;

                estimate.items.forEach(function(item){

                    let quantity = parseFloat(item.quantity) || 0;
                    let price = parseFloat(item.price) || 0;
                    let total = quantity * price;
                    subtotal += total;

                    // Render taxes for item
                    let taxesHtml = '';
                    if(item.item_taxes && item.item_taxes.length){
                        item.item_taxes.forEach(function(tax){
                            let taxAmount = total * parseFloat(tax.percentage)/100 || 0;
                            taxTotal += taxAmount;
                            taxesHtml += `<small class="text-muted d-block">Apply Taxes: ${tax.name} (${tax.percentage}%)</small>`;
                        });
                    }

                    // Append item row
                    tbody.append(`
                        <tr data-id="${item.id}">
                            <td>${item.name}${taxesHtml}</td>
                            <td>${quantity} ${item.unit ?? ''}</td>
                            <td>$${price.toFixed(2)}</td>
                            <td class="item-total">$${total.toFixed(2)}</td>
                            <td class="no-print">
                                <button type="button" class="btn btn-sm btn-danger md-remove-item" data-url="/portal/contract/modify/delete" data-contract_id="${contractId}" data-product_id="${item.id}">Delete</button>
                            </td>
                        </tr>
                    `);
                });
            });

            // Update footer totals without discount
            $('#md_subtotal').text(`$${subtotal.toFixed(2)}`);
            $('#md_tax_amount').text(`$${taxTotal.toFixed(2)}`);
            $('#md_total').text(`$${(subtotal + taxTotal).toFixed(2)}`);
            $('#modifyContractModal').modal('hide');
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            alert('Delete failed.');
        }
    });
});

$(document).on('submit', '#contractTaxForm', function (e) {

    e.preventDefault(); 

    let form = $(this);
    let url  = form.attr('action');

    let contractId = form.find('input[name="contract_id"]').val();
    let taxName    = $('#md_tax_name').val();
    let taxPercent = $('#md_tax_percent').val();
    let selectedProducts = [];
    $('#md_producttaxTable tbody input[name="selected_products[]"]:checked')
        .each(function () {
            selectedProducts.push($(this).val());
        });

    if (selectedProducts.length === 0) {
        alert('Please select at least one product.');
        return;
    }

    $('#contractLoader').show();

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            contract_id: contractId,
            tax_name: taxName,
            tax_percent: taxPercent,
            products: selectedProducts,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $('#contractLoader').hide();
            //alert('Tax applied successfully.');
            $('#taxModal').modal('hide');
            $('#modifyContractModal').modal('hide');
            reloadModifyContract(contractId);
        },

        error: function (xhr) {
            $('#contractLoader').hide();
            console.log(xhr.responseText);
            alert('Failed to apply tax.');
        }
    });

});


$(document).off('click', '.delete-tax').on('click', '.delete-tax', function () {

    if (!confirm('Are you sure you want to delete this tax?')) {
        return;
    }

    let url = $(this).data('url');
    let csrf = $(this).data('csrf');

    $('#contractLoader').show();

    $.ajax({
        url: url,
        type: 'DELETE',
        data: {
            _token: csrf
        },
        success: function (response) {
                $('#contractLoader').hide();
                window.location.reload();

               // rebuildModifyTable(response);
        },
        error: function (xhr) {
            $('#contractLoader').hide();
            alert('Failed to delete tax.');
        }
    });

});



function reloadModifyContract(contractId) {

    $.ajax({
        url: '/portal/contract/modify/'+contractId+'/details/',
        type: 'GET',
        success: function(response) {

           $('#contractLoader').hide();
            
            let tbody = $('#md_productTable tbody');
            tbody.empty();

            if (!response.estimates || response.estimates.length === 0) {
                tbody.append(`<tr class="no-items">
                                <td colspan="5" class="text-center">No products added yet.</td>
                              </tr>`);
                $('#md_subtotal').text('$0.00');
                $('#md_tax_amount').text('$0.00');
                $('#md_total').text('$0.00');
                $('#remaining_total').val(0);
                $('#total_amount').val(0);
                $('#remainingTotal').text('$0.00');
                return;
            }

            let subtotal = 0;
            let totalTax = 0;

            // Clear installments container
            let installmentsContainer = $('#dynamicInputsContainer');
            installmentsContainer.empty();

            response.estimates.forEach(function(estimate){
                if(!estimate.items || estimate.items.length === 0) return;

                // Loop through items
                estimate.items.forEach(function(item){
                    let quantity = parseFloat(item.quantity) || 0;
                    let price = parseFloat(item.price) || 0;
                    let total = quantity * price;
                    subtotal += total;

                    // Item-level taxes
                    let taxesHtml = '';
                    if(item.item_taxes && item.item_taxes.length){
                        item.item_taxes.forEach(function(tax){
                            let taxAmount = total * parseFloat(tax.percentage)/100;
                            totalTax += taxAmount;
                            taxesHtml += `<small class="text-muted d-block">Apply Taxes: ${tax.name} (${tax.percentage}%)</small>`;
                        });
                    }

                    tbody.append(`
                        <tr data-id="${item.id}">
                            <td>${item.name}${taxesHtml}</td>
                            <td>${quantity} ${item.unit ?? ''}</td>
                            <td>$${price.toFixed(2)}</td>
                            <td class="item-total">$${total.toFixed(2)}</td>
                            <td class="no-print">
                                <button type="button" class="btn btn-sm btn-danger md-remove-item" data-url="/portal/contract/modify/delete" data-contract_id="${contractId}" data-product_id="${item.id}">Delete</button>
                            </td>
                        </tr>
                    `);
                });

                // Append estimate-level taxes if needed
                if (estimate.taxes && estimate.taxes.length) {
                    let taxBlocks = '';
                    estimate.taxes.forEach(function(tax){
                        taxBlocks += `
                            <div class="border rounded px-2 py-1 d-flex align-items-center gap-1" data-tax-id="${tax.id}">
                                <small class="fw-semibold">${tax.name} (${tax.percent}%)</small>
                                <button class="btn btn-sm btn-link text-primary edit-tax" type="button"
                                    data-id="${contractId}"
                                    data-tax-id="${tax.id}"
                                    data-url="/portal/contract/modify/${contractId}/details"
                                    data-update-url="/portal/estimate/tax/update/${tax.id}"
                                    data-csrf="${$('meta[name="csrf-token"]').attr('content')}"
                                    data-estimateid="${estimate.id}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editTaxModal">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-link text-danger delete-tax" type="button"
                                    data-contract-id="${contractId}"
                                    data-tax-id="${tax.id}"
                                    data-url="/portal/estimate/tax/delete/${tax.id}"
                                    data-csrf="${$('meta[name="csrf-token"]').attr('content')}">
                                    Delete
                                </button>
                            </div>
                        `;
                    });

                    tbody.append(`
                        <tr>
                            <th colspan="4" class="text-end">Tax:
                                <div class="d-flex flex-wrap gap-2 justify-content-end">
                                    ${taxBlocks}
                                </div>
                            </th>
                            <th id="tax_amount">$${totalTax.toFixed(2)}</th>
                        </tr>
                    `);
                }

                // Append installments
                if (estimate.installments && estimate.installments.length) {
                    estimate.installments.forEach(function(inst, idx){
                        installmentsContainer.append(`
                            <div class="row mb-2 installment-row">
                                <div class="col-md-5">
                                    <input type="number"
                                        name="installments[${idx}][amount]"
                                        class="form-control inst-amount"
                                        value="${inst.amount}"
                                        step="0.01" min="0" required>
                                </div>
                                <div class="col-md-5">
                                    <input type="date"
                                        name="installments[${idx}][date]"
                                        class="form-control inst-date"
                                        value="${inst.installment_date}"
                                        min="${new Date().toISOString().split('T')[0]}" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100 btn-remove">Remove</button>
                                </div>
                            </div>
                        `);
                    });
                }
            });

            // Update totals
            let grandTotal = subtotal + totalTax;
            $('#md_subtotal').text(`$${subtotal.toFixed(2)}`);
            $('#md_tax_amount').text(`$${totalTax.toFixed(2)}`);
            $('#md_total').text(`$${grandTotal.toFixed(2)}`);
            $('#remaining_total').val(grandTotal.toFixed(2));
            $('#total_amount').val(grandTotal.toFixed(2));
            $('#remainingTotal').text(`$${grandTotal.toFixed(2)}`);

            // Show success message
            showMessage('Contract products tac apply successfully!', 'success');
        }
    });
}

//Payment

$(document).ready(function() {
    const $container = $('#dynamicInputsContainer');
    let installmentIndex = 0;
    const $errorBox = $('#md_installmentError');

    // Add Row
    $('#addRowBtn').on('click', function() {
        const totalAmount = parseFloat($('#total_amount').val()) || 0;

        if (totalAmount === 0) {
            $errorBox.text("Please set total amount before adding installment.").show();
            return;
        }

        let paidSoFar = 0;
        $container.find('.inst-amount').each(function() {
            paidSoFar += parseFloat($(this).val()) || 0;
        });

        let remaining = totalAmount - paidSoFar;
        if (remaining <= 0) {
            //$errorBox.text("Total amount already fully allocated!").show();
            return;
        }

        $errorBox.hide();
        $container.append(createRowHtml(remaining.toFixed(2)));
        calculateBalance();
    });

    // Remove Row
    $container.on('click', '.btn-remove', function() {
        $(this).closest('.installment-row').remove();
        calculateBalance();
    });

    // Update remaining on input change
    $container.on('input', '.inst-amount', function() {
        const totalAmount = parseFloat($('#total_amount').val()) || 0;
        let paidSoFar = 0;

        $container.find('.inst-amount').each(function() {
            paidSoFar += parseFloat($(this).val()) || 0;
        });

        if (paidSoFar > totalAmount) {
            $errorBox.text("Installments cannot exceed total amount!").show();
            $(this).val("");
        } else {
            $errorBox.hide();
        }

        calculateBalance();
    });

    // Submit form via AJAX
    $('#paymentScheduleForm').on('submit', function(e) {
        e.preventDefault();
        const totalAmount = parseFloat($('#remaining_total').val()) || 0;
        if ($container.find('.inst-amount').length === 0) {
            $errorBox.text("Please add at least one installment.").show();
            return;
        }

        if (totalAmount > 0) {
            $errorBox.text("Please set total amount adjust in installment.").show();
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.status) {
                    $('#formMessage').text(res.message).removeClass('text-danger').addClass('text-success').show();
                } else {
                    $('#formMessage').text(res.message).removeClass('text-success').addClass('text-danger').show();
                }
            },
            error: function(xhr) {
                let msg = "Something went wrong!";
                if(xhr.responseJSON && xhr.responseJSON.errors){
                    msg = Object.values(xhr.responseJSON.errors).map(arr => arr.join(", ")).join(", ");
                }
                $('#formMessage').text(msg).removeClass('text-success').addClass('text-danger').show();
            }
        });
    });

    // Calculate remaining total
    function calculateBalance() {
        const totalAmount = parseFloat($('#total_amount').val()) || 0;
        let paid = 0;
        $container.find('.inst-amount').each(function() {
            paid += parseFloat($(this).val()) || 0;
        });

        const remaining = totalAmount - paid;
        $('#remainingTotal').text('$' + (remaining > 0 ? remaining.toFixed(2) : '0.00'));
        $('#remaining_total').val(remaining > 0 ? remaining.toFixed(2) : '0.00');
    }

    // Generate new row HTML
    function createRowHtml(amount = "", date = "") {
        installmentIndex++;
        const today = new Date().toISOString().split('T')[0];

        return `
        <div class="row mb-2 installment-row">
            <div class="col-md-5">
                <input type="number" 
                       name="installments[${installmentIndex}][amount]" 
                       class="form-control inst-amount" 
                       placeholder="Amount" 
                       value="${amount}" 
                       step="0.01" min="0" required>
            </div>
            <div class="col-md-5">
                <input type="date" 
                       name="installments[${installmentIndex}][date]" 
                       class="form-control inst-date" 
                       value="${date || today}" 
                       min="${today}" 
                       required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove">×</button>
            </div>
        </div>`;
    }

    // Initial calculation
    calculateBalance();
});


$('#modifypaymentScheduleForm').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    var btn = $('#savemodifyPaymentScheduleBtn');
    var msgEl = $('#paymentScheduleMessage');
    btn.prop('disabled', true);
    btn.find('.btn-schedule-text').hide();
    btn.find('.btn-schedule-loading').show();
    msgEl.hide().removeClass('text-success text-danger');

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function(res) {
            btn.prop('disabled', false);
            btn.find('.btn-schedule-loading').hide();
            btn.find('.btn-schedule-text').show();
            if (res.status === true) {
                msgEl.text(res.message || 'Payment schedule saved successfully!').addClass('text-success').show();
            } else {
                msgEl.text(res.message || 'Something went wrong.').addClass('text-danger').show();
            }
        },
        error: function(xhr) {
            btn.prop('disabled', false);
            btn.find('.btn-schedule-loading').hide();
            btn.find('.btn-schedule-text').show();
            var res = (xhr.responseJSON || {});
            msgEl.text(res.message || (xhr.responseText || 'Request failed.')).addClass('text-danger').show();
        }
    });
});

// Handle the modify contract form submission via AJAX
$('#clientConfirmationForm').on('submit', function(e) {
    e.preventDefault(); // prevent default form submit

    let form = $(this);
    let url = form.attr('action');
    let submitBtn = form.find('button[type="submit"]');

    // Optional: disable button and show loading
    submitBtn.prop('disabled', true).text('Saving...');

    $.ajax({
        url: url,
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function(res) {
            // Enable button and reset text
            submitBtn.prop('disabled', false).text('Save All Changes');

            if(res.status === true) {
                // Show success message (you can add a div #contractMessages in modal for this)
                $('#contractMessages').html(`<div class="alert alert-success">${res.message || 'Contract saved successfully!'}</div>`);

                // Optionally, close modal after a short delay
                setTimeout(() => {
                    $('#modifyContractModal').modal('hide');
                }, 1000);
            } else {
                $('#contractMessages').html(`<div class="alert alert-danger">${res.message || 'Something went wrong!'}</div>`);
            }
        },
        error: function(xhr) {
            submitBtn.prop('disabled', false).text('Save All Changes');

            let res = xhr.responseJSON || {};
            $('#contractMessages').html(`<div class="alert alert-danger">${res.message || 'Failed to save contract.'}</div>`);
        }
    });
});
