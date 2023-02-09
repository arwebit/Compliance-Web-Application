<?php
$main_page = "Master";
$page = "View Legislations";
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
                                        <h3 class="box-title" style="float: left;">Active Legislations</h3>
                                        <h3 class="box-title" style="float: right;">
                                            <a href="add_legislation.php">
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
                                                    <th><b>Legislations</b></th>
                                                    <th><b>Short code</b></th>
                                                    <th><b>Departments</b></th>
                                                    <th><b>Status</b></th>
                                                    <th><b>Option</b></th>
                                                    <th><b>History</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $aslno = 0;
                                                $alegislation_SQL = "";
                                                $alegislation_SQL .= "SELECT a.id, a.legislation, a.short_legislation, b.department, a.status, a.create_user, a.create_date, a.modify_user, ";
                                                $alegislation_SQL .= "a.modify_date, c.company_name FROM mas_legislation a INNER JOIN mas_department b ON a.department_id=b.id INNER JOIN ";
                                                $alegislation_SQL .= "mas_company c ON a.company_id=c.id WHERE a.company_id='$login_company_id' AND a.status=1 ORDER BY a.legislation ";
                                                $fetch_alegislation = json_decode(ret_json_str($alegislation_SQL));
                                                foreach ($fetch_alegislation as $fetch_alegislations) {
                                                    $aslno++;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $aslno; ?></td>
                                                        <td><?php echo $fetch_alegislations->legislation; ?></td>
                                                        <td><?php echo $fetch_alegislations->short_legislation; ?></td>
                                                        <td><?php echo $fetch_alegislations->department; ?></td>
                                                        <td><?php echo $fetch_alegislations->status == "1" ? "Active" : "Inactive"; ?></td>
                                                        <td>
                                                            <a href="edit_legislation.php?legislation_id=<?php echo $fetch_alegislations->id; ?>" class="btn btn-info">
                                                                EDIT  
                                                            </a>
                                                        </td>
                                                        <td>
                                                            Created by : <?php echo $fetch_alegislations->create_user; ?><br/>
                                                            Created date : <?php echo $fetch_alegislations->create_date; ?><br/>
                                                            Modified by : <?php echo $fetch_alegislations->modify_user; ?><br/>
                                                            Modified date : <?php echo $fetch_alegislations->modify_date; ?><br/>
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
                                        <h3 class="box-title">Inactive Legislations</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <table class="table table-bordered table-hover" id="example2">
                                            <thead>
                                                <tr>
                                                    <th><b>Sl no</b></th>
                                                    <th><b>Legislations</b></th>
                                                    <th><b>Short code</b></th>
                                                    <th><b>Departments</b></th>
                                                    <th><b>Status</b></th>
                                                    <th><b>Option</b></th>
                                                    <th><b>History</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $islno = 0;
                                                $ilegislation_SQL = "";
                                                $ilegislation_SQL .= "SELECT a.id, a.legislation, a.short_legislation, b.department, a.status, a.create_user, a.create_date, a.modify_user, ";
                                                $ilegislation_SQL .= "a.modify_date, c.company_name FROM mas_legislation a INNER JOIN mas_department b ON a.department_id=b.id INNER JOIN ";
                                                $ilegislation_SQL .= "mas_company c ON a.company_id=c.id WHERE a.company_id='$login_company_id' AND a.status=0 ORDER BY a.legislation ";
                                                $fetch_ilegislation = json_decode(ret_json_str($ilegislation_SQL));
                                                foreach ($fetch_ilegislation as $fetch_ilegislations) {
                                                    $islno++;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $islno; ?></td>
                                                        <td><?php echo $fetch_ilegislations->legislation; ?></td>
                                                        <td><?php echo $fetch_ilegislations->short_legislation; ?></td>
                                                        <td><?php echo $fetch_ilegislations->department; ?></td>
                                                        <td><?php echo $fetch_ilegislations->status == "1" ? "Active" : "Inactive"; ?></td>
                                                        <td>
                                                            <a href="edit_legislation.php?legislation_id=<?php echo $fetch_ilegislations->id; ?>" class="btn btn-info">
                                                                EDIT  
                                                            </a>
                                                        </td>
                                                        <td>
                                                            Created by : <?php echo $fetch_ilegislations->create_user; ?><br/>
                                                            Created date : <?php echo $fetch_ilegislations->create_date; ?><br/>
                                                            Modified by : <?php echo $fetch_ilegislations->modify_user; ?><br/>
                                                            Modified date : <?php echo $fetch_ilegislations->modify_date; ?><br/>
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
