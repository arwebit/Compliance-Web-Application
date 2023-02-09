<?php
$main_page = "Master";
$page = "View Purposes";
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
                                        <h3 class="box-title" style="float: left;">Active Purposes</h3>
                                        <h3 class="box-title" style="float: right;">
                                            <a href="add_purpose.php">
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
                                                    <th><b>Sl no</b></th>
                                                    <th><b>Purpose</b></th>
                                                    <th><b>Status</b></th>
                                                    <th><b>Option</b></th>
                                                    <th><b>History</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $aslno = 0;
                                                $apurpose_SQL = "";
                                                $apurpose_SQL .= "SELECT a.id, a.purpose, b.company_name, a.status, a.create_user, a.create_date, a.modify_user, a.modify_date ";
                                                $apurpose_SQL .= "FROM mas_purpose a INNER JOIN mas_company b ON a.company_id=b.id WHERE a.company_id='$login_company_id' AND ";
                                                $apurpose_SQL .= "a.status=1 ORDER BY a.purpose";
                                                $fetch_apurpose = json_decode(ret_json_str($apurpose_SQL));
                                                foreach ($fetch_apurpose as $fetch_apurposes) {
                                                    $aslno++;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $aslno; ?></td>
                                                        <td><?php echo $fetch_apurposes->purpose; ?></td>
                                                        <td><?php echo $fetch_apurposes->status == "1" ? "Active" : "Inactive"; ?></td>
                                                        <td>
                                                            <a href="edit_purpose.php?purpose_id=<?php echo $fetch_apurposes->id; ?>" class="btn btn-info">
                                                                EDIT  
                                                            </a>
                                                        </td>
                                                        <td>
                                                            Created by : <?php echo $fetch_apurposes->create_user; ?><br/>
                                                            Created date : <?php echo $fetch_apurposes->create_date; ?><br/>
                                                            Modified by : <?php echo $fetch_apurposes->modify_user; ?><br/>
                                                            Modified date : <?php echo $fetch_apurposes->modify_date; ?><br/>
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
                                        <h3 class="box-title">Inactive Purposes</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <table class="table table-bordered table-hover" id="example2">
                                            <thead>
                                                <tr>
                                                    <th><b>Sl no</b></th>
                                                    <th><b>Purpose</b></th>
                                                    <th><b>Status</b></th>
                                                    <th><b>Option</b></th>
                                                    <th><b>History</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $islno = 0;
                                                $ipurpose_SQL = "";
                                                $ipurpose_SQL .= "SELECT a.id, a.purpose, b.company_name, a.status, a.create_user, a.create_date, a.modify_user, a.modify_date ";
                                                $ipurpose_SQL .= "FROM mas_purpose a INNER JOIN mas_company b ON a.company_id=b.id WHERE a.company_id='$login_company_id' AND ";
                                                $ipurpose_SQL .= "a.status=0 ORDER BY a.purpose";
                                                $fetch_ipurpose = json_decode(ret_json_str($ipurpose_SQL));
                                                foreach ($fetch_ipurpose as $fetch_ipurposes) {
                                                    $islno++;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $islno; ?></td>
                                                        <td><?php echo $fetch_ipurposes->purpose; ?></td>
                                                        <td><?php echo $fetch_apurposes->status == "1" ? "Active" : "Inactive"; ?></td>
                                                        <td>
                                                            <a href="edit_purpose.php?purpose_id=<?php echo $fetch_ipurposes->id; ?>" class="btn btn-info">
                                                                EDIT  
                                                            </a>
                                                        </td>
                                                        <td>
                                                            Created by : <?php echo $fetch_ipurposes->create_user; ?><br/>
                                                            Created date : <?php echo $fetch_ipurposes->create_date; ?><br/>
                                                            Modified by : <?php echo $fetch_ipurposes->modify_user; ?><br/>
                                                            Modified date : <?php echo $fetch_ipurposes->modify_date; ?><br/>
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
