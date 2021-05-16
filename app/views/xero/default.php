<nav id="section" style="display: block;">
    <div class="inventory">
        <div id="inventory_tab" style="">
            <article id="inventory_inventory_section" class="section">
                <h2>Customers</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>xero/customers"><button><i class="fa fa-search"></i><span>Xero Customers</span></button></a>
                        <p>List/Search customers imported from xero, and view or edit their details.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>customers/customer_add"><button><i class="fa fa-plus"></i><span>New Customer</span></button></a>
                        <p>Create new customer.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>xero/sync/customers"><button><i class="fa fa-reply"></i><span>Sync Customers</span></button></a>
                        <p>Sync customers details with xero and import any new customers from there.</p>
                    </li>
                </ul>
            </article>
            <article id="inventory_inventory_section" class="section">
                <h2>Invoices</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>xero/invoices"><button><i class="fa fa-search"></i><span>Xero Invoices</span></button></a>
                        <p>List/Search invoices imported from xero, and view or edit their details.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>xero/sync/invoices"><button><i class="fa fa-reply"></i><span>Sync Invoices</span></button></a>
                        <p>Sync invoices with xero and import any new invoices from there.</p>
                    </li>
                </ul>
            </article>
            <article id="inventory_inventory_section" class="section">
                <h2>Items</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>xero/items"><button><i class="fa fa-search"></i><span>Xero Items</span></button></a>
                        <p>List/Search items imported from xero, and view or edit their details.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/item_add"><button><i class="fa fa-plus"></i><span>New Item</span></button></a>
                        <p>Create new customer.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>xero/sync/items"><button><i class="fa fa-reply"></i><span>Sync Items</span></button></a>
                        <p>Sync items with xero and import any new items from there.</p>
                    </li>
                </ul>
            </article>
            <article id="inventory_purchase_orders_section" class="section">
                <h2>Accounts</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>xero/accounts"><button><i class="fa fa-list"></i><span>All Accounts</span></button></a>
                        <p>List/Search accounts imported from xero, and view their details.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>xero/sync/accounts"><button><i class="fa fa-reply"></i><span>Sync Accounts</span></button></a>
                        <p>Sync accounts details with xero and import any new accounts from there.</p>
                    </li>
                </ul>
            </article>

        </div>
    </div>
</nav>