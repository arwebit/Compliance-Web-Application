<?php
$main_page = "Reports";
$page = "Statutory Compliance Annual Plan Report Details";
$excel_file_name = "Statutory_annual_plan";
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
                                            <button class="btn btn-primary" id="btnExporttoExcel">
                                                <span class="fa fa-download"></span>
                                            </button> 
                                            <a href="report_annual_plan.php">
                                                <button class="btn btn-primary">
                                                    <span class="fa fa-arrow-left"></span> </button>  
                                            </a>
                                        </h4>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <?php
                                        if (isset($_POST['search_annual_plan_report'])) {
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
                                                header("location:report_annual_plan.php?error=$error_msg");
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
                                                    <table class="table table-bordered" id="tblData">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="13" style="font-weight: bold; text-align: center;">
                                                                    Annual Plan for Statutory Compliances 
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
                                                                <th style="font-weight: bold; font-size: 14px;">Sl</th>
                                                                <th style="font-weight: bold; font-size: 14px;">SC ID</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Department</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Location</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Legislation</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Activity</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Purpose</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Description</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Mode</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Reference</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Assignee</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Due date</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $link = "risk_management_details.php?link=report_annual_plan.php&&rm_id=";
                                                            $slno = 0;
                                                            $ann_reportSQL = "";
                                                            $ann_reportSQL .= "SELECT a.id, a.rm_id, b.activity, c.purpose, a.description, d.mode, a.reference, g.short_legislation as legislation, ";
                                                            $ann_reportSQL .= "a.due_date, a.assign_user, e.department, f.location, a.budgeted_cost FROM risk_management a ";
                                                            $ann_reportSQL .= "INNER JOIN mas_activity b ON a.activity_id=b.id INNER JOIN mas_purpose c ";
                                                            $ann_reportSQL .= "ON a.purpose_id=c.id INNER JOIN mas_mode d ON a.mode_id=d.id INNER JOIN ";
                                                            $ann_reportSQL .= "mas_department e ON a.department_id=e.id INNER JOIN mas_location f ON a.location_id=f.id ";
                                                            $ann_reportSQL .= "INNER JOIN mas_legislation g ON a.legislation_id=g.id WHERE a.company_id='$login_company_id' " . $where_clause;
                                                            $fetch_ann_report = json_decode(ret_json_str($ann_reportSQL));
                                                            foreach ($fetch_ann_report as $fetch_ann_reports) {
                                                                $slno++;
                                                                $id = $fetch_ann_reports->id;
                                                                $sc_id = $fetch_ann_reports->rm_id;
                                                                $activity = $fetch_ann_reports->activity;
                                                                $legislation = $fetch_ann_reports->legislation;
                                                                $department = $fetch_ann_reports->department;
                                                                $location = $fetch_ann_reports->location;
                                                                $purpose = $fetch_ann_reports->purpose;
                                                                $description = $fetch_ann_reports->description;
                                                                $mode = $fetch_ann_reports->mode;
                                                                $reference = $fetch_ann_reports->reference;
                                                                $due_date = $fetch_ann_reports->due_date;
                                                                $assign_user = $fetch_ann_reports->assign_user;
                                                                $budgeted_cost = $fetch_ann_reports->budgeted_cost;
                                                                $str_due_date = strtotime($due_date);
                                                                $datediff = $str_due_date - strtotime(curr_date_time());
                                                                $due_day = round($datediff / (60 * 60 * 24));
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $slno; ?></td>
                                                                    <td style="font-weight: bolder;"><a href="<?php echo $link . $id; ?>">
                                                                            <?php echo $sc_id; ?>
                                                                        </a>
                                                                    </td>
                                                                    <td><?php echo $department; ?></td>
                                                                    <td><?php echo $location; ?></td>
                                                                    <td><?php echo $legislation; ?></td>
                                                                    <td><?php echo $activity; ?></td>
                                                                    <td><?php echo $purpose; ?></td>
                                                                    <td><?php echo $description; ?></td>
                                                                    <td><?php echo $mode; ?></td>
                                                                    <td><?php echo $reference; ?></td>
                                                                    <td><?php echo $assign_user; ?> </td>
                                                                    <td><?php echo date("d/m/Y", strtotime($due_date)); ?></td>
                                                                    <td><?php echo $budgeted_cost; ?></td>
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
