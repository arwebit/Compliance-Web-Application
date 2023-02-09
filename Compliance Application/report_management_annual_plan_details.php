<?php
$main_page = "Reports";
$page = "Non-Statutory Compliance Annual Plan Report Details";
$excel_file_name = "Non_statutory_annual_plan";
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
                                            <a href="report_management_annual_plan.php">
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
                                                header("location:report_management_annual_plan.php?error=$error_msg");
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
                                                    <table class="table table-bordered" id="tblData">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="10" style="font-weight: bold; text-align: center;">
                                                                    Annual Plan for Non-statutory Compliances 
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
                                                                <th style="font-weight: bold; font-size: 14px;">NSC ID</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Department</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Location</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Nature of compliance</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Description</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Reference</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Assignee</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Due date</th>
                                                                <th style="font-weight: bold; font-size: 14px;">Budgeted cost</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $link = "mng_cmp_details.php?link=report_management_annual_plan.php&&mng_id=";
                                                            $slno = 0;
                                                            $ann_reportSQL = "";
                                                            $ann_reportSQL .= "SELECT a.id, a.mng_id, a.description, b.location, a.reference, a.due_date, a.assign_user, ";
                                                            $ann_reportSQL .= "a.budgeted_cost, c.department, a.comp_nature FROM mng_cmp a INNER JOIN mas_location b ON ";
                                                            $ann_reportSQL .= "a.location_id=b.id INNER JOIN mas_department c ON a.department_id=c.id WHERE a.company_id='$login_company_id' " . $where_clause;
                                                            $fetch_ann_report = json_decode(ret_json_str($ann_reportSQL));
                                                            foreach ($fetch_ann_report as $fetch_ann_reports) {
                                                                $slno++;
                                                                $id = $fetch_ann_reports->id;
                                                                $mng_id = $fetch_ann_reports->mng_id;
                                                                $department = $fetch_ann_reports->department;
                                                                $location = $fetch_ann_reports->location;
                                                                $description = $fetch_ann_reports->description;
                                                                $comp_nature = $fetch_ann_reports->comp_nature;
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
                                                                            <?php echo $mng_id; ?>
                                                                        </a>
                                                                    </td>
                                                                    <td><?php echo $department; ?></td>
                                                                    <td><?php echo $location; ?></td>
                                                                    <td><?php echo $comp_nature; ?></td>
                                                                    <td><?php echo $description; ?></td>
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
