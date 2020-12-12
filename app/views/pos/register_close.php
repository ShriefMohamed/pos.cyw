<?php if (isset($page)) : ?>
<?php if ($page == 'count') : ?>
    <div id="register">
        <div>
            <form method="post" class="quick">
                <input type="hidden" name="form_name" value="count">
                <h2 id="closeRegisterTitle">Register Closing Totals</h2>
                <?php if (isset($counts) && !empty($counts)) : ?>
                    <table id="closeRegisterCountTable" class="grid" style="width: 100%">
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th>Start+Adds</th>
                            <th>Payments</th>
                            <th>Withdraws</th>
                            <th>Total Remaining</th>
                            <th>Closing Count</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr id="registerLineItemCash">
                            <th >Cash</th>
                            <td class="number">$<?= isset($counts['cash']['open_add']) ? number_format($counts['cash']['open_add'], 2) : 0.00 ?></td>
                            <td class="number">$<?= isset($counts['cash']['payments']) ? number_format($counts['cash']['payments'], 2) : 0.00 ?></td>
                            <td class="number">$-<?= isset($counts['cash']['remove']) ? number_format($counts['cash']['remove'], 2) : 0.00 ?></td>
                            <td class="number">
                                <input type="hidden" name="counts[cash][calculated]" value="<?= isset($counts['cash']['remaining']) ? number_format($counts['cash']['remaining'], 2) : 0.00 ?>">
                                $<?= isset($counts['cash']['remaining']) ? number_format($counts['cash']['remaining'], 2) : 0.00 ?>
                            </td>
                            <td style="text-align: right;">
                                <?php $this->RenderPart('_register_count'); ?>
                            </td>
                        </tr>
                        <tr id="registerLineItemCreditAccount">
                            <th >Credit Account</th>
                            <td class="number">$<?= isset($counts['account']['open_add']) ? number_format($counts['account']['open_add'], 2) : 0.00 ?></td>
                            <td class="number">$<?= isset($counts['account']['payments']) ? number_format($counts['account']['payments'], 2) : 0.00 ?></td>
                            <td class="number">$-<?= isset($counts['account']['remove']) ? number_format($counts['account']['remove'], 2) : 0.00 ?></td>
                            <td class="number">
                                <input type="hidden" name="counts[account][calculated]" value="<?= isset($counts['account']['remaining']) ? number_format($counts['account']['remaining'], 2) : 0.00 ?>">
                                $<?= isset($counts['account']['remaining']) ? number_format($counts['account']['remaining'], 2) : 0.00 ?>
                            </td>

                            <td class="number">
                                <input type="hidden" name="counts[account][counted]" value="<?= isset($counts['account']['remaining']) ? number_format($counts['account']['remaining'], 2) : 0.00 ?>">
                                $<?= isset($counts['account']['remaining']) ? number_format($counts['account']['remaining'], 2) : 0.00 ?>
                            </td>
                        </tr>

                        <?php foreach ($counts as $key => $count) : ?>
                            <?php if ($key != 'cash' && $key != 'account') : ?>
                                <tr id="registerLineItem<?= $key ?>">
                                    <th><?= $count['method'] ?></th>
                                    <td class="number">$<?= isset($count['open_add']) ? number_format($count['open_add'], 2) : 0.00 ?></td>
                                    <td class="number">$<?= isset($count['payments']) ? number_format($count['payments'], 2) : 0.00 ?></td>
                                    <td class="number">$<?= isset($count['remove']) ? number_format($count['remove'], 2) : 0.00 ?></td>
                                    <td class="number">
                                        <input type="hidden" name="counts[<?= $key ?>][method]" value="<?= isset($count['method']) ? $count['method'] : ucfirst($key) ?>">
                                        <input type="hidden" name="counts[<?= $key ?>][calculated]" value="<?= isset($count['remaining']) ? number_format($count['remaining'], 2) : 0.00 ?>">
                                        $<?= isset($count['remaining']) ? number_format($count['remaining'], 2) : 0.00 ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <div class="money-field-container">
                                            <input class="class_record_item x-small" type="text" step=".01" maxlength="11" name="counts[<?= $key ?>][counted]" value="0.00" autocomplete="off">
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if (isset($totals) && !empty($totals)) : ?>
                            <tr id="registerLineItemTotals" class="totals">
                                <th >Totals</th>
                                <td class="number">$<?= number_format($totals['open_add'], 2) ?></td>
                                <td class="number">$<?= number_format($totals['payments'], 2) ?></td>
                                <td class="number">$-<?= number_format($totals['remove'], 2) ?></td>
                                <td class="number">$<?= number_format($totals['remaining'], 2) ?></td>
                                <td></td>
                            </tr>
                        <?php endif; ?>

                        </tbody>
                    </table>
                <?php endif; ?>
                <div class="submit">
                    <button id="submitCountsButton" type="submit" name="submit" class="gui-def-button">Submit Counts</button>
                    <a href="<?= HOST_NAME ?>pos/sales"><button type="button" id="cancelButton" style="text-decoration: none;" class="gui-def-button">Cancel</button></a>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            setTimeout("recalc_total();", 250);

            $('.cash_count').on('input change mousewheel', function (e) {
                if ($(this).val() < 0) {
                    $(this).val(0);
                }
                recalc_total();
            });
        });
    </script>
<?php elseif ($page == 'closing_counts') : ?>
    <div id="notificationBarTypeMessage" class="alert-messages alert highlight small" style="padding: 0">
        <i class="fa fa-exclamation-circle"></i>
        <ul>
            <li class="alert-messages_inner"></li>
            <li>Please verify closing counts for register.</li>
        </ul>
    </div>
    <?php if (isset($counts) && !empty($counts)) : ?>
        <div id="register">
            <form method="post" class="quick">
                <h2 id="closeRegisterTitle">Register Closing Totals</h2>
                <input type="hidden" name="form_name" value="closing_counts">
                <table id="reviewCountsTable" class="grid" style="width: 100%">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Calculated</th>
                        <th>Counted</th>
                        <th>Short/Over</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr id="registerLineItemCash">
                        <th>Cash</th>
                        <td class="number">$<?= isset($counts['cash']['calculated']) ? $counts['cash']['calculated'] : 0.00 ?></td>
                        <td class="number">$<?= isset($counts['cash']['counted']) ? $counts['cash']['counted'] : 0.00 ?>
                            <input type="hidden" name="counted[cash]" value="<?= isset($counts['cash']['counted']) ? $counts['cash']['counted'] : 0.00 ?>">
                        </td>
                        <td class="number" style="<?= isset($counts['cash']['diff']) && $counts['cash']['diff'] > 0 ? 'color: green' : 'color: red;' ?>">$<?= isset($counts['cash']['diff']) ? $counts['cash']['diff'] : 0.00 ?></td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                    <tr id="registerLineItemCreditAccount">
                        <th>Credit Account</th>
                        <td class="number">$<?= isset($counts['account']['calculated']) ? $counts['account']['calculated'] : 0.00 ?></td>
                        <td class="number">$<?= isset($counts['account']['counted']) ? $counts['account']['counted'] : 0.00 ?>
                            <input type="hidden" name="counted[account]" value="<?= isset($counts['account']['counted']) ? $counts['account']['counted'] : 0.00 ?>">
                        </td>
                        <td class="number">$<?= isset($counts['account']['diff']) ? $counts['account']['diff'] : 0.00 ?></td>
                    </tr>

                    <?php foreach ($counts as $key => $count) : ?>
                    <?php if ($key != 'cash' && $key != 'account') : ?>
                        <tr id="registerLineItemCreditAccount">
                            <th><?= isset($count['method']) && $count['method'] ? $count['method'] : ucfirst($key) ?></th>
                            <td class="number">$<?= isset($count['calculated']) ? $count['calculated'] : 0.00 ?></td>
                            <td class="number">$<?= isset($count['counted']) ? $count['counted'] : 0.00 ?>
                                <input type="hidden" name="counted[<?= $key ?>]" value="<?= isset($count['counted']) ? $count['counted'] : 0.00 ?>">
                            </td>
                            <td class="number" style="<?= isset($count['diff']) && $count['diff'] > 0 ? 'color: green' : 'color: red;' ?>">$<?= isset($count['diff']) ? $count['diff'] : 0.00 ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php endforeach; ?>

                    </tbody>
                </table>

                <dl class="lines narrow">
                    <dt><label for="description">Note</label></dt>
                    <dd><input id="registerNoteInputField" type="text" name="notes" size="60" maxlength="255" autocomplete="off" value=""></dd>
                </dl>

                <div class="submit" style="margin-top: 2rem;">
                    <button id="saveCountsButton" type="submit" class="gui-def-button"><i class="fa fa-save"></i> Save Counts</button>
                    <button id="redoCountsButton" type="button" onclick="window.history.back();" class="gui-def-button"><i class="fa fa-refresh"></i> Redo Counts</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php endif; ?>

