<div class="wrapper">
    <!-- Sidebar Holder -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="images/MPIC_logo.png" alt="" class="img-responsive logo">
        </div>

        <ul class="list-unstyled components">
            <p>Hello, <?= $_SESSION['mpic_mpic_name']; ?></p>
            <li>
                <a href="index">Home</a>
            </li>
            <?php
            if ($_SESSION['mpic_mpic_role'] == "Viewer" || $_SESSION['mpic_mpic_role'] == "Site Admin") { } else {
                ?>
                <li>
                    <a href="#nav_comp" data-toggle="collapse" aria-expanded="false"><i class="fa fa-cog"></i> Manage Company</a>
                    <ul class="collapse list-unstyled" id="nav_comp">
                        <li><a href="add_company"><i class="fa fa-plus"></i> Add Company</a></li>
                        <li><a href="deleted_company"><i class="fa fa-trash"></i> Deleted Company</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#nav_corp" data-toggle="collapse" aria-expanded="false"><i class="fa fa-cogs"></i> Manage Corporation</a>
                    <ul class="collapse list-unstyled" id="nav_corp">
                        <li><a href="add_corporation"><i class="fa fa-plus"></i> Add Corporation</a></li>
                        <li><a href="deleted_corporation"><i class="fa fa-trash"></i> Deleted Corporation</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#nav_ind" data-toggle="collapse" aria-expanded="false"><i class="fa fa-user"></i> Manage Individual</a>
                    <ul class="collapse list-unstyled" id="nav_ind">
                        <li><a href="add_individual"><i class="fa fa-plus"></i> Add Individual</a></li>
                        <li><a href="deleted_individual"><i class="fa fa-trash"></i> Deleted Individual</a></li>
                        <!-- Add page "deleted_individual -->
                    </ul>
                </li>
                <li>
                    <a href="#reg_users" data-toggle="collapse" aria-expanded="false"><i class="fa fa-users"></i> Manage Users</a>
                    <ul class="collapse list-unstyled" id="reg_users">
                        <li><a href="view_users"><i class="fa fa-eye"></i> View Users</a></li>
                        <li><a href="user_registration"><i class="fa fa-plus"></i> Register Users</a></li>
                        <li><a href="deleted_users"><i class="fa fa-trash"></i> Deleted Users</a></li>
                        <!-- Add page view_users -->
                    </ul>
                </li>
                <li>
                    <a href="audit_trail"><i class="fa fa-list"></i> Audit Trail</a>
                </li>
            <?php } ?>
            <li>
                <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false"><i class="fa fa-area-chart"></i> Chart</a>
                <ul class="collapse list-unstyled" id="pageSubmenu">
                    <li><a href="#"><i class="fa fa-bolt"></i> Power</a></li>
                    <li><a href="#"><i class="fa fa-tint"></i> Water</a></li>
                    <li><a href="#"><i class="fa fa-train"></i> Rail</a></li>
                    <li><a target="blank" href="partial/test_chart?cat=Tollways"><i class="fa fa-road"></i> Tollways</a></li>
                    <li><a target="blank" href="partial/test_chart?cat=Logistics"><i class="fa fa-truck"></i> Logistics</a></li>
                    <li><a href="#"><i class="fa fa-hospital-o"></i> Hospital</a></li>
                    <li><a href="#">Others</a></li>
                </ul>
            </li>
            <!-- <li>
                <a href="#my_account" data-toggle="collapse" aria-expanded="false"><i class="fa fa-cog"></i> My Account</a>
                <ul class="collapse list-unstyled" id="my_account">
                    <li><a href="my_account"><i class="fa fa-user"></i> Account Settings</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Company Settings</a></li>
                </ul>
            </li> -->
        </ul>

        <ul class="list-unstyled CTAs">
            <li><a href="login" class="btn btn-danger" style="border-radius: 35px;">Log out</a></li>
        </ul>
    </nav>

    <!-- Page Content Holder -->
    <div id="content">

        <button type="button" id="sidebarCollapse" class="btn btn-primary navbar-btn">
            <i class="glyphicon glyphicon-align-left"></i>
            <span>Toggle Sidebar</span>
        </button>