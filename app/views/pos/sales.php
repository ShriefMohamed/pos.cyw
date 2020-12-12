<nav id="section" style="display: block;">
    <div class="register">
        <div id="register_tab">

            <?php if (isset($register) && $register !== false) : ?>
            <article id="register_current_sale_section" class="section">
                <h2>Current Sale</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>pos/sale_continue_check"><button><i class="fa fa-reply"></i><span>Continue Sale</span></button></a>
                        <p>Continue with the current sale.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/sale_add"><button><i class="fa fa-plus"></i><span>New Sale</span></button></a>
                        <p>Leave the current transaction open and start a new sale.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/"><button><i class="fa fa-star"></i><span>Special Order</span></button></a>
                        <p>Create a special order.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/"><button><i class="fa fa-umbrella"></i><span>Layaway</span></button></a>
                        <p>Create a new layaway.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/sale_refund"><button><i class="fa fa-ticket-alt"></i><span>Refund</span></button></a>
                        <p>Add a refund to the current sale.</p>
                    </li>
                </ul>
            </article>
            <article id="register_current_register_section" class="section">
                <h2>Current Register is <var>'Register 1'</var></h2>
                <p id="register_current_register_section_explanation">The register is where you do sales and refunds. You are currently using <var>'Register 1'</var>&nbsp; at <var>'CYW'</var>.</p>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>pos/register_close"><button><i class="fa fa-power-off"></i><span>Close Register</span></button></a>
                        <p>Close the register and count the cash in your till.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/register_cash_remove"><button><i class="fa fa-minus"></i><span>Payout / Drop</span></button></a>
                        <p>Remove cash from the register's till.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/register_cash_add"><button><i class="fa fa-plus"></i><span>Add Amount</span></button></a>
                        <p>Add cash to the register's till.</p>
                    </li>
                </ul>
            </article>
            <?php else : ?>
            <article id="register_choose_register_section" class="section">
                <h2>Open Register</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>pos/register_open"><button id="registerButton_0"><i class="fa fa-inbox"></i><span><span>Register 1</span></span></button></a>
                    </li>
                </ul>
            </article>
            <?php endif; ?>

            <article id="recent_records_sales_section" class="section">
                <h2>Sales History</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>pos/sales_list"><button><i class="fa fa-history"></i><span>Sales history</span></button></a>
                        <p>View & manage all sales (paid, unpaid, quotes)</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/sales_list?status=awaiting_payment"><button><i class="fa fa-history"></i><span>Un-Paid Sales</span></button></a>
                        <p>View & manage un-paid sales.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/sales_list?status=partial_payment"><button><i class="fa fa-history"></i><span>Partially-Paid Sales</span></button></a>
                        <p>View & manage partially-paid/unfinished sales.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/sales_list?type=quote"><button><i class="fa fa-history"></i><span>Saved Quotes</span></button></a>
                        <p>View & manage saved quotes (Held Receipts).</p>
                    </li>
                </ul>
            </article>

            <article id="recent_records_sales_section" class="section">
                <h2>Quotes History (Quotes System)</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>pos/quotes"><button><i class="fa fa-history"></i><span>Quotes history</span></button></a>
                        <p>View & manage all quotes (approved, completed)</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/quotes?status=approved"><button><i class="fa fa-history"></i><span>Approved Quotes</span></button></a>
                        <p>View & manage approved quotes.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/quotes?status=complete"><button><i class="fa fa-history"></i><span>Completed Quotes</span></button></a>
                        <p>View & manage completed quotes.</p>
                    </li>
                </ul>
            </article>
        </div>
    </div>
</nav>