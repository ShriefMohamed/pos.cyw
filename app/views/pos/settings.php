<nav id="section" data-test="section-wrapper" style="display: block;">
    <div class="admin">
        <div id="admin_tab">
            <article id="admin_sales_setup_section" class="section">
                <h2>Sales</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>pos/payment_methods"><button><i class="fa fa-list-alt"></i><span>Payment Types</span></button>
                        <p>Cash, credit card, check...</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/tax_classes"><button><i class="fa fa-list"></i><span>Tax Classes</span></button></a>
                        <p>Labor, miscellaneous, items, etc. Have different tax rates for each.</p>
                    </li>
                </ul>
            </article>
            <article id="admin_pricing_setup_section" class="section">
                <h2>Pricing</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>pos/pricing_levels"><button><i class="fa fa-dollar-sign"></i><span>Pricing Levels</span></button></a>
                        <p>Products can have multiple prices.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/discounts"><button><i class="fa fa-chevron-down"></i><span>Discounts</span></button></a>
                        <p>Discounts can be for a percentage of an item or total sale or a dollar amount.</p>
                    </li>
                </ul>
            </article>
        </div>
    </div>
</nav>