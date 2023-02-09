<?php
$main_page = "Reports";
$page = "Statutory Compliance Cancelled Report Details";
$excel_file_name = "Statutory_cancelled_details";
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
                                        <h4 style="float: left;"> </h4>
                                        <h4 style="float: right;">
                                            <a href="report_canceled.php">
                                                <button class="btn btn-primary">
                                                    <span class="fa fa-arrow-left"></span> </button>  
                                            </a>
                                        </h4>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <?php
                                        if (isset($_POST['search_canceled_report'])) {
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
                                                header("location:report_canceled.php?error=$error_msg");
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
                                                                <th colspan="15" style="font-weight: bold; text-align: center;">
                                                                    Cancelled Report on Statutory
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
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">SC ID </th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Legislation</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Activity</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Purpose</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Mode</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Cancelled date</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                                                                <th colspan="2"style="font-weight: bold; font-size: 14px;">Actual </th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th>
                                                            </tr>
                                                            <tr>
                                                                <th style="font-weight: bold; font-size: 14px;">Value covered</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Cost</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $link = "risk_management_details.php?link=report_canceled.php&&rm_id=";
                                                            $slno = 0;
                                                            $canceled_reportSQL = "";
                                                            $canceled_reportSQL .= "SELECT a.id, a.rm_id, b.activity, c.purpose, a.remarks, d.mode, a.reference, e.short_legislation as legislation, f.department, g.location, ";
                                                            $canceled_reportSQL .= "a.due_date, a.budgeted_cost, a.renewed_date, a.actual_value_covered,a.actual_cost, a.modify_date ";
                                                            $canceled_reportSQL .= "FROM risk_management a INNER JOIN mas_activity b ON a.activity_id=b.id INNER JOIN mas_purpose c ON ";
                                                            $canceled_reportSQL .= "a.purpose_id=c.id INNER JOIN mas_mode d ON a.mode_id=d.id INNER JOIN mas_legislation e ON a.legislation_id=e.id ";
                                                            $canceled_reportSQL .= "INNER JOIN mas_department f ON a.department_id=f.id INNER JOIN mas_location g ON a.location_id=g.id ";
                                                            $canceled_reportSQL .= "WHERE a.company_id='$login_company_id' AND a.status=-1 AND a.update_status=1 " . $where_clause;
                                                            $fetch_canceled_report = json_decode(ret_json_str($canceled_reportSQL));
                                                            foreach ($fetch_canceled_report as $fetch_canceled_reports) {
                                                                $slno++;
                                                                $id = $fetch_canceled_reports->id;
                                                                $scid = $fetch_canceled_reports->rm_id;
                                                                $department = $fetch_canceled_reports->department;
                                                                $legislation = $fetch_canceled_reports->legislation;
                                                                $location = $fetch_canceled_reports->location;
                                                                $activity = $fetch_canceled_reports->activity;
                                                                $purpose = $fetch_canceled_reports->purpose;
                                                                $mode = $fetch_canceled_reports->mode;
                                                                $reference = $fetch_canceled_reports->reference;
                                                                $due_date = $fetch_canceled_reports->due_date;
                                                                $budgeted_cost = $fetch_canceled_reports->budgeted_cost;
                                                                $renewed_date = $fetch_canceled_reports->renewed_date;
                                                                $actual_cost = $fetch_canceled_reports->actual_cost;
                                                                $remarks = $fetch_canceled_reports->remarks;
                                                                $actual_value_covered = $fetch_canceled_reports->actual_value_covered;
                                                              $canceled_date= $fetch_canceled_reports->modify_date;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $slno; ?></td>
                                                                    <td style="font-weight: bolder;"><a href="<?php echo $link . $id; ?>">
                                                                            <?php echo $scid; ?>
                                                                        </a>
                                                                    </td>
                                                                    <td><?php echo $department; ?></td>
                                                                    <td><?php echo $legislation; ?></td>
                                                                    <td><?php echo $location; ?></td>
                                                                    <td><?php echo $activity; ?></td>
                                                                    <td><?php echo $purpose; ?></td>
                                                                    <td><?php echo $mode; ?></td>
                                                                    <td><?php echo $reference; ?></td>
                                                                    <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                                                    <td><?php echo date("d-m-Y", strtotime($canceled_date)); ?></td>
                                                                    <td><?php echo $budgeted_cost; ?></td>
                                                                    <td><?php echo $actual_value_covered; ?></td>
                                                                    <td><?php echo $actual_cost; ?></td>
                                                                    <td><?php echo $remarks; ?></td>
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
