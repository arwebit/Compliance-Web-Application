<?php
$main_page = "Master";
$page = "View company";
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
                            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li><a href="#"><?php echo $main_page; ?></a></li>
                            <li class="active"><?php echo $page; ?></li>
                        </ol>
                    </section>

                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title" style="float: left;">Active companies</h3>
                                        <h3 class="box-title" style="float: right;">
                                            <a href="add_company.php">
                                                <button class="btn btn-info">
                                                    <span class="fa fa-plus"></span> </button>  
                                            </a>
                                        </h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <table class="table table-bordered table-hover" id="example1" >
                                            <thead>
                                                <tr>
                                                    <th style="font-weight:bolder;">Companies</th>
                                                    <th style="font-weight:bolder;">Status</th>
                                                    <th style="font-weight:bolder;">Option</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $company_SQL = "";
                                                $company_SQL = "SELECT * FROM mas_company WHERE status=1 AND id!='20210316182915' ORDER BY company_name";
                                                $fetch_company = json_decode(ret_json_str($company_SQL));
                                                foreach ($fetch_company as $fetch_companys) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $fetch_companys->company_name; ?></td>
                                                        <td><?php echo $fetch_companys->status == "1" ? "Active" : "Inactive"; ?></td>
                                                        <td>
                                                            <a href="edit_company.php?company_id=<?php echo $fetch_companys->id; ?>" class="btn btn-info">
                                                                EDIT  
                                                            </a>
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
                                        <h3 class="box-title">Inactive companies</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <table class="table table-bordered table-hover" id="example2" >
                                            <thead>
                                                <tr>
                                                    <th style="font-weight:bolder;">Companies</th>
                                                    <th style="font-weight:bolder;">Status</th>
                                                    <th style="font-weight:bolder;">Option</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $icompany_SQL = "";
                                                $icompany_SQL = "SELECT * FROM mas_company WHERE status=0 AND id!='20210316182915' ORDER BY company_name";
                                                $fetch_icompany = json_decode(ret_json_str($icompany_SQL));
                                                foreach ($fetch_icompany as $fetch_icompanys) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $fetch_icompanys->company_name; ?></td>
                                                        <td><?php echo $fetch_icompanys->status == "1" ? "Active" : "Inactive"; ?></td>
                                                        <td>
                                                            <a href="edit_company.php?company_id=<?php echo $fetch_icompanys->id; ?>" class="btn btn-info">
                                                                EDIT  
                                                            </a>
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
