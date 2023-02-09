<?php
$main_page = "Upload data";
$page = "Upload Master Data";
include './master_data_upload.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if($_REQUEST['table']){
        $table=$_REQUEST['table'];
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <?php include './header_links.php'; ?>
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
                        <!-- SELECT2 EXAMPLE -->
                        <div class="box box-default">
                            <div class="box-header">
                                <span style="font-size:18px; font-weight: bolder;"> 
                                    Master Upload For <span style="text-transform: capitalize;">
                                        <?php echo explode("_",$table)[1]; ?></span>
                                </span> 
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <form action="" id="upload_data" class="forms-sample" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="company_id" id="company_id" value="<?php echo $login_company_id; ?>" />
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="file_xl">Choose csv file</label><span class="text-danger"> *</span>
                                                <input type="file" id="master_data_upload" required="required" name="master_data_upload" class="form-control" />
                                            </div>
                                            <button type="submit" id="upload_master_data" name="upload_master_data" class="btn btn-primary mr-2">Upload</button>
                                            <br/><br/>
                                            <b class="text-success" id="success_message"><?php
                                                echo $master_success_msg;?></b>
                                            <b class="text-danger" id="success_message"><?php
                                                echo $master_error_msg;?></b>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                        <?php
                        $upl_masterSQL = "SELECT * FROM upl_master";
                        $upl_master_exist = connect_db()->countEntries($upl_masterSQL);
                        if ($upl_master_exist > 0) {
                            ?>
                            <div class="box box-default">
                                <div class="box-header">
                                    <h3 class="box-title">Preview</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table class="table table-bordered" id="example1">
                                        <thead>
                                            <tr>
                                                <th><b>MASTER DESCRIPTION</b></th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                            <?php
                                            $chk1 = 0;
                                            $err_chk = 0;
                                            $uplmstr_SQL = "SELECT * FROM upl_master";
                                            $fetchupms = json_decode(ret_json_str($uplmstr_SQL));
                                            foreach ($fetchupms as $fetchupmss) {
                                                $master_desc = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetchupmss->master_desc));

                                                ?>
                                                <tr>
                                                    <td <?php echo $master_desc; ?>><?php echo $master_desc; ?></td>
                                                </tr>
                                                <?php
                                                if ($chk1 == 1) {
                                                    $err_chk++;
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table><br/>
                                    <?php
                                    if ($err_chk == 0) {
                                        ?>
                                    <form id="import_data" class="forms-sample" action="" method="post">
                                        <input type="hidden" name="table_name" id="table_name" value="<?php echo $table;?>" />
                                        <input type="hidden" name="create_user" id="create_user" value="<?php echo $login_user;?>" />
                                        <button type="submit" name="confirm_master_data" id="confirm_master_data" class="btn btn-info">
                                            Confirm</button>
                                    </form>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box --> 
                            <?php
                        }
                        ?>
                    </section>
                    <!-- /.content -->
                </div>
                <!-- /.content-wrapper -->
                <?php
                include './footer.php';
                ?>

                <div class="control-sidebar-bg"></div>
            </div>
            <!-- ./wrapper -->
            <?php
            include './footer_links.php';
            ?>
        </body>
    </html>
    <?php
    }
} else {
    header("location:index.php");
}
?>