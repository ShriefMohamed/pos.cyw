<dd>
    <table class="narrow count">
        <tbody>
        <tr>
            <td><span>$100</span>  ×</td>
            <td>
                <input id="cash_count_1" class="xx-small cash_count" type="number" maxlength="4" style="text-align: right;" value="" name="cash_count_1" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td><span>$50</span>  ×</td>
            <td>
                <input id="cash_count_2" class="xx-small cash_count" type="number" maxlength="4" style="text-align: right;" value="" name="cash_count_2" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td><span>$20</span>  ×</td>
            <td>
                <input id="cash_count_3" class="xx-small cash_count" type="number" maxlength="4" style="text-align: right;" value="" name="cash_count_3" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td><span>$10</span>  ×</td>
            <td>
                <input id="cash_count_4" class="xx-small cash_count" type="number" maxlength="4" style="text-align: right;" value="" name="cash_count_4" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td><span>$5</span>  ×</td>
            <td>
                <input id="cash_count_5" class="xx-small cash_count" type="number" maxlength="4" style="text-align: right;" value="" name="cash_count_5" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td><span>$1</span>  ×</td>
            <td>
                <input id="cash_count_6" class="xx-small cash_count" type="number" maxlength="4" style="text-align: right;" value="" name="cash_count_6" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td><span>50¢</span>  ×</td>
            <td>
                <input id="cash_count_7" class="xx-small cash_count" type="number" maxlength="4" style="text-align: right;" value="" name="cash_count_7" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td><span>20¢</span>  ×</td>
            <td>
                <input id="cash_count_8" class="xx-small cash_count" type="number" maxlength="4" style="text-align: right;" value="" name="cash_count_8" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td><span>10¢</span>  ×</td>
            <td>
                <input id="cash_count_9" class="xx-small cash_count" type="number" maxlength="4" style="text-align: right;" value="" name="cash_count_9" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td><span>5¢</span>  ×</td>
            <td>
                <input id="cash_count_10" class="xx-small cash_count" type="number" maxlength="4" style="text-align: right;" value="" name="cash_count_10" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td>Extra</td>
            <td>
                <div class="money-field-container">
                    <input id="cash_count_extra" type="text" class="money cash_count" step="0.01" value="0.00" name="extra" size="15" maxlength="15" autocomplete="off">
                </div>
            </td>
        </tr>
        <tr>
            <td>Total</td>
            <td>
                <div class="money-field-container">
                    <input class="money" name="total" id="cash_count_total" type="text" style="text-align: right;" maxlength="11" value="0.00" readonly>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</dd>
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