<?php
$main_page = "Reports";
$page = "Statutory Compliance Status Report Details";
$excel_file_name = "Statutory_status_details";
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
                                            <a href="report_status.php">
                                                <button class="btn btn-primary">
                                                    <span class="fa fa-arrow-left"></span> </button>  
                                            </a>
                                        </h4>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <?php
                                        if (isset($_POST['search_status_report'])) {
                                            $where_clause = "";
                                            $sdue_date = $_POST['sdue_date'];
                                            $department_id = $_POST['department_id'];
                                            $mode_id = $_POST['mode_id'];
                                            $legislation_id = $_POST['legislation_id'];
                                            $activity_id = $_POST['activity_id'];
                                            $location = $_POST['location'];

                                            if ($sdue_date != "") {
                                                $sdue_date = str_replace(" ", "", $sdue_date);
                                                $format_date = explode("-", $sdue_date);
                                                $fdate = DateTime::createFromFormat('m/d/Y', $format_date[0]);
                                                $tdate = DateTime::createFromFormat('m/d/Y', $format_date[1]);
                                                $from_date = $fdate->format('Y-m-d');
                                                $to_date = $tdate->format('Y-m-d');
                                            }
                                            if ($error_msg != "") {
                                                header("location:report_status.php?error=$error_msg");
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
                                                if ((($from_date != "") && ($to_date == "")) || (($from_date != "") && ($to_date != ""))) {
                                                    $where_clause .= "AND a.due_date BETWEEN '$from_date' AND '$to_date'";
                                                }
                                                ?>
                                                <div style="height: 500px; overflow-x: scroll; overflow-y: scroll;">
                                                    <table id="tblData" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="20" style="font-weight: bold; text-align: center;">
                                                                    Status Report on Statutory Compliance 
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
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">SC ID</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Legislation</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Activity</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Purpose</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Mode</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Assignee</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                                                                <th colspan="2" style="font-weight: bold; font-size: 14px;">Actual</th>
                                                                <th colspan="4" style="font-weight: bold; font-size: 14px;">Compliances Status</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th>                                                                    
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Status</th>
                                                            </tr>
                                                            <tr>
                                                                <th style="font-weight: bold; font-size: 14px;">Cost</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Value covered</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Completed date</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Delayed (in days)</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Pending (in days)</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Cancelled date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $link = "risk_management_details.php?link=report_status.php&&rm_id=";
                                                            $slno = 0;
                                                            $status_reportSQL = "";
                                                            $status_reportSQL .= "SELECT a.id, a.rm_id, b.activity, c.purpose, a.remarks, d.mode, a.reference, e.short_legislation as legislation, f.department, g.location, ";
                                                            $status_reportSQL .= "a.due_date, a.assign_user, a.budgeted_cost, a.renewed_date, a.actual_value_covered,a.actual_cost,a.status, a.update_status, ";
                                                            $status_reportSQL .= "a.create_date, a.modify_date FROM risk_management a INNER JOIN mas_activity b ON a.activity_id=b.id INNER JOIN ";
                                                            $status_reportSQL .= "mas_purpose c ON a.purpose_id=c.id INNER JOIN mas_mode d ON a.mode_id=d.id INNER JOIN mas_legislation e ON ";
                                                            $status_reportSQL .= "a.legislation_id=e.id INNER JOIN mas_department f ON a.department_id=f.id INNER JOIN mas_location g ON a.location_id=g.id ";
                                                            $status_reportSQL .= "WHERE a.company_id='$login_company_id' AND a.status!=-1 " . $where_clause . " ORDER BY a.status, a.rm_id";
                                                            $fetch_status_report = json_decode(ret_json_str($status_reportSQL));
                                                            foreach ($fetch_status_report as $fetch_status_reports) {
                                                                $slno++;
                                                                $id = $fetch_status_reports->id;
                                                                $scid = $fetch_status_reports->rm_id;
                                                                $department = $fetch_status_reports->department;
                                                                $legislation = $fetch_status_reports->legislation;
                                                                $location = $fetch_status_reports->location;
                                                                $assign_user = $fetch_status_reports->assign_user;
                                                                $activity = $fetch_status_reports->activity;
                                                                $purpose = $fetch_status_reports->purpose;
                                                                $mode = $fetch_status_reports->mode;
                                                                $reference = $fetch_status_reports->reference;
                                                                $due_date = $fetch_status_reports->due_date;
                                                                $budgeted_cost = $fetch_status_reports->budgeted_cost;
                                                                $renewed_date = $fetch_status_reports->renewed_date;
                                                                $actual_cost = $fetch_status_reports->actual_cost;
                                                                $remarks = $fetch_status_reports->remarks;
                                                                $case_status = $fetch_status_reports->status;
                                                                $actual_value_covered = $fetch_status_reports->actual_value_covered;

                                                                if ($case_status == -1) {
                                                                    $canceled_date = $fetch_status_reports->modify_date;
                                                                    $task_delayed = "---";
                                                                    $task_still_not_completed = "---";
                                                                } else {
                                                                    $canceled_date = "---";
                                                                    $str_due_date = strtotime($due_date);
                                                                    $pending_crosseddiff = $str_due_date - strtotime(curr_date_time());
                                                                    $task_still_not_completed = round($pending_crosseddiff / (60 * 60 * 24));
                                                                    if ((!empty($renewed_date)) || ($renewed_date != "")) {
                                                                        $str_renew_date = strtotime($renewed_date);
                                                                        $delay_diff = $str_renew_date - $str_due_date;
                                                                        $task_delayed = round($delay_diff / (60 * 60 * 24));
                                                                        if ($task_delayed <= 0) {
                                                                            $task_delayed = "---";
                                                                        }
                                                                    } else {
                                                                        $task_delayed = "---";
                                                                    }
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $slno; ?></td>
                                                                    <td style="font-weight: bolder;"><a href="<?php echo $link . $id; ?>">
                                                                            <?php echo $scid; ?>
                                                                        </a>
                                                                    </td>
                                                                    <td><?php echo $department; ?></td>
                                                                    <td><?php echo $location; ?></td>
                                                                    <td><?php echo $legislation; ?></td>
                                                                    <td><?php echo $activity; ?></td>
                                                                    <td><?php echo $purpose; ?></td>
                                                                    <td><?php echo $mode; ?></td>
                                                                    <td><?php echo $reference; ?></td>
                                                                    <td><?php echo $assign_user; ?></td>
                                                                    <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                                                    <td><?php echo $budgeted_cost; ?></td>
                                                                    <td><?php echo $actual_cost; ?></td>
                                                                    <td><?php echo $actual_value_covered; ?></td>
                                                                    <td><?php echo $renewed_date == "" ? "---" : date("d-m-Y", strtotime($renewed_date)); ?></td>
                                                                    <td><?php echo $task_delayed; ?> </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($case_status == -1) {
                                                                            echo "---";
                                                                        } else {
                                                                            if (empty($renewed_date)) {
                                                                                if ($task_still_not_completed >= 0) {
                                                                                    echo "Due : ";
                                                                                } else {
                                                                                    echo "Overdue :  ";
                                                                                }
                                                                                echo abs($task_still_not_completed);
                                                                            } else {
                                                                                echo "---";
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo $canceled_date; ?> </td>
                                                                    <td><?php echo $remarks; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        if ($case_status == -1) {
                                                                            echo "Canceled";
                                                                        } else {
                                                                            echo $case_status == 1 ? "Closed" : "Open";
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
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
