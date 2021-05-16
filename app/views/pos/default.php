<nav id="section" style="display: block;">
    <div class="register">
        <div id="register_tab">

            <article class="section">
                <h2>SALES</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>pos/sales"><button><i class="oi oi-dashboard"></i><span>Sales Dashboard</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/sales_list"><button><i class="fa fa-history"></i><span>Sales history</span></button></a>
                        <p>View & manage all sales (paid, unpaid, quotes)</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/invoices"><button><i class="fa fa-newspaper"></i><span>Invoices</span></button></a>
                        <p>View & manage all invoices (paid, unpaid)</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/sale_add"><button><i class="fa fa-plus"></i><span>New Sale</span></button></a>
                        <p>Leave the current transaction open and start a new sale.</p>
                    </li>
                </ul>
            </article>

            <article class="section">
                <h2>ITEMS & INVENTORY</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>pos/inventory"><button><i class="oi oi-dashboard"></i><span>Inventory Dashboard</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/items"><button><i class="fa fa-search"></i><span>All Items</span></button></a>
                        <p>List/Search items and view or edit their details.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/item_add"><button><i class="fa fa-plus"></i><span>New Item</span></button></a>
                        <p>Create new items and add inventory at the same time.</p>
                    </li>
                </ul>
            </article>
            <article class="section">
                <h2>SETTINGS</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>pos/settings"><button><i class="oi oi-dashboard"></i><span>Settings Dashboard</span></button></a>
                        <p>Payment Methods, Tax Classes, Pricing Levels, Discounts</p>
                    </li>
                </ul>
            </article>
        </div>
    </div>
</nav>