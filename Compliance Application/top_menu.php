<?php
$loginuser_SQL = "";
$loginuser_SQL .= "SELECT b.role, a.mem_name, c.role_name, d.id company_id, d.company_name, a.create_date FROM member_registration a ";
$loginuser_SQL .= "INNER JOIN member_login_access b ON a.user_name=b.username INNER JOIN mas_role c ON b.role=c.id INNER ";
$loginuser_SQL .= "JOIN mas_company d ON a.company_id=d.id WHERE a.user_name='$login_user'";
$fetch_loginuser = json_decode(ret_json_str($loginuser_SQL));
foreach ($fetch_loginuser as $fetch_loginusers) {
    $login_role_id = $fetch_loginusers->role;
    $role_name = $fetch_loginusers->role_name;
    $profile_name = $fetch_loginusers->mem_name;
    $login_company_id = $fetch_loginusers->company_id;
    $login_company_name = $fetch_loginusers->company_name;
    $mem_reg_date = $fetch_loginusers->create_date;
}


?>
<header class="main-header">
    <!-- Logo -->
    <a href="home.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>C</b>A</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Compliance</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span style="color: #FFFFFF; text-transform: uppercase; padding-top: 10px; font-weight: bolder; margin-left: 20px; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;">
                <?php echo $login_company_name; ?></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="dist/img/user_avatar.jpg" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?php echo $profile_name; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="dist/img/user_avatar.jpg" class="img-circle" alt="User Image">
                            <p>
                                <?php echo $profile_name; ?> - <?php echo $role_name; ?>
                                <small><b><?php echo $login_company_name; ?></b></small>
                                <a href="change_password.php" style="color: #ffffff;"><small>Change password</small></a>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="update_profile.php" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>