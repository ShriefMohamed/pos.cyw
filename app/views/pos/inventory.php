<nav id="section" style="display: block;">
    <div class="inventory">
    <div id="inventory_tab" style="">
        <article id="inventory_inventory_section" class="section">
            <h2>Items &amp; Inventory</h2>
            <ul class="options">
                <li>
                    <a href="<?= HOST_NAME ?>pos/items"><button><i class="fa fa-search"></i><span>All Items</span></button></a>
                    <p>List/Search items and view or edit their details.</p>
                </li>
                <li>
                    <a href="<?= HOST_NAME ?>pos/item_add"><button><i class="fa fa-plus"></i><span>New Item</span></button></a>
                    <p>Create new items and add inventory at the same time.</p>
                </li>
                <li>
                    <a href="<?= HOST_NAME ?>pos/items_labels"><button><i class="fa fa-tag"></i><span>Print Labels</span></button></a>
                    <p>View your list of item labels to print.</p>
                </li>
                <li>
                    <a href="<?= HOST_NAME ?>pos/items_serial_numbers"><button><i class="fa fa-terminal"></i><span>Serial Numbers</span></button></a>
                    <p>Search for serial numbers, in stock or sold.</p>
                </li>

                <li>
                    <a href="<?= HOST_NAME ?>pos/items_import"><button><i class="fa fa-reply"></i><span>Import Items</span></button></a>
                    <p>Import items from xls export file.</p>
                </li>
            </ul>
        </article>
        <article id="inventory_purchase_orders_section" class="section">
            <h2>Purchase Orders, and Shipping</h2>
            <ul class="options">
                <li>
                    <a href="<?= HOST_NAME ?>pos/purchase_orders"><button><i class="fa fa-shopping-cart"></i><span>Purchase Orders</span></button></a>
                    <p>Manage, check-in and review orders.</p>
                </li>
                <li>
                    <a href="<?= HOST_NAME ?>pos/purchase_order_add"><button><i class="fa fa-plus"></i><span>New Order</span></button></a>
                    <p>Create and add items and special orders to your order.</p>
                </li>
                <li>
                    <a href="<?= HOST_NAME ?>pos/vendor_returns"><button><i class="fa fa-arrow-right"></i><span>Vendor Return</span></button></a>
                    <p>Return items to vendors</p>
                </li>
                <li>
                    <a href="<?= HOST_NAME ?>pos/shipments"><button><i class="fa fa-truck"></i><span>Shipping</span></button></a>
                    <p>A list of what needs to be shipped to customers.</p>
                </li>
            </ul>
        </article>
        <article id="inventory_maintenance_section" class="section">
            <h2>Inventory Maintenance</h2>
            <ul class="options">
                <li>
                    <a href="<?= HOST_NAME ?>pos/inventory_counts"><button><i class="fa fa-signal"></i><span>Inventory Counts</span></button></a>
                    <p>Count your inventory and reconcile your counts with the system totals.</p>
                </li>
            </ul>
        </article>
        <article id="inventory_inventory_settings_section" class="section">
            <h2>Inventory Settings</h2>
            <ul class="options">
                <li>
                    <a href="<?= HOST_NAME ?>pos/vendors"><button><i class="fa fa-bullseye"></i><span>Vendors</span></button></a>
                    <p>Vendors are your merchandise suppliers.</p>
                </li>
                <li>
                    <a href="<?= HOST_NAME ?>pos/categories"><button><i class="fa fa-columns"></i><span>Categories</span></button></a>
                    <p>Use categories to organize your items and analyze business in different segments.</p>
                </li>
                <li>
                    <a href="<?= HOST_NAME ?>pos/tags"><button><i class="fa fa-tags"></i><span>Tags</span></button></a>
                    <p>Organize your items with tags.</p>
                </li>
                <li>
                    <a href="<?= HOST_NAME ?>pos/brands"><button><i class="fa fa-certificate"></i><span>Brands</span></button></a>
                    <p>View, edit, and create item brands.</p>
                </li>
                <li>
                    <a href="<?= HOST_NAME ?>pos/vendor_return_reasons"><button><i class="fa fa-list"></i><span>Vendor Return Reasons</span></button></a>
                    <p>Manage reasons available for products returned to vendor.</p>
                </li>
                <li>
                    <a href="<?= HOST_NAME ?>xero/accounts"><button><i class="fa fa-list"></i><span>Xero Accounts</span></button></a>
                    <p>Manage imported Xero accounts.</p>
                </li>
            </ul>
        </article>
    </div>
</div>
</nav>