<div id="register" style="display: block;margin-bottom: 5rem;">
    <div>
        <form method="post" class="quick">
            <h2 id="updateRegisterCountTitle"><span id="registerName">Register 1</span>: Payout	</h2>

            <dl class="lines narrow">
                <dt><label id="updateCountLabel">Pay Out</label></dt>

                <?php $this->RenderPart('_register_count'); ?>

                <dt><label>Type</label></dt>
                <dd>
                    <select name="payment_type" id="payment_type_control">
                        <option value="cash" selected="selected">Cash</option>
                        <?php if (isset($payment_methods) && $payment_methods) : ?>
                            <?php foreach ($payment_methods as $payment_method) : ?>
                                <option value="<?= $payment_method->method_key ?>"><?= $payment_method->method ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </dd>

                <dt><label>Note</label></dt>
                <dd><input id="noteInputField" type="text" name="notes" size="35" maxlength="255" autocomplete="off"></dd>
            </dl>

            <div class="submit">
                <button id="submitCountButton" type="submit" name="submit" class="gui-def-button" onclick="e.preventDefault(); recalc_total();">Withdraw</button>
                <a href="<?= HOST_NAME ?>pos/sales"><button type="button" id="cancelButton" style="text-decoration: none;" class="gui-def-button">Cancel</button></a>
            </div>
        </form>
    </div>
</div>