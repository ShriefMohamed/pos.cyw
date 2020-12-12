<nav id="section" style="display: block;">
    <div class="register">
        <div id="register_tab">

            <article class="section">
                <h2>REPAIR JOBS</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>admin/jobs"><button><i class="oi oi-dashboard"></i><span>All Jobs</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/jobs?status=trash"><button><i class="fa fa-trash"></i><span>Trash Jobs</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/job_add"><button><i class="fa fa-plus"></i><span>New Job</span></button></a>
                    </li>
                </ul>
            </article>

            <article class="section">
                <h2>QUOTE JOBS</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>admin/quote_jobs"><button><i class="oi oi-dashboard"></i><span>All Jobs</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/quote_jobs?status=complete"><button><i class="fa fa-search"></i><span>Completed Jobs</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>quotes/quote_add"><button><i class="fa fa-plus"></i><span>New Quote</span></button></a>
                    </li>
                </ul>
            </article>

            <article class="section">
                <h2>USERS</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>admin/users"><button><i class="fa fa-user"></i><span>All Users</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/users?type=admin"><button><i class="fa fa-user"></i><span>Administrators</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/users?type=technician"><button><i class="fa fa-user"></i><span>Technicians</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/users?type=customer"><button><i class="fa fa-user"></i><span>Customers</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>pos/user_add"><button><i class="fa fa-plus"></i><span>New User</span></button></a>
                    </li>
                </ul>
            </article>

            <article class="section">
                <h2>SETTINGS</h2>
                <ul class="options">
                    <li>
                        <a href="<?= HOST_NAME ?>admin/stages"><button><i class="oi oi-file"></i><span>Repair Stages</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/insurance_reports"><button><i class="oi oi-file"></i><span>Insurance Reports</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/insurance"><button><i class="oi oi-file"></i><span>Insurance Companies</span></button></a>
                    </li>
                    <li>
                        <a href="<?= HOST_NAME ?>admin/insurance_add"><button><i class="fa fa-plus"></i><span>New Insurance Company</span></button></a>
                    </li>
                </ul>
            </article>
        </div>
    </div>
</nav>