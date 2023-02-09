<?php
$main_page = "Upload Data";
$page = "Upload Statutory Data";
include './data_upload.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
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
                            <!-- /.box-header -->
                            <div class="box-body">
                                <form action="" id="upload_data" class="forms-sample" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="company_id" id="company_id" value="<?php echo $login_company_id; ?>" />
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="file_xl">Choose csv file</label><span class="text-danger"> *</span>
                                                <input type="file" id="statutory_data_upload" required="required" name="statutory_data_upload" class="form-control" />
                                            </div>
                                            <button type="submit" id="upload_statutory_data" name="upload_statutory_data" class="btn btn-primary mr-2">Upload</button>
                                            <br/><br/>
                                            <b class="text-success" id="success_message"><?php
                                                echo $stat_success_msg;?></b>
                                            <b class="text-danger" id="success_message"><?php
                                                echo $stat_error_msg;?></b>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                        <?php
                        $upl_rmSQL = "SELECT * FROM upl_risk_mng";
                        $upl_rm_exist = connect_db()->countEntries($upl_rmSQL);
                        if ($upl_rm_exist > 0) {
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
                                                <th><b>USERNAME</b></th>
                                                <th><b>DEPARTMENT</b></th>
                                                <th><b>LEGISLATION</b></th>
                                                <th><b>DUE DATE</b></th>
                                                <th><b>ACTIVITY</b></th>
                                                <th><b>PURPOSE</b></th>
                                                <th><b>MODE</b></th>
                                                <th><b>LOCATION</th>
                                                <th><b>ASSIGNEE</th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                            <?php
                                            $chk1 = 0;
                                            $chk2 = 0;
                                            $chk3 = 0;
                                            $chk4 = 0;
                                            $chk5 = 0;
                                            $chk6 = 0;
                                            $chk7 = 0;
                                            $chk8 = 0;
                                            $chk9 = 0;
                                            $err_chk = 0;
                                            $rmmng_SQL = "SELECT * FROM upl_risk_mng";
                                            $fetchrm = json_decode(ret_json_str($rmmng_SQL));
                                            foreach ($fetchrm as $fetchrms) {
                                                $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetchrms->username));
                                                $assign_user = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetchrms->assign_user));
                                                $department = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetchrms->department));
                                                $legislation = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetchrms->legislation));
                                                $due_date = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetchrms->due_date));
                                                $activity = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetchrms->activity));
                                                $mode = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetchrms->mode));
                                                $purpose = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetchrms->purpose));
                                                $location = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetchrms->location));

                                                if (empty($user_name)) {
                                                    $user_style = "style=color:#FF0000";
                                                    $chk1 = 1;
                                                    $user_name = "Required";
                                                } else {
                                                    $user_nameSQL = "SELECT * FROM member_registration WHERE user_name='$user_name'";
                                                    $user_name_exist = connect_db()->countEntries($user_nameSQL);
                                                    if ($user_name_exist == 0) {
                                                        $user_style = "style=color:#FF0000";
                                                        $chk1 = 1;
                                                        $user_name .= " (Not matched with master)";
                                                    } else {
                                                        $compuser_nameSQL = "SELECT * FROM member_registration WHERE user_name='$user_name' AND company_id='$login_company_id'";
                                                    $compuser_name_exist = connect_db()->countEntries($compuser_nameSQL);
                                                    if($compuser_name_exist==0){
                                                        $user_style = "style=color:#FF0000";
                                                        $chk1 = 1;
                                                        $user_name .= " (This user is not registered with this company)";
                                                    }else{
                                                        $user_style = "";
                                                        $chk1 = 0;
                                                    }
                                                    }
                                                }
                                                if (empty($assign_user)) {
                                                    $user_assign_style = "style=color:#FF0000";
                                                    $chk1 = 1;
                                                    $assign_user = "Required";
                                                } else {
                                                    $user_assignSQL = "SELECT * FROM member_registration WHERE user_name='$assign_user'";
                                                    $user_assign_exist = connect_db()->countEntries($user_assignSQL);
                                                    if ($user_assign_exist == 0) {
                                                        $user_assign_style = "style=color:#FF0000";
                                                        $chk2 = 1;
                                                        $assign_user .= " (Not matched with master)";
                                                    }  else {
                                                        $compuser_assignSQL = "SELECT * FROM member_registration WHERE user_name='$assign_user' AND company_id='$login_company_id'";
                                                    $compuser_assign_exist = connect_db()->countEntries($compuser_assignSQL);
                                                    if($compuser_assign_exist==0){
                                                        $user_assign_style = "style=color:#FF0000";
                                                        $chk2 = 1;
                                                        $assign_user .= " (This user is not registered with this company)";
                                                    }else{
                                                        $user_assign_style = "";
                                                         $chk2 = 0;
                                                    }
                                                    }
                                                }
                                                if (empty($department)) {
                                                    $department_style = "style=color:#FF0000";
                                                    $chk1 = 1;
                                                    $department = "Required";
                                                } else {
                                                    $deptSQL = "SELECT * FROM mas_department WHERE department='$department' AND company_id='$login_company_id'";
                                                    $dept_exist = connect_db()->countEntries($deptSQL);
                                                    if ($dept_exist == 0) {
                                                        $department_style = "style=color:#FF0000";
                                                        $chk3 = 1;
                                                        $department .= " (This department is not registered with this company)";
                                                    } else {
                                                        $department_style = "";
                                                        $chk3 = 0;
                                                    }
                                                }

                                                if (empty($legislation)) {
                                                    $legislation_style = "style=color:#FF0000";
                                                    $chk4 = 1;
                                                    $legislation = "Required";
                                                } else {
                                                    $legisSQL = "SELECT * FROM mas_legislation WHERE legislation='$legislation' AND company_id='$login_company_id'";
                                                    $legis_exist = connect_db()->countEntries($legisSQL);
                                                    if ($legis_exist == 0) {
                                                        $legislation_style = "style=color:#FF0000";
                                                        $chk4 = 1;
                                                        $legislation .= " (This legislation is not registered with this company)";
                                                    } else {
                                                        $dept_legisSQL = "";
                                                        $dept_legisSQL .= "SELECT * FROM mas_legislation a INNER JOIN mas_department b ON a.department_id=b.id ";
                                                        $dept_legisSQL .= "WHERE a.legislation='$legislation' AND b.department='$department' AND a.company_id='$login_company_id'";
                                                        $dept_legis_exist = connect_db()->countEntries($dept_legisSQL);
                                                        if ($dept_legis_exist == 0) {
                                                            $legislation_style = "style=color:#FF0000";
                                                            $department_style = "style=color:#FF0000";
                                                            $chk4 = 1;
                                                            $legislation .= " (Not matched with department for this company)";
                                                        } else {
                                                            $department_style = "";
                                                            $legislation_style = "";
                                                            $chk4 = 0;
                                                        }
                                                    }
                                                }
                                                if (empty($due_date)) {
                                                    $due_date_style = "style=color:#FF0000";
                                                    $chk5 = 1;
                                                    $due_date = "Required";
                                                } else {
                                                  /*  if (date("Y-m-d", strtotime($due_date)) <= date("Y-m-d", strtotime(curr_date_time()))) {
                                                      $due_date_style = "style=color:#FF0000";
                                                      $chk5 = 1;
                                                      $due_date .= " (Must be beyond current date)";
                                                      } else {*/ 
                                                    $due_date_style = "";
                                                    $chk5 = 0;
                                                    //}
                                                }
                                                if (empty($location)) {
                                                    $location_style = "style=color:#FF0000";
                                                    $chk6 = 1;
                                                    $location = "Required";
                                                } else {
                                                    $locSQL = "SELECT * FROM mas_location WHERE location='$location' AND company_id='$login_company_id'";
                                                    $loc_exist = connect_db()->countEntries($locSQL);
                                                    if ($loc_exist == 0) {
                                                        $location_style = "style=color:#FF0000";
                                                        $chk6 = 1;
                                                        $location .= " (This location is not registered with this company)";
                                                    } else {
                                                        $location_style = "";
                                                        $chk6 = 0;
                                                    }
                                                }
                                                if (empty($activity)) {
                                                    $activity_style = "style=color:#FF0000";
                                                    $chk7 = 1;
                                                    $activity = "Required";
                                                } else {
                                                    $actSQL = "SELECT * FROM mas_activity WHERE activity='$activity' AND company_id='$login_company_id'";
                                                    $act_exist = connect_db()->countEntries($actSQL);
                                                    if ($act_exist == 0) {
                                                        $activity_style = "style=color:#FF0000";
                                                        $chk7 = 1;
                                                        $activity .= " (This activity is not registered with this company)";
                                                    } else {
                                                        $activity_style = "";
                                                        $chk7 = 0;
                                                    }
                                                }
                                                if (empty($mode)) {
                                                    $mode_style = "style=color:#FF0000";
                                                    $chk8 = 1;
                                                    $mode = "Required";
                                                } else {
                                                    $modeSQL = "SELECT * FROM mas_mode WHERE mode='$mode' AND company_id='$login_company_id'";
                                                    $mode_exist = connect_db()->countEntries($modeSQL);
                                                    if ($mode_exist == 0) {
                                                        $mode_style = "style=color:#FF0000";
                                                        $chk8 = 1;
                                                        $mode .= " (This mode is not registered with this company)";
                                                    } else {
                                                        $mode_style = "";
                                                        $chk8 = 0;
                                                    }
                                                }
                                                if (empty($purpose)) {
                                                    $purpose_style = "style=color:#FF0000";
                                                    $chk9 = 1;
                                                    $purpose = "Required";
                                                } else {
                                                    if (($purpose == "Others") || ($purpose == "others")) {
                                                        $purpose_style = "";
                                                        $chk9 = 0;
                                                    } else {
                                                        $purposeSQL = "SELECT * FROM mas_purpose WHERE purpose='$purpose' AND company_id='$login_company_id'";
                                                        $purpose_exist = connect_db()->countEntries($purposeSQL);
                                                        if ($purpose_exist == 0) {
                                                            $purpose_style = "style=color:#FF0000";
                                                            $chk9 = 1;
                                                            $purpose .= " (This purpose is not registered with this company)";
                                                        } else {
                                                            $purpose_style = "";
                                                            $chk9 = 0;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td <?php echo $user_style; ?>><?php echo $user_name; ?></td>
                                                    <td <?php echo $department_style; ?>><?php echo $department; ?></td>
                                                    <td <?php echo $legislation_style; ?>><?php echo $legislation; ?></td>
                                                    <td <?php echo $due_date_style; ?>><?php echo $due_date; ?></td>
                                                    <td <?php echo $activity_style; ?>><?php echo $activity; ?></td>
                                                    <td <?php echo $purpose_style; ?>><?php echo $purpose; ?></td>
                                                    <td <?php echo $mode_style; ?>><?php echo $mode; ?></td>
                                                    <td <?php echo $location_style; ?>><?php echo $location; ?></td>
                                                    <td <?php echo $user_assign_style; ?>><?php echo $assign_user; ?></td>
                                                </tr>
                                                <?php
                                                if (($chk1 == 1) || ($chk2 == 1) || ($chk3 == 1) || ($chk4 == 1) || ($chk5 == 1) || ($chk6 == 1) || ($chk7 == 1) || ($chk8 == 1) || ($chk9 == 1)) {
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
                                        <button type="submit" name="confirm_sc_data" id="confirm_sc_data" class="btn btn-info">
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
} else {
    header("location:index.php");
}
?>