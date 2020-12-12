<nav id="section" style="display: block;">
    <div class="inventory">
        <div id="inventory_tab" style="">
            <article id="inventory_inventory_section" class="section">
                <h2>API Actions</h2>
                <ul class="options">
                    <li class="dashboard-btn-full-width">
                        <a href="<?= HOST_NAME ?>quotes/cron/leader_items"><button><i class="fa fa-reply"></i><span>Sync Quote's Items</span></button></a>
                        <p>Sync quote's items with leadersystems items.</p>
                    </li>
                    <li class="dashboard-btn-full-width">
                        <a href="<?= HOST_NAME ?>quotes/cron/neto_items"><button><i class="fa fa-reply"></i><span>Sync Neto Products</span></button></a>
                        <p>Sync Neto Products' stock & prices with LeaderSystems. ("Quote's items Sync" first is highly recommended)</p>
                    </li>
                    <li class="dashboard-btn-full-width">
                        <a href="<?= HOST_NAME ?>quotes/cron/expired_quotes"><button><i class="fa fa-reply"></i><span>Re-Check Expired Quotes</span></button></a>
                        <p>Check quotes dates and mark as expired, if any.</p>
                    </li>
                </ul>
            </article>
            <article id="inventory_purchase_orders_section" class="section">
                <h2>Quotes</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>quotes/quotes"><button><i class="fa fa-list"></i><span>Quotes</span></button></a>
                        <p>List/Search quotes, and view their details.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>quotes/quotes?ex=1"><button><i class="fa fa-list"></i><span>Expired Quotes</span></button></a>
                        <p>List/Search expired quotes, and view their details.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>quotes/quote_add"><button><i class="fa fa-plus"></i><span>Add Quote</span></button></a>
                        <p>Create new quote.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>quotes/quote_template"><button><i class="fa fa-book"></i><span>Quote Template</span></button></a>
                        <p>Update the docx template used for all quotes (the template which is sent to customers).</p>
                    </li>
                </ul>
            </article>
        </div>
    </div>
</nav>