<?php
$main_page = "Reports";
$page = "Statutory Compliance User Report Details";
$excel_file_name = "Statutory_User";
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
                                    <div class="box-header">
                                        <h4 style="float: left;"></h4>
                                        <h4 style="float: right;">
                                            <button class="btn btn-primary" id="btnExporttoExcel">
                                                <span class="fa fa-download"></span>
                                            </button> 
                                            <a href="report_user_statutory.php">
                                                <button class="btn btn-primary">
                                                    <span class="fa fa-arrow-left"></span> </button>  
                                            </a>
                                        </h4>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <?php
                                        if (isset($_POST['search_user_report'])) {
                                            $where_clause = "";
                                            $sdue_date = $_POST['sdue_date'];
                                            $department_id = $_POST['department_id'];
                                            $mode_id = $_POST['mode_id'];
                                            $legislation_id = $_POST['legislation_id'];
                                            $activity_id = $_POST['activity_id'];
                                            $location = $_POST['location'];
                                            $assign_user = $_POST['assign_user'];

                                            if ($sdue_date != "") {
                                                $sdue_date = str_replace(" ", "", $sdue_date);
                                                $format_date = explode("-", $sdue_date);
                                                $fdate = DateTime::createFromFormat('m/d/Y', $format_date[0]);
                                                $tdate = DateTime::createFromFormat('m/d/Y', $format_date[1]);
                                                $from_date = $fdate->format('Y-m-d');
                                                $to_date = $tdate->format('Y-m-d');
                                            }
                                            if ($error_msg != "") {
                                                header("location:report_user_statutory.php?error=$error_msg");
                                            } else {
                                                if (($activity_id != "")) {
                                                    $where_clause .= "AND a.activity_id='$activity_id' ";
                                                }
                                                if (($mode_id != "")) {
                                                    $where_clause .= "AND a.mode_id='$mode_id' ";
                                                }
                                                if (($legislation_id != "")) {
                                                    $where_clause .= "AND a.legislation_id='$legislation_id' ";
                                                }
                                                if (($department_id != "")) {
                                                    $where_clause .= "AND a.department_id='$department_id' ";
                                                }
                                                if (($location != "")) {
                                                    $where_clause .= "AND a.location_id='$location' ";
                                                }
                                                if (($assign_user != "")) {
                                                    $where_clause .= "AND a.assign_user='$assign_user' ";
                                                }
                                                if ((($from_date != "") && ($to_date == "")) || (($from_date != "") && ($to_date != ""))) {
                                                    $where_clause .= "AND a.due_date BETWEEN '$from_date' AND '$to_date'";
                                                }
                                                ?>
                                       
                                                <div style="height: 500px; overflow-x: scroll; overflow-y: scroll;">
                                        <table id="tblData" class="table table-bordered table-striped" border="1">
                                            <caption><b style="font-size: 18px; color: #000000;">Username : 
                                          <?php echo $assign_user==""?"ALL":$assign_user; ?></b>
                                            </caption>
                                            <thead>
                                                <tr>
                                                    <th colspan="14" style="font-weight: bold; text-align: center;">
                                                        User report - User Report of <?php echo $assign_user==""?"all":$assign_user; ?> on Statutory Compliance 
                                                        <?php
                                                        if (($from_date == "") || ($to_date == "")) {
                                                        echo "upto " . date("d/m/Y", strtotime(curr_date_time()));
                                                        } else {
                                                        echo "from " . date("d/m/Y", strtotime($from_date)) . " to " . date("d/m/Y", strtotime($to_date));
                                                        }
                                                        ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl </th>
                                                    <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                                                    <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                                                    <th rowspan="2" style="font-weight: bold; font-size: 14px;">Legislation</th>
                                                    <th rowspan="2" style="font-weight: bold; font-size: 14px;">Activity</th>
                                                    <th rowspan="2" style="font-weight: bold; font-size: 14px;">Purpose</th>
                                                    <th colspan="6" style="font-weight: bold; font-size: 14px;">No. of compliances</th>
                                                </tr>
                                                <tr>
                                                    <th style="font-weight: bold; font-size: 14px;">Total</th>
                                                    <th style="font-weight: bold; font-size: 14px;">Completed on due date</th>
                                                    <th style="font-weight: bold; font-size: 14px;">Completed but delayed</th>
                                                    <th style="font-weight: bold; font-size: 14px;">Pending (Due)</th>
                                                    <th style="font-weight: bold; font-size: 14px;">Pending (Overdue)</th>
                                                    <th style="font-weight: bold; font-size: 14px;">Cancelled</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 0;
                                                $sum_total_compliances = 0;
                                                $sum_total_cancel_compliances = 0;
                                                $sum_total_compliances_comp_time = 0;
                                                $sum_total_task_not_comp_due = 0;
                                                $sum_total_task_not_comp_overdue=0;
                                                $sum_total_task_delay_comp = 0;

                                                $user_wise_reportSQL = "";
                                                $user_wise_reportSQL .= "SELECT b.activity, c.purpose, e.short_legislation as legislation, f.department, g.location, SUM(a.budgeted_cost) budgeted_cost, SUM(a.actual_value_covered) ";
                                                $user_wise_reportSQL .= "actual_value_covered, SUM(a.actual_cost) actual_cost, COUNT(*) total_cmp, COUNT(CASE WHEN a.status = -1 AND a.update_status = 1 THEN 1 END) AS ";
                                                $user_wise_reportSQL .= "cancelled_cmp, COUNT(CASE WHEN a.status = 1 AND a.update_status = 1 AND a.renewed_date<=a.due_date THEN 1 END) AS task_comp_time, COUNT(CASE WHEN ";
                                                $user_wise_reportSQL .= "a.status = 0 AND a.due_date <NOW() THEN 1 END) AS task_not_comp_overdue, COUNT(CASE WHEN a.status = 1 AND a.update_status = 1 AND a.renewed_date>a.due_date THEN 1 END) AS ";
                                                $user_wise_reportSQL .= "task_comp_delay, COUNT(CASE WHEN a.status = 0 AND a.due_date>=NOW() THEN 1 END) AS task_not_comp_due FROM risk_management a INNER JOIN mas_activity b ON a.activity_id=b.id INNER JOIN mas_purpose c ON a.purpose_id=c.id ";
                                                $user_wise_reportSQL .= "INNER JOIN mas_legislation e ON a.legislation_id=e.id INNER JOIN mas_department f ON a.department_id=f.id ";
                                                $user_wise_reportSQL .= "INNER JOIN mas_location g ON a.location_id=g.id WHERE a.company_id='$login_company_id' " . $where_clause . " GROUP BY b.activity, c.purpose, g.location, e.legislation, f.department";
                                                $fetch_user_wise_report = json_decode(ret_json_str($user_wise_reportSQL));
                                                foreach ($fetch_user_wise_report as $fetch_user_wise_reports) {
                                                $slno++;
                                                $department = $fetch_user_wise_reports->department;
                                                $legislation = $fetch_user_wise_reports->legislation;
                                                $activity = $fetch_user_wise_reports->activity;
                                                $purpose = $fetch_user_wise_reports->purpose;
                                                $location = $fetch_user_wise_reports->location;
                                                $budgeted_cost = $fetch_user_wise_reports->budgeted_cost;
                                                $actual_cost = $fetch_user_wise_reports->actual_cost;
                                                $actual_value_covered = $fetch_user_wise_reports->actual_value_covered;
                                                $total_compliances = $fetch_user_wise_reports->total_cmp;
                                                $total_cancel_compliances = $fetch_user_wise_reports->cancelled_cmp;
                                                $total_compliances_comp_time = $fetch_user_wise_reports->task_comp_time;
                                                $total_task_not_comp_due = $fetch_user_wise_reports->task_not_comp_due;
                                                $total_task_not_comp_overdue = $fetch_user_wise_reports->task_not_comp_overdue;
                                                $total_task_delay_comp = $fetch_user_wise_reports->task_comp_delay;

                                                $sum_total_compliances += $total_compliances;
                                                $sum_total_compliances_comp_time += $total_compliances_comp_time;
                                                $sum_total_task_delay_comp += $total_task_delay_comp;
                                                $sum_total_task_not_comp_due += $total_task_not_comp_due;
                                                $sum_total_task_not_comp_overdue += $total_task_not_comp_overdue;
                                                $sum_total_cancel_compliances += $total_cancel_compliances;
                                                ?>
                                                <tr>
                                                    <td><?php echo $slno; ?></td>
                                                    <td><?php echo $department; ?></td>
                                                    <td><?php echo $location; ?></td>
                                                    <td><?php echo $legislation; ?></td>
                                                    <td><?php echo $activity; ?></td>
                                                    <td><?php echo $purpose; ?></td>
                                                    <td><?php echo $total_compliances; ?></td>
                                                    <td><?php echo $total_compliances_comp_time; ?></td>
                                                    <td><?php echo $total_task_delay_comp; ?></td>
                                                    <td><?php echo $total_task_not_comp_due; ?></td>
                                                    <td><?php echo $total_task_not_comp_overdue; ?></td>
                                                    <td><?php echo $total_cancel_compliances; ?></td>
                                                </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                 <tr>
                                                    <th colspan="6">TOTAL </th>
                                                    <th><?php echo $sum_total_compliances; ?></th>
                                                    <th><?php echo $sum_total_compliances_comp_time; ?></th>
                                                    <th><?php echo $sum_total_task_delay_comp; ?></th>
                                                    <th><?php echo $sum_total_task_not_comp_due; ?></th>
                                                    <th><?php echo $sum_total_task_not_comp_overdue; ?></th>
                                                    <th><?php echo $sum_total_cancel_compliances; ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                                <?php
                                            }
                                        }
                                        ?>
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
