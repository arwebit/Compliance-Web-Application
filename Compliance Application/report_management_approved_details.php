<?php
$main_page = "Reports";
$page = "Non-Statutory Compliance Approved Report Details";
$excel_file_name = "Non-statutory_status_details";
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
                                            <a href="report_management_approved.php">
                                                <button class="btn btn-primary">
                                                    <span class="fa fa-arrow-left"></span> </button>  
                                            </a>
                                        </h4>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <?php
                                        if (isset($_POST['search_approved_report'])) {
                                            $where_clause = "";
                                            $sdue_date = $_POST['sdue_date'];
                                            $department_id = $_POST['department_id'];
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
                                                header("location:report_management_approved.php?error=$error_msg");
                                            } else {
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
                                                                    Approved Report on Non-Statutory
                                                                    <?php
                                                                    if (($from_date == "") || ($to_date == "")) {
                                                                        echo "upto " . date("d/m/Y", strtotime(curr_date_time()));
                                                                    } else {
                                                                        echo "from " . date("d/m/Y", strtotime($from_date)) . " to " . date("d/m/Y", strtotime($to_date));
                                                                    }
                                                                    ?></th>
                                                            </tr>
                                                            <tr>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Sl </th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">NSC ID</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Department</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Location</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Nature of compliance</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Description</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Reference</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Assignee</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Due date</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Transaction Value</th>
                                                                <th colspan="2" style="font-weight: bold; font-size: 14px;">Compliances Status</th>
                                                                <th rowspan="2" style="font-weight: bold; font-size: 14px;">Remarks</th>   
                                                            </tr>
                                                            <tr>
                                                                <th style="font-weight: bold; font-size: 14px;">Completed date</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Delayed (in days)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $link = "mng_cmp_details.php?link=report_management_approved.php&&mng_id=";
                                                           $slno = 0;
                                                            $status_reportSQL = "";
                                                            $status_reportSQL .= "SELECT a.id, a.mng_id, a.reference, b.location, c.department, a.description, a.due_date, a.renewed_date, ";
                                                            $status_reportSQL .= "a.assign_user, a.budgeted_cost, a.actual_transaction_value, a.comp_nature, a.create_date, a.modify_date, ";
                                                            $status_reportSQL .= "a.remarks FROM mng_cmp a INNER JOIN mas_location b ON a.location_id=b.id INNER JOIN mas_department c ON ";
                                                            $status_reportSQL .= "a.department_id=c.id WHERE a.company_id='$login_company_id' AND a.status=1 AND a.update_status=1 " . $where_clause . " ORDER BY a.status, a.mng_id";

                                                            $fetch_status_report = json_decode(ret_json_str($status_reportSQL));
                                                            foreach ($fetch_status_report as $fetch_status_reports) {
                                                                $slno++;
                                                                $id = $fetch_status_reports->id;
                                                                $mng_id = $fetch_status_reports->mng_id;
                                                                $department = $fetch_status_reports->department;
                                                                $location = $fetch_status_reports->location;
                                                                $description = $fetch_status_reports->description;
                                                                $comp_nature = $fetch_status_reports->comp_nature;
                                                                $reference = $fetch_status_reports->reference;
                                                                $due_date = $fetch_status_reports->due_date;
                                                                $renewed_date = $fetch_status_reports->renewed_date;
                                                                $assign_user = $fetch_status_reports->assign_user;
                                                                $remarks = $fetch_status_reports->remarks;
                                                                $budgeted_cost = $fetch_status_reports->budgeted_cost;
                                                                $actual_transaction_value = $fetch_status_reports->actual_transaction_value;
                                                                $case_status = $fetch_status_reports->status;

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
                                                                            <?php echo $mng_id; ?>
                                                                        </a>
                                                                    </td>
                                                                    <td><?php echo $department; ?></td>
                                                                    <td><?php echo $location; ?></td>
                                                                    <td><?php echo $comp_nature; ?></td>
                                                                    <td><?php echo $description; ?></td>
                                                                    <td><?php echo $reference; ?></td>
                                                                    <td><?php echo $assign_user; ?></td>
                                                                    <td><?php echo date("d-m-Y", strtotime($due_date)); ?></td>
                                                                    <td><?php echo $budgeted_cost; ?></td>
                                                                    <td><?php echo $actual_transaction_value; ?></td>
                                                                    <td><?php echo $renewed_date == "" ? "---" : date("d-m-Y", strtotime($renewed_date)); ?></td>
                                                                    <td><?php echo $task_delayed; ?> </td>
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
