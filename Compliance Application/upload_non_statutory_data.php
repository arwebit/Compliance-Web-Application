<?php
$main_page = "Upload Data";
$page = "Upload Non-Statutory Data";
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
                                                <input type="file" id="non_statutory_data_upload" required="required" name="non_statutory_data_upload" class="form-control" />
                                            </div>
                                            <button type="submit" id="upload_non_statutory_data" name="upload_non_statutory_data" class="btn btn-primary mr-2">Upload</button>
                                            <br/><br/>
                                            <b class="text-success" id="success_message"><?php echo $non_stat_success_msg; ?></b>
                                            <b class="text-danger" id="success_message"><?php echo $non_stat_error_msg; ?></b>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                        <?php
                        $upl_mng_cmpSQL = "SELECT * FROM upl_mng_cmp";
                        $upl_mng_cmp_exist = connect_db()->countEntries($upl_mng_cmpSQL);
                        if ($upl_mng_cmp_exist > 0) {
                            ?>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                                    <div class="box">
                                        <h4 class="box-header">Preview</h4>
                                        <div class="box-body">
                                            <table class="table table-bordered" id="example1">
                                                <thead>
                                                    <tr>
                                                        <th><b>USERNAME</b></th>
                                                        <th><b>DEPARTMENT</b></th>
                                                        <th><b>DESCRIPTION</b></th>
                                                        <th><b>DUE DATE</b></th>
                                                        <th><b>DOCUMENT REF NO</b></th>
                                                        <th><b>ASSIGN USER</b></th>
                                                        <th><b>COMPLIANCE NATURE</b></th>
                                                        <th><b>LOCATION</b></th>
                                                        <th><b>REMARKS</b></th>

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
                                                    $mng_SQL = "SELECT * FROM upl_mng_cmp";
                                                    $fetch_mng = json_decode(ret_json_str($mng_SQL));
                                                    foreach ($fetch_mng as $fetch_mngs) {
                                                        $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->username));
                                                        $assign_user = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->assign_user));
                                                        $department = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->department));
                                                        $description = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->description));
                                                        $due_date = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->due_date));
                                                        $comp_nature = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->comp_nature));
                                                        $pol_doc_no = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->policy_document_no));
                                                        $remarks = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->remarks));
                                                        $location = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->location));

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
                                                                if ($compuser_name_exist == 0) {
                                                                    $user_style = "style=color:#FF0000";
                                                                    $chk1 = 1;
                                                                    $user_name .= " (This user is not registered with this company)";
                                                                } else {
                                                                    $user_style = "";
                                                                    $chk1 = 0;
                                                                }
                                                            }
                                                        }
                                                        if (empty($assign_user)) {
                                                            $user_assign_style = "style=color:#FF0000";
                                                            $chk2 = 1;
                                                            $assign_user = "Required";
                                                        } else {
                                                            $user_assignSQL = "SELECT * FROM member_registration WHERE user_name='$assign_user'";
                                                            $user_assign_exist = connect_db()->countEntries($user_assignSQL);
                                                            if ($user_assign_exist == 0) {
                                                                $user_assign_style = "style=color:#FF0000";
                                                                $chk2 = 1;
                                                                $assign_user .= " (Not matched with master)";
                                                            } else {
                                                                $compuser_assignSQL = "SELECT * FROM member_registration WHERE user_name='$assign_user' AND company_id='$login_company_id'";
                                                                $compuser_assign_exist = connect_db()->countEntries($compuser_assignSQL);
                                                                if ($compuser_assign_exist == 0) {
                                                                    $user_assign_style = "style=color:#FF0000";
                                                                    $chk2 = 1;
                                                                    $assign_user .= " (This user is not registered with this company)";
                                                                } else {
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
                                                        if (empty($description)) {
                                                            $description_style = "style=color:#FF0000";
                                                            $chk4 = 1;
                                                            $description = "Required";
                                                        } else {
                                                            $description_style = "";
                                                            $chk4 = 0;
                                                        }
                                                        if (empty($due_date)) {
                                                            $due_date_style = "style=color:#FF0000";
                                                            $chk5 = 1;
                                                            $due_date = "Required";
                                                        } else {
                                                           /* if (date("Y-m-d", strtotime($due_date)) <= date("Y-m-d", strtotime(curr_date_time()))) {
                                                              $due_date_style = "style=color:#FF0000";
                                                              $chk5 = 1;
                                                              $due_date .= " (Must be beyond current date)";
                                                              } else { */
                                                            $due_date_style = "";
                                                            $chk5 = 0;
                                                           // }
                                                        }
                                                        if (empty($remarks)) {
                                                            $remarks_style = "style=color:#FF0000";
                                                            $chk6 = 1;
                                                            $remarks = "Required";
                                                        } else {
                                                            $remarks_style = "";
                                                            $chk6 = 0;
                                                        }

                                                        if (empty($pol_doc_no)) {
                                                            $pol_doc_no_style = "style=color:#FF0000";
                                                            $chk7 = 1;
                                                            $pol_doc_no = "Required";
                                                        } else {
                                                            $pol_doc_no_style = "";
                                                            $chk7 = 0;
                                                        }

                                                        if (empty($comp_nature)) {
                                                            $comp_nature_style = "style=color:#FF0000";
                                                            $chk8 = 1;
                                                            $comp_nature = "Required";
                                                        } else {
                                                            $comp_nature_style = "";
                                                            $chk8 = 0;
                                                        }
                                                        if (empty($location)) {
                                                            $location_style = "style=color:#FF0000";
                                                            $chk9 = 1;
                                                            $location = "Required";
                                                        } else {
                                                            $locSQL = "SELECT * FROM mas_location WHERE location='$location' AND company_id='$login_company_id'";
                                                            $loc_exist = connect_db()->countEntries($locSQL);
                                                            if ($loc_exist == 0) {
                                                                $location_style = "style=color:#FF0000";
                                                                $chk9 = 1;
                                                               $location .= " (This location is not registered with this company)";
                                                            } else {
                                                                $location_style = "";
                                                                $chk9 = 0;
                                                            }
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td <?php echo $user_style; ?>><?php echo $user_name; ?></td>
                                                            <td <?php echo $department_style; ?>><?php echo $department; ?></td>
                                                            <td <?php echo $description_style; ?>><?php echo $description; ?></td>
                                                            <td <?php echo $due_date_style; ?>><?php echo $due_date; ?></td>
                                                            <td <?php echo $pol_doc_no_style; ?>><?php echo $pol_doc_no; ?></td>
                                                            <td <?php echo $user_assign_style; ?>><?php echo $assign_user; ?></td>
                                                            <td <?php echo $comp_nature_style; ?>><?php echo $comp_nature; ?></td>
                                                            <td <?php echo $location_style; ?>><?php echo $location; ?></td>
                                                            <td <?php echo $remarks_style; ?>><?php echo $remarks; ?></td>
                                                        </tr>
                                                        <?php
                                                        if (($chk1 == 1) || ($chk2 == 1) || ($chk3 == 1) || ($chk4 == 1) || ($chk5 == 1) || ($chk6 == 1) || ($chk7 == 1) || ($chk8 == 1) || ($chk8 == 9)) {
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
                                                    <button type="submit" name="confirm_nsc_data" id="confirm_nsc_data" class="btn btn-info">
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