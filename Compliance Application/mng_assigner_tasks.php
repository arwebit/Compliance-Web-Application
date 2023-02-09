<?php
$main_page = "Assigner Task";
$page = "Non-Statutory Compliance Assigner Task";
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
                            <?php echo $page; ?>
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
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <form class="forms-sample" action="" method="post">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="from_date">Due date</label><b class="text-danger"></b>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input type="text" class="form-control pull-right" name="sdue_date" id="reservation">
                                                        </div>
                                                        <!-- /.input group -->
                                                    </div>  
                                                    <div class="form-group">
                                                        <label for="empname">Name</label> <b class="text-danger"></b>
                                                        <select class="form-control select2" id="empname" name="empname">
                                                            <option value="">SELECT NAME</option>
                                                            <?php
                                                            $user_SQL = "SELECT * FROM member_registration WHERE update_status=1 AND company_id='$login_company_id'";
                                                            $fetch_user = json_decode(ret_json_str($user_SQL));
                                                            foreach ($fetch_user as $fetch_users) {
                                                                ?>
                                                                <option value="<?php echo $fetch_users->user_name; ?>">
                                                                    <?php echo $fetch_users->user_name; ?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>

                                                    </div>

                                                    <button type="submit" value="search" name="search_task" id="search_task" class="btn btn-primary mr-2" tabindex="17">
                                                        Search</button> 

                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="location">Location</label>
                                                        <select class="form-control select2" id="location" name="location">
                                                            <option value="">SELECT LOCATIONS</option>
                                                            <?php
                                                            $location_SQL = "SELECT * FROM mas_location WHERE status=1 AND company_id='$login_company_id' ORDER BY location";
                                                            $fetch_location = json_decode(ret_json_str($location_SQL));
                                                            foreach ($fetch_location as $fetch_locations) {
                                                                ?>
                                                                <option value="<?php echo $fetch_locations->id; ?>">
                                                                    <?php echo $fetch_locations->location; ?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </form><br /><hr/>
                                        <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="nonstat_assgn_task">
                                            <thead>
                                                <tr>
                                                    <th ><b>NSC ID</b></th>
                                                    <th syle="font-weight:bolder;"><b>Department</b></th>
                                                    <th syle="font-weight:bolder;"><b>Location</b></th>
                                                    <th syle="font-weight:bolder;"><b>Due date</b></th>
                                                    <th syle="font-weight:bolder;"><b>Task due / overdue</b></th>
                                                    <th syle="font-weight:bolder;"><b>Status</b></th> 
                                                    <th syle="font-weight:bolder;"><b>Option</b></th>
                                                </tr>
                                                <tr>
                                                    <th ><b>&nbsp;</b></th>
                                                    <th syle="font-weight:bolder;"><b>&nbsp;</b></th>
                                                    <th syle="font-weight:bolder;"><b>&nbsp;</b></th>
                                                    <th syle="font-weight:bolder;"><b>&nbsp;</b></th>
                                                    <th syle="font-weight:bolder;"><b>&nbsp;</b></th>
                                                    <th syle="font-weight:bolder;"><b>&nbsp;</b></th>
                                                    <th syle="font-weight:bolder;"><b>&nbsp;</b></th> 
                                                </tr>
                                            </thead>
                                             <?php
                                            if (isset($_REQUEST['search_task'])) {
                                                $where_clause = "";
                                                $display_cat="";
                                                $sdue_date = $_REQUEST['sdue_date'];
                                                $empname = $_REQUEST['empname'];
                                                $location = $_REQUEST['location'];

                                                if ($sdue_date != "") {
                                                    $sdue_date = str_replace(" ", "", $sdue_date);
                                                    $format_date = explode("-", $sdue_date);
                                                    $fdate = DateTime::createFromFormat('m/d/Y', $format_date[0]);
                                                    $tdate = DateTime::createFromFormat('m/d/Y', $format_date[1]);
                                                    $from_date = $fdate->format('Y-m-d');
                                                    $to_date = $tdate->format('Y-m-d');
                                                }
                                                if (($empname != "")) {
                                                    $where_clause .= "AND a.username='$empname' ";
                                                }
                                                if (($location != "")) {
                                                    $where_clause .= "AND a.location_id='$location' ";
                                                }
                                                if ((($from_date != "") && ($to_date == "")) || (($from_date != "") && ($to_date != ""))) {
                                                    $where_clause .= "AND a.due_date BETWEEN '$from_date' AND '$to_date'";
                                                }
                                                ?>
                                                <tbody>
                                                <?php
                                                $rm_SQL = "";
                                                $rm_SQL .= "SELECT a.id, a.due_date, a.mng_id, c.location, a.status, a.update_status, b.department FROM mng_cmp a INNER JOIN mas_department b ON  ";
                                                $rm_SQL .= "a.department_id=b.id INNER JOIN mas_location c ON c.id=a.location_id WHERE a.assign_user='$login_user' AND a.update_status!=1 ";
                                                $rm_SQL .= "AND a.company_id='$login_company_id' ". $where_clause." ORDER BY a.create_date DESC";

                                                $fetch_rm = json_decode(ret_json_str($rm_SQL));
                                                foreach ($fetch_rm as $fetch_mng) {
                                                    $update_status = $fetch_mng->update_status;
                                                    $status = $fetch_mng->status;
                                                    $due_date = $fetch_mng->due_date;
                                                    $str_due_date = strtotime($due_date);
                                                    $datediff = $str_due_date - strtotime(curr_date_time());
                                                    $days_pending_due = round($datediff / (60 * 60 * 24));

                                                    switch ($update_status) {
                                                        case "1":
                                                            $update_status_desc = "Approved";
                                                            break;
                                                        case "2":
                                                            $update_status_desc = "Assigned";
                                                            break;
                                                        case "3":
                                                            $update_status_desc = "Data edited, please check and approve";
                                                            break;
                                                        case "4":
                                                            $update_status_desc = "Marked complete and sent for approval to the preparer";
                                                           break;
                                                        default:
                                                            $update_status_desc = "";
                                                            break;
                                                    }
                                                     if ($days_pending_due >= 0) {
                                                                    $bgColor="background-color:#FFFFFF;";
                                                                }else{
                                                                    $bgColor="background-color:#FF0000; color:#FFFFFF;";
                                                                }
                                                    ?>
                                                    <tr style="<?php echo $bgColor; ?>">
                                                        <td><?php echo $fetch_mng->mng_id; ?></td>
                                                        <td><?php echo $fetch_mng->department; ?></td>
                                                        <td><?php echo $fetch_mng->location; ?></td>
                                                        <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                                        <td><?php
                                                            if ($days_pending_due >= 0) {
                                                                echo "Due : ";
                                                            } else {
                                                                echo "Overdue : ";
                                                            }
                                                            echo abs($days_pending_due);
                                                            ?> day(s)</td>
                                                        <td><?php echo $update_status_desc; ?></td>
                                                        <td>
                                                            <a href="mng_cmp_details.php?link=mng_assigner_tasks.php&&mng_id=<?php echo $fetch_mng->id; ?>" class="btn btn-warning">
                                                                DETAILS  
                                                            </a> ||
                                                            <?php
                                                            if (!(($status == 1) && ($update_status == 1))) {
                                                                if ($update_status != 3) {
                                                                    ?>
                                                                    <a href="mark_complete_mng.php?mng_id=<?php echo $fetch_mng->id; ?>" class="btn btn-info">
                                                                        MARK COMPLETE  
                                                                    </a>
                                                                    <?php
                                                                } else {
                                                                    echo "Data edited, please check and approve first, and then mark it complete";
                                                                }
                                                            } else {
                                                                echo "Task completed";
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                               <?php
                                                }
                                                ?>
                                        </table>
                                            </div>
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
        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>
