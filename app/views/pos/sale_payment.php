<?php if (isset($sale) && $sale !== false) : ?>
<div id="register">
    <form method="post" id="payment-form">
        <div class="do_payments">

            <section class="main">
                <div class="register-sale-overview lines">
                    <div class="register-customer" style="display: flex;">
                        <?php if ($sale->customer_id && isset($customer) && $customer !== false) : ?>
                            <div id="customerInfo" class="block placeholder"><?= $sale->customer_name ?></div>

                            <a href="<?= HOST_NAME.'ajax/sale_remove_customer/'.$sale->id.'?returnURL=pos/sale_payment/'.$sale->id ?>" class="gui-def-button" style="margin-left: 1rem"><i class="fa fa-trash"></i> Remove</a>
                            <a href="<?= HOST_NAME ?>customers/customer_add" target="_blank" id="newCustomerButton" class="gui-def-button" style="margin-left: 10px"><i class="fa fa-plus"></i> New</a>
                        <?php else : ?>
                            <div id="customerInfo" class="block placeholder">No Customer Selected</div>
                        <?php endif; ?>

                    </div>


                    <?php if (isset($customer) && $customer) : ?>
                    <table id="payments_customer_and_credit">
                        <tbody>
                        <tr>
                            <th colspan="2" class="section">Credit Account</th>
                        </tr>
                        <?php if ($customer->credit_limit) : ?>
                        <tr>
                            <th>Credit Limit</th>
                            <td id="creditAccountCreditLimitValue">$<?= number_format($customer->credit_limit, 2) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($customer->owed) : ?>
                        <tr>
                            <th><?= $customer->owed > 0 ? "Balance Owed" : "Deposit" ?></th>
                            <td id="creditAccountBalanceOwedValue">$<?= number_format(str_replace('-', '', $customer->owed), 2) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($customer->credit_limit - $customer->owed) : ?>
                        <tr>
                            <th>Available</th>
                            <td id="creditAccountAvailableValue">$<?= number_format($customer->credit_limit - $customer->owed, 2) ?></td>
                        </tr>
                        <?php endif; ?>

                        <tr>
                            <td colspan="2">
                                <button id="addDepositButton" type="button" class="gui-def-button" data-toggle="modal" data-target="#customer-account-payment-modal">Add Deposit</button>
                                <div id="account_actions"></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <?php endif; ?>
                    <table>
                        <tbody id="current_sale_list">
                        <tr class="section">
                            <th colspan="4">Current Sale #<span id="currentSaleId"><?= $sale->id ?></span></th>
                        </tr>
                        <tr class="thead">
                            <th>Description</th>
                            <th class="th-money text-left">Buy Price</th>
                            <th>Qty.</th>
                            <th class="th-money">Subtotal</th>
                        </tr>
                        <?php if (isset($sale_items) && $sale_items !== false) : ?>
                        <?php foreach ($sale_items as $sale_item) : ?>
                            <tr>
                                <td><?= $sale_item->item ?></td>
                                <td class="money text-left">$<?= number_format($sale_item->buy_price, 2) ?></td>
                                <td><?= $sale_item->quantity ?></td>
                                <td class="money">$<?= number_format($sale_item->total, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="register-payment-form">
                    <div id="payments_box">
                        <div class="register-payments ">
                            <header id="paymentBoxTitle">Payment</header>
                            <div id="payments_content" class="content">
                                <div>
                                    <table class="payments_list" width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tbody>
                                        <?php if (isset($customer) && $customer !== false) : ?>
                                        <tr>
                                            <th>Account</th>
                                            <td>
                                                <div class="money-field-container payment-block-money localized ">
                                                    <input type="text" class="money group group-start" size="10" value="0.00" id="charge_account_payment" name="payments[account]" title="Credit Account" autocomplete="off" data-payment-type="account">
                                                </div>
                                                <button id="CreditAccountMaxButton" type="button" class="group group-end gui-def-button" data-credit-limit="<?= $customer->credit_limit - $customer->owed ?>">Max</button>
                                                <div id="creditAvailable" style="margin-top: 5px; font-size: 0.8em;">
                                                    Available:
                                                    <var>
                                                        $<?= number_format($customer->credit_limit - $customer->owed, 2) ?>
                                                    </var>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endif; ?>

                                        <tr>
                                            <th>Cash</th>
                                            <td>
                                                <div class="money-field-container payment-block-money localized">
                                                    <input tabindex="1" type="text" value="0.00" class="money group group-start" size="10" id="cash_payment" name="payments[cash]" title="Cash" autocomplete="off">
                                                </div>
                                                <button id="CashMaxButton" type="button" class="group group-end gui-def-button maxButton">Max</button>
                                            </td>
                                        </tr>
                                        <tr id="cash_remainder" class="hidden">
                                            <th>Change</th>
                                            <td class="payment-amount">
                                                <span id="cash_remainder_balance">$0.00</span>
                                            </td>
                                        </tr>

                                        <?php if (isset($payment_methods) && $payment_methods) : ?>
                                        <?php foreach ($payment_methods as $payment_method) : ?>
                                            <tr>
                                                <th><?= strtoupper($payment_method->method) ?></th>
                                                <td>
                                                    <div class="money-field-container payment-block-money localized">
                                                        <input type="text" value="0.00" class="money group group-start userDefinedInput" size="10" id="<?= $payment_method->method_key ?>_payment" name="payments[<?= $payment_method->method_key ?>]" title="<?= $payment_method->method ?>" autocomplete="off">
                                                    </div>
                                                    <button type="button" id="<?= $payment_method->method_key ?>MaxButton" class="group group-end userDefinedMaxButton gui-def-button maxButton">Max</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <div id="special_payments_list">
                                        <table id="emv_payments_list" class="emv-payments"></table>
                                    </div>
                                </div>
                            </div>
                            <footer>
                                <label for="payments_total">Total</label>
                                <div class="money-field-container localized">
                                    <input class="money" size="10" value="0.00" id="payments_total" disabled="disabled" autocomplete="off">
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="cirrus">
                <div class="cirrus transaction-sidebar">
                    <div id="register_totals">
                        <div class="register-payment-block register-totals">
                            <div class="register-summary">
                                <table class="totals">
                                    <tbody>
                                    <tr>
                                        <th>Subtotal</th>
                                        <td id="popdisplay_subtotal" class="money">$<?= number_format($sale->subtotal, 2) ?></td>
                                    </tr>
                                    <tr class="discount">
                                        <th>Discounts</th>
                                        <td id="discountValue" class="money">$-<?= number_format($sale->discount, 2) ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table>
                                </table>
                            </div>
                            <div class="register-summary register-payment-detail">
                                <table>
                                    <tbody>
                                    <tr class="total ">
                                        <th>Total</th>
                                        <td id="sale_total">$<?= number_format($sale->total, 2) ?></td>
                                    </tr>
                                    <tr id="totals_title_whitespace"></tr>

                                    <?php $total_without_before = $sale->total; ?>
                                    <?php if ($sale->total_paid > 0) : ?>
                                    <?php $total_without_before = $sale->total - $sale->total_paid; ?>
                                    <tr class="partial-payments">
                                        <th>Payments from Before</th>
                                        <td id="payments_total3" data-total-paid="<?= $sale->total_paid ?>">$<?= number_format($sale->total_paid, 2) ?></td>
                                    </tr>
                                    <?php endif; ?>

                                    <tr class="payments">
                                        <th>Payments</th>
                                        <td id="payments_total2">$0.00</td>
                                    </tr>
                                    <tr class="balance">
                                        <th>Balance</th>
                                        <td id="sale_balance" class="red">$<?= number_format($total_without_before, 2) ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="register-payment-block">
                        <button id="finishSaleButton" name="finish" type="submit" tabindex="19" class="cr-button cr-button--primary cr-button--large cr-button--fill">
                            <span class="cr-button__content">Finish Sale</span>
                        </button>
                    </div>
                    <div class="register-sidebar-buttons">
                        <a href="<?= HOST_NAME.'pos/sale_continue/'.$sale->id ?>" id="backToSaleButton" class="cr-button cr-button--large" style="line-height: 2.4;">
                            <span class="cr-button__content">Back To Sale</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>


    </form>

    <?php if (isset($customer) && $customer) : ?>
    <div class="add-deposit modal fade" id="customer-account-payment-modal">
        <div class="modal-dialog">
            <form method="post" id="customer-account-payment-form" action="<?= HOST_NAME.'ajax/add_deposit/'.$sale->id ?>">
                <input type="hidden" name="customer_id" value="<?= $customer->id ?>">

                <div class="modal-content">
                    <div class="add-deposit-header modal-header">
                        <h4 class="modal-title" style="color: #444">Account Payment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="add-deposit-fill-block"></div>
                    <div class="modal-body clearfix add-deposit-body" style="padding: 20px;">
                        <div class="add-deposit-customer add-deposit-item">
                            <div class="add-deposit-customer-title"><strong>Credit Account Balance</strong></div>
                            <div class="add-deposit-customer-name notranslate"><?= $sale->customer_name ?></div>
                            <div><strong class="add-deposit-credit-balance notranslate">$-<?= number_format($customer->owed, 2) ?></strong></div>
                        </div>

                        <div class="add-deposit-item">
                            <div class="cr-form-field">
                                <div class="cr-text-base cr-text--body-small cr-text--body-color cr-text--bold cr-form-field__text-label">Payment Amount</div>
                                <div class="cr-group cr-group--no-spacing">
                                    <div class="cr-group__item cr-group__item--addon">
                                        <div>$</div>
                                    </div>
                                    <div class="cr-group__item cr-group__item--input-block">
                                        <div class="cr-input__container">
                                            <label for="deposit-amount" class="cr-input__wrapper" style="margin: 0">
                                                <input id="deposit-amount" name="deposit-amount" placeholder="Deposit Amount..." class="cr-input">
                                                <div class="cr-input__backdrop"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-light" name="submit">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

</div>
<script>
    $(document).ready(function () {
        var $total = <?= $sale->total ?>;

        // $('#payment-form').on('submit', function (e) {
        //     var $input_total = getInputsTotal();
        //     if ($input_total < $total) {
        //         showFeedback('error', "Payments are insufficient.");
        //         e.preventDefault();
        //     }
        // });

        // $('.cashBillButton').on('click', function (e) {
        //     var $amount = parseFloat($(this).data('cash-value'));
        //     var cash = parseFloat($('#cash_payment').val());
        //     var total_cash = cash + $amount;
        //     $('#cash_payment').val(formatMoney(total_cash));
        //
        //     updatePaymentBalance();
        // });

        $('.maxButton').on('click', function (e) {
            var $closest_input = $(this).parent().find('.payment-block-money input');
            $closest_input.val(0);

            var $inputs_total = getInputsTotal();
            $closest_input.val(formatMoney($total - $inputs_total));
            updatePaymentBalance();
        });

        $('#CreditAccountMaxButton').on('click', function (e) {
            var input = $('#charge_account_payment');
            input.val(0);
            var $credit_limit = $(this).data('credit-limit');
            var $inputs_total = getInputsTotal();

            if (($total - $inputs_total) > $credit_limit) {
                input.val(formatMoney($credit_limit));
            } else {
                input.val(formatMoney($total - $inputs_total));
            }
            updatePaymentBalance();
        });

        $('.payment-block-money input').on('input', function (e) {
            if ($(this).data('payment-type') == 'account') {
                var input = $(this).val();
                var account_input = $('#charge_account_payment');
                var $credit_limit = $('#CreditAccountMaxButton').data('credit-limit');

                if (input > $credit_limit) {
                    account_input.val(0);
                    var $inputs_total = getInputsTotal();

                    var account_new_value = ($total - $inputs_total) <= $credit_limit ? $total - $inputs_total : $credit_limit;

                    account_input.val(formatMoney(account_new_value));
                }
            }

            updatePaymentBalance();
        });

        
        function updatePaymentBalance() {
            var $inputs_total = getInputsTotal();
            var $inputs_total_without_before = $inputs_total;
            if ($('.partial-payments').length) {
                var paid_before = $('.partial-payments #payments_total3').data('total-paid');
                $inputs_total_without_before = paid_before ? $inputs_total - parseFloat(paid_before) : $inputs_total;
            }

            $('#payments_total').val(formatMoney($inputs_total));
            $('#payments_total2').html('$'+ formatMoney($inputs_total_without_before));

            var balance = $total - $inputs_total >= 0 ? $total - $inputs_total : 0;
            $('#sale_balance').html('$'+ String(formatMoney(balance)));

            if ($inputs_total > $total) {
                $('#cash_remainder').removeClass('hidden');
                $('#cash_remainder #cash_remainder_balance').html('$' + String(formatMoney($total - $inputs_total)).replace('-', ''));
            } else {
                $('#cash_remainder').addClass('hidden');
                $('#cash_remainder #cash_remainder_balance').html('');
            }
        }

        function getInputsTotal() {
            var $money_inputs = $('.payment-block-money input');
            var $inputs_total = 0;
            for (var i = 0; i < $money_inputs.length; i++) {
                $inputs_total += parseFloat($($money_inputs[i]).val());
            }

            if ($('.partial-payments').length) {
                var paid_before = $('.partial-payments #payments_total3').data('total-paid');
                $inputs_total = paid_before ? $inputs_total + parseFloat(paid_before) : $inputs_total;
            }

            return $inputs_total;
        }
    });
</script>

<?php endif; ?>