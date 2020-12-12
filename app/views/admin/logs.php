<nav id="section" style="display: block;">
    <div class="inventory">
        <div id="inventory_tab" style="">
            <article id="inventory_inventory_section" class="section">
                <h2>Logs</h2>
                <ul class="options row">
                    <li>
                        <a href="<?= HOST_NAME ?>admin/log/jobs"><button><i class="fa fa-reply"></i><span>Jobs System Logs</span></button></a>
                        <p>All Logs related to Jobs System</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/log/pos"><button><i class="fa fa-reply"></i><span>POS Logs</span></button></a>
                        <p>All Logs related to POS</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/log/quotes"><button><i class="fa fa-reply"></i><span>Quotes System Logs</span></button></a>
                        <p>All Logs related to Quotes System</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/log/cron"><button><i class="fa fa-reply"></i><span>Cron Jobs Logs</span></button></a>
                        <p>All Logs recorded while running a cron job</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/log/database"><button><i class="fa fa-reply"></i><span>Database Logs</span></button></a>
                        <p>All Error Logs while doing a database operation.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/log/emails"><button><i class="fa fa-reply"></i><span>Emails Logs</span></button></a>
                        <p>All Error Logs recorded while sending emails.</p>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/log/logs"><button><i class="fa fa-reply"></i><span>Other Logs</span></button></a>
                        <p>Other logs.</p>
                    </li>
                </ul>
            </article>
        </div>
    </div>
</nav>