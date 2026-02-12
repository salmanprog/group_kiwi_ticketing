$(document).ready(function() {
    const $container = $('#dynamicInputsContainer');
    let installmentIndex = 0;
    const $errorBox = $('#installmentError');

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
            $errorBox.text("Total amount already fully allocated!").show();
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
