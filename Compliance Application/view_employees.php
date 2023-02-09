<?php
$main_page = "Master";
$page = "View employees";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <?php
            include './header_links.php';
            ?>
        </head>
        <body class="hold-transition skin-blue sidebar-mini">
            <div class="wrapper">
                <?php
                include './top_menu.php';
                include './side_menu.php';
                ?>
                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1>
                            <?php echo $main_page; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li class="active"><?php echo $page; ?></li>
                        </ol>
                    </section>

                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title" style="float: left;">Active Employees</h3>
                                        <h3 class="box-title" style="float: right;">
                                            <a href="add_employee.php">
                                                <button class="btn btn-info">
                                                    <span class="fa fa-plus"></span> </button>  
                                            </a>
                                        </h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <table class="table table-bordered table-hover" id="example1">
                                            <thead>
                                                <tr>
                                                    <th style="font-weight: bolder;">Sl no</th>
                                                    <th style="font-weight: bolder;">Username (Role)</th>
                                                    <th style="font-weight: bolder;">Department</th>
                                                    <th style="font-weight: bolder;">Location</th>
                                                    <th style="font-weight: bolder;">Email id</th>
                                                    <th style="font-weight: bolder;">Reporting officers</th>
                                                    <th style="font-weight: bolder;">Status</th>
                                                    <th style="font-weight: bolder;">Option</th>
                                                    <th style="font-weight: bolder;">History</th>
                                                </tr>

                                            </thead>
                                            <tbody>
                                                <?php
                                                $aslno = 0;
                                                $aemployee_SQL = "";
                                                $aemployee_SQL .= "SELECT a.user_name, a.mem_name, b.password, a.email, b.id, c.role_name, b.active_status, d.department, ";
                                                $aemployee_SQL .= "f.first_officer, f.second_officer, f.third_officer, e.location, a.create_user, a.create_date, a.modify_user, a.modify_date FROM member_registration a INNER JOIN ";
                                                $aemployee_SQL .= "member_login_access b ON a.user_name=b.username INNER JOIN mas_role c ON  b.role=c.id LEFT JOIN ";
                                                $aemployee_SQL .= "mas_department d ON a.department=d.id LEFT JOIN mas_location e ON a.location=e.id LEFT JOIN mem_reporting_officer f ";
                                                $aemployee_SQL .= "ON a.user_name=f.user_name WHERE b.active_status=1 AND a.company_id='$login_company_id' AND b.role>0 ORDER BY a.mem_name";

                                                $fetch_aemployee = json_decode(ret_json_str($aemployee_SQL));
                                                foreach ($fetch_aemployee as $fetch_aemployees) {
                                                    $aslno++;
                                                    $name = $fetch_aemployees->mem_name;
                                                    $uname = $fetch_aemployees->user_name;
                                                    $active_status = $fetch_aemployees->active_status;
                                                    $role_name = $fetch_aemployees->role_name;
                                                    $user_id = $fetch_aemployees->id;
                                                    $email = $fetch_aemployees->email;
                                                    $first_officer = $fetch_aemployees->first_officer;
                                                    $second_officer = $fetch_aemployees->second_officer;
                                                    $third_officer = $fetch_aemployees->third_officer;
                                                    $location = $fetch_aemployees->location;
                                                    $department = $fetch_aemployees->department;
                                                    $create_user = $fetch_aemployees->create_user;
                                                    $create_date = $fetch_aemployees->create_date;
                                                    $modify_user = $fetch_aemployees->modify_user;
                                                    $modify_date = $fetch_aemployees->modify_date;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $aslno; ?></td>
                                                        <td><?php echo $uname . " (" . $role_name . ")"; ?></td>
                                                        <td><?php echo $department; ?></td>
                                                        <td><?php echo $location; ?></td>
                                                        <td><?php echo $email; ?></td>
                                                        <td>First officer : <?php echo $first_officer; ?><br/><br/>
                                                        Second officer : <?php echo $second_officer; ?><br/><br/>
                                                        Third officer : <?php echo $third_officer; ?>
                                                        </td>
                                                        <td><?php echo $active_status == "1" ? "Active" : "Inactive"; ?></td>
                                                        <td>
                                                       <a href="edit_role.php?user_id=<?php echo $user_id;?>" class="btn btn-primary">
                                                                CHANGE ROLE
                                                            </a>
                                                            <?php
                                                            if ($active_status == "1") {
                                                                ?>
                                                                <button class="btn btn-danger" value="0" onclick="change_status(this.value, '<?php echo $uname; ?>');">
                                                                    IN-ACTIVE
                                                                </button>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <button class="btn btn-info" value="1" onclick="change_status(this.value, '<?php echo $uname; ?>');">
                                                                    ACTIVE
                                                                </button>
                                                                <?php
                                                            }
                                                            ?>

                                                            <?php
                                                            if ($login_role_id == "-1") {
                                                                ?>
                                                                <a href="user_change_password?user=<?php echo $uname; ?>" class="btn btn-info">
                                                                    CHANGE PASSWORD
                                                                </a>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            Created by : <?php echo $create_user; ?><br/>
                                                            Created date : <?php echo $create_date; ?><br/>
                                                            Modified by : <?php echo $modify_user; ?><br/>
                                                            Modified date : <?php echo $modify_date; ?><br/>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">Inactive Employees</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <table class="table table-bordered table-hover" id="example2">
                                            <thead>
                                                <tr>
                                                    <th style="font-weight: bolder;">Sl no</th>
                                                    <th style="font-weight: bolder;">Username (Role)</th>
                                                    <th style="font-weight: bolder;">Department</th>
                                                    <th style="font-weight: bolder;">Location</th>
                                                    <th style="font-weight: bolder;">Email id</th>
                                                    <th style="font-weight: bolder;">Reporting officers</th>
                                                    <th style="font-weight: bolder;">Status</th>
                                                    <th style="font-weight: bolder;">Option</th>
                                                    <th style="font-weight: bolder;">History</th>
                                                </tr>

                                            </thead>
                                            <tbody>
                                                <?php
                                                $islno = 0;
                                                $iemployee_SQL = "";
                                                $iemployee_SQL .= "SELECT a.user_name, a.mem_name, b.password, a.email, b.id, c.role_name, b.active_status, d.department, ";
                                                $iemployee_SQL .= "f.first_officer, f.second_officer, f.third_officer, e.location, a.create_user, a.create_date, a.modify_user, a.modify_date FROM member_registration a INNER JOIN ";
                                                $iemployee_SQL .= "member_login_access b ON a.user_name=b.username INNER JOIN mas_role c ON  b.role=c.id LEFT JOIN ";
                                                $iemployee_SQL .= "mas_department d ON a.department=d.id LEFT JOIN mas_location e ON a.location=e.id LEFT JOIN mem_reporting_officer f ";
                                                $iemployee_SQL .= "ON a.user_name=f.user_name WHERE b.active_status=0 AND a.company_id='$login_company_id' AND b.role>0 ORDER BY a.mem_name";

                                                $fetch_iemployee = json_decode(ret_json_str($iemployee_SQL));
                                                foreach ($fetch_iemployee as $fetch_iemployees) {
                                                    $islno++;
                                                    $name = $fetch_iemployees->mem_name;
                                                    $uname = $fetch_iemployees->user_name;
                                                    $active_status = $fetch_iemployees->active_status;
                                                    $role_name = $fetch_iemployees->role_name;
                                                    $user_id = $fetch_iemployees->id;
                                                    $email = $fetch_iemployees->email;
                                                    $first_officer = $fetch_iemployees->first_officer;
                                                    $second_officer = $fetch_iemployees->second_officer;
                                                    $third_officer = $fetch_iemployees->third_officer;
                                                    $location = $fetch_iemployees->location;
                                                    $department = $fetch_iemployees->department;
                                                    $create_user = $fetch_iemployees->create_user;
                                                    $create_date = $fetch_iemployees->create_date;
                                                    $modify_user = $fetch_iemployees->modify_user;
                                                    $modify_date = $fetch_iemployees->modify_date;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $islno; ?></td>
                                                        <td><?php echo $uname . " (" . $role_name . ")"; ?></td>
                                                        <td><?php echo $department; ?></td>
                                                        <td><?php echo $location; ?></td>
                                                        <td><?php echo $email; ?></td>
                                                       <td>First officer : <?php echo $first_officer; ?><br/><br/>
                                                        Second officer : <?php echo $second_officer; ?><br/><br/>
                                                        Third officer : <?php echo $third_officer; ?>
                                                        </td>
                                                        <td><?php echo $active_status == "1" ? "Active" : "Inactive"; ?></td>
                                                        <td>
                                                            <!--<a href="edit_role.php?user_id=<?php //echo $user_id;        ?>" class="btn btn-primary">
                                                                CHANGE ROLE
                                                            </a>-->
                                                            <?php
                                                            if ($active_status == "1") {
                                                                ?>
                                                                <button class="btn btn-danger" value="0" onclick="change_status(this.value, '<?php echo $uname; ?>');">
                                                                    IN-ACTIVE
                                                                </button>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <button class="btn btn-info" value="1" onclick="change_status(this.value, '<?php echo $uname; ?>');">
                                                                    ACTIVE
                                                                </button>
                                                                <?php
                                                            }
                                                            ?>

                                                            <?php
                                                            if ($login_role_id == "-1") {
                                                                ?>
                                                                <a href="user_change_password?user=<?php echo $uname; ?>" class="btn btn-info">
                                                                    CHANGE PASSWORD
                                                                </a>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            Created by : <?php echo $create_user; ?><br/>
                                                            Created date : <?php echo $create_date; ?><br/>
                                                            Modified by : <?php echo $modify_user; ?><br/>
                                                            Modified date : <?php echo $modify_date; ?><br/>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </section>
                    <!-- /.content -->
                </div>
                <!-- /.content-wrapper -->
                <?php
                include './footer.php';
                ?>
            </div>
            <!-- ./wrapper -->
            <?php
            include './footer_links.php';
            ?>
             <script>
                function change_status(emp_status, emp_user) {
                    $.ajax({
                        type: "POST",
                        url: "getAPI.php?change_emp_status",
                        dataType: "json",
                        data: {
                            emp_user: emp_user,
                            emp_status: emp_status
                        },
                        success: function (RetVal) {
                            if (RetVal.message === "Success") {
                                window.location.href = "view_employees.php";
                            } else {

                            }

                        }
                    });
                }
            </script>
        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>
