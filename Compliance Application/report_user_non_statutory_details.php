<?php
$main_page = "Reports";
$page = "Non-Statutory Compliance User Report Details";
$excel_file_name = "Non_statutory_User";
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
            <style type="text/css">
                table, tr, th, td
                {
                    border:1px solid #000000;
                }
            </style>
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
                                            <a href="report_user_non_statutory.php">
                                                <button class="btn btn-primary">
                                                    <span class="fa fa-arrow-left"></span> </button>  
                                            </a>
                                        </h4>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <?php
                                        if (isset($_POST['search_mis_report'])) {
                                            $where_clause = "";
                                            $sdue_date = $_POST['sdue_date'];
                                            $department_id = $_POST['department_id'];
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
                                                header("location:report_user_non_statutory.php?error=$error_msg");
                                            } else {
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
                                                    <table id="tblData" class="table table-bordered">
                                                        <caption><b style="font-size: 18px; color: #000000;">Username : 
                                          <?php echo $assign_user==""?"ALL":$assign_user; ?></b>
                                            </caption>
                                                        <thead>
                                                            <tr>
                                                                <th colspan="9" style="font-weight: bold; text-align: center;">
                                                                    User report - User Report of <?php echo $assign_user==""?"all":$assign_user; ?> on Non-statutory Compliance 
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
                                                            $sum_total_task_not_comp_overdue = 0;
                                                            $sum_total_task_delay_comp = 0;

                                                            $mis_status_reportSQL = "";
                                                            $mis_status_reportSQL .= "SELECT f.department, g.location, SUM(a.budgeted_cost) budgeted_cost, SUM(a.actual_transaction_value) actual_transaction_value,  ";
                                                            $mis_status_reportSQL .= "COUNT(*) total_cmp, COUNT(CASE WHEN a.status = -1 AND a.update_status = 1 THEN 1 END) AS ";
                                                            $mis_status_reportSQL .= "cancelled_cmp, COUNT(CASE WHEN a.status = 1 AND a.update_status = 1 AND a.renewed_date<=a.due_date THEN 1 END) AS task_comp_time, COUNT(CASE WHEN ";
                                                            $mis_status_reportSQL .= "a.status = 0 AND a.due_date <NOW() THEN 1 END) AS task_not_comp_overdue, COUNT(CASE WHEN a.status = 1 AND a.update_status = 1 AND a.renewed_date>a.due_date THEN 1 END) AS ";
                                                            $mis_status_reportSQL .= "task_comp_delay, COUNT(CASE WHEN a.status = 0 AND a.due_date>=NOW() THEN 1 END) AS task_not_comp_due  FROM mng_cmp a INNER JOIN mas_department f ON a.department_id=f.id ";
                                                            $mis_status_reportSQL .= "INNER JOIN mas_location g ON a.location_id=g.id WHERE a.company_id='$login_company_id' " . $where_clause . " GROUP BY f.department, g.location";
                                                            $fetch_mis_status_report = json_decode(ret_json_str($mis_status_reportSQL));
                                                            foreach ($fetch_mis_status_report as $fetch_mis_status_reports) {
                                                                $slno++;
                                                                $department = $fetch_mis_status_reports->department;
                                                                $location = $fetch_mis_status_reports->location;
                                                                $budgeted_cost = $fetch_mis_status_reports->budgeted_cost;
                                                                $actual_transaction_value = $fetch_mis_status_reports->actual_transaction_value;
                                                                $total_compliances = $fetch_mis_status_reports->total_cmp;
                                                                $total_cancel_compliances = $fetch_mis_status_reports->cancelled_cmp;
                                                                $total_compliances_comp_time = $fetch_mis_status_reports->task_comp_time;
                                                                $total_task_not_comp_due = $fetch_mis_status_reports->task_not_comp_due;
                                                                $total_task_not_comp_overdue = $fetch_mis_status_reports->task_not_comp_overdue;
                                                                $total_task_delay_comp = $fetch_mis_status_reports->task_comp_delay;

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
                                                                    <td ><?php echo $total_compliances; ?></td>
                                                                    <td ><?php echo $total_compliances_comp_time; ?></td>
                                                                    <td ><?php echo $total_task_delay_comp; ?></td>
                                                                    <td ><?php echo $total_task_not_comp_due; ?></td>
                                                                    <td ><?php echo $total_task_not_comp_overdue; ?></td>
                                                                    <td ><?php echo $total_cancel_compliances; ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>
                                                                 <tr>
                                                                <th colspan="3">TOTAL </th>
                                                                <th ><?php echo $sum_total_compliances; ?></th>
                                                                <th ><?php echo $sum_total_compliances_comp_time; ?></th>
                                                                <th ><?php echo $sum_total_task_delay_comp; ?></th>
                                                                <th ><?php echo $sum_total_task_not_comp_due; ?></th>
                                                                 <th ><?php echo $sum_total_task_not_comp_overdue; ?></th>
                                                                <th ><?php echo $sum_total_cancel_compliances; ?></th>
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
