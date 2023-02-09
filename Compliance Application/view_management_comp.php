<?php
$main_page = "Non-Statutory Compliance";
$page = "View Non-Statutory Compliance";
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
                                        <h3 class="box-title"><?php echo $main_page; ?></h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <table id="example1" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th syle="font-weight:bolder;"><b>NSC ID</b></th>
                                                    <th syle="font-weight:bolder;"><b>Due date</b></th>
                                                    <th syle="font-weight:bolder;"><b>Task due / overdue</b></th>
                                                    <th syle="font-weight:bolder;"><b>Status</b></th>
                                                    <th syle="font-weight:bolder;"><b>Option</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $rm_SQL = "";
                                                $rm_SQL .= "SELECT a.id, a.due_date, b.mem_name, a.mng_id, a.status, a.update_status FROM mng_cmp a INNER JOIN  ";
                                                $rm_SQL .= "member_registration b ON a.username=b.user_name WHERE a.username='$login_user' AND a.update_status!=1 ";
                                                $rm_SQL .= "AND a.company_id='$login_company_id' ORDER BY a.create_date DESC";

                                                $fetch_rm = json_decode(ret_json_str($rm_SQL));
                                                foreach ($fetch_rm as $fetch_mng) {
                                                    $update_status = $fetch_mng->update_status;
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
                                                            $update_status_desc = "Edited and sent for approval to the assigner";
                                                             break;
                                                        case "4":
                                                            $update_status_desc = "Marked complete, please confirm the completion";
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
                                                            <a href="mng_cmp_details.php?link=view_management_comp.php&&mng_id=<?php echo $fetch_mng->id; ?>" class="btn btn-warning">
                                                                DETAILS  
                                                            </a> ||
                                                            <?php
                                                            if (!(($status == 1) && ($update_status == 1))) {
                                                                ?>
                                                                <a href="edit_management_comp.php?mng_id=<?php echo $fetch_mng->id; ?>" class="btn btn-info">
                                                                    EDIT  
                                                                </a>
                                                                <?php
                                                            } else {
                                                                echo "Task completed";
                                                            }
                                                            ?> ||
                                                            <?php
                                                            if (!(($status == 1) && ($update_status == 1))) {
                                                                ?>
                                                                <a href="cancel_mng.php?mng_id=<?php echo $fetch_mng->id; ?>" class="btn btn-danger">
                                                                    CANCEL  
                                                                </a>
                                                                <?php
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
        </body>
    </html>
    <?php
} else {
    header("location:index.php");
}
?>
