<div id="register">
    <div>
        <form name="edit_record_item" method="post" class="quick">
            <h2 id="openRegisterTitle">Open Register: <span id="registerName">Register 1</span></h2>
            <dl class="lines">
                <dt><label id="openingCountLabel">Opening Count</label></dt>
                <?php $this->RenderPart('_register_count'); ?>

            </dl>
            <div class="submit">
                <button id="submitCountButton" type="submit" name="submit" class="gui-def-button" onclick="e.preventDefault(); recalc_total();">Submit Count</button>
                <a href="<?= HOST_NAME ?>pos/sales"><button type="button" id="cancelButton" style="text-decoration: none;" class="gui-def-button">Cancel</button></a>
            </div>
        </form>
    </div>
</div>