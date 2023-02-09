<?php
$main_page = "Statutory Compliance";
$page = "Show Statutory Compliance";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['rm_id'] && $_GET['link']) {
        $link = $_GET['link'];
        $rm_id = $_GET['rm_id'];
        $rm_SQL = "";
        $rm_SQL .= "SELECT b.mem_name, a.username, a.assign_user, c.mem_name as assigner, a.rm_id, a.location_id, a.department_id, a.legislation_id, a.description, a.activity_id, a.mode_id, ";
        $rm_SQL .= "a.purpose_id, a.purpose_descr, a.renewed_date, a.due_date, a.purpose_value, a.budgeted_cost, a.actual_cost, a.reference, ";
        $rm_SQL .= "a.actual_value_covered, a.policy_document_no, a.remarks, a.status, a.update_status FROM risk_management a ";
        $rm_SQL .= "INNER JOIN member_registration b ON a.username=b.user_name INNER JOIN member_registration c ON ";
        $rm_SQL .= "a.assign_user=c.user_name INNER JOIN mas_location d ON a.location_id=d.id WHERE a.id='$rm_id'";
        $fetch_rm = json_decode(ret_json_str($rm_SQL));
        foreach ($fetch_rm as $fetch_rms) {
            $rmid = $fetch_rms->rm_id;
            $username = $fetch_rms->username;
            $mem_name = $fetch_rms->mem_name;
            $department_id = $fetch_rms->department_id;
            $legislation_id = $fetch_rms->legislation_id;
            $location_id = $fetch_rms->location_id;
            $description = $fetch_rms->description;
            $activity_id = $fetch_rms->activity_id;
            $mode_id = $fetch_rms->mode_id;
            $purpose_id = $fetch_rms->purpose_id;
            $purpose_descr = $fetch_rms->purpose_descr;
            $renewed_date = $fetch_rms->renewed_date == "" ? "" : $fetch_rms->renewed_date;
            $due_date = $fetch_rms->due_date;
            $purpose_value = $fetch_rms->purpose_value;
            $budgeted_cost = $fetch_rms->budgeted_cost;
            $actual_cost = $fetch_rms->actual_cost;
            $reference = $fetch_rms->reference;
            $actual_value_covered = $fetch_rms->actual_value_covered;
            $policy_document_no = $fetch_rms->policy_document_no;
            $remarks = $fetch_rms->remarks;
            $rm_status = $fetch_rms->status;
            $update_status = $fetch_rms->update_status;
            $assign_person = $fetch_rms->assigner;
            $assign_user = $fetch_rms->assign_user;
        }
        $str_due_date = strtotime($due_date);
        $pending_crosseddiff = $str_due_date - strtotime(curr_date_time());
        $tasks_not_completed = round($pending_crosseddiff / (60 * 60 * 24));
        if ((!empty($renewed_date)) || ($renewed_date != "")) {
            $str_renew_date = strtotime($renewed_date);
            $delay_diff = $str_renew_date - $str_due_date;
            $tasks_delay = round($delay_diff / (60 * 60 * 24));
            if ($tasks_delay <= 0) {
                $tasks_delay = "0";
            }
        } else {
            $tasks_delay = "0";
        }

        $rmbkup_SQL = "";
        $rmbkup_SQL .= "SELECT b.mem_name, a.assign_user, c.mem_name as assigner, a.location_id, a.rm_id, a.department_id, a.legislation_id, a.description, a.activity_id, a.mode_id, ";
        $rmbkup_SQL .= "a.purpose_id, a.purpose_descr, a.renewed_date, a.due_date, a.purpose_value, a.budgeted_cost, a.actual_cost, a.reference, ";
        $rmbkup_SQL .= "a.actual_value_covered, a.policy_document_no, a.remarks, a.status, a.update_status FROM rm_bkup a ";
        $rmbkup_SQL .= "INNER JOIN member_registration b ON a.username=b.user_name INNER JOIN member_registration c ON ";
        $rmbkup_SQL .= "a.assign_user=c.user_name INNER JOIN mas_location d ON a.location_id=d.id WHERE a.id='$rm_id'";
        $fetch_rmbkup = json_decode(ret_json_str($rmbkup_SQL));
        foreach ($fetch_rmbkup as $fetch_rmbkups) {
            $bkup_department_id = $fetch_rmbkups->department_id;
            $bkup_legislation_id = $fetch_rmbkups->legislation_id;
            $bkup_description = $fetch_rmbkups->description;
            $bkup_activity_id = $fetch_rmbkups->activity_id;
            $bkup_location_id = $fetch_rmbkups->location_id;
            $bkup_mode_id = $fetch_rmbkups->mode_id;
            $bkup_purpose_id = $fetch_rmbkups->purpose_id;
            $bkup_purpose_descr = $fetch_rmbkups->purpose_descr;
            $bkup_renewed_date = $fetch_rmbkups->renewed_date == "" ? "" : $fetch_rmbkups->renewed_date;
            $bkup_due_date = $fetch_rmbkups->due_date;
            $bkup_purpose_value = $fetch_rmbkups->purpose_value;
            $bkup_budgeted_cost = $fetch_rmbkups->budgeted_cost;
            $bkup_actual_cost = $fetch_rmbkups->actual_cost;
            $bkup_reference = $fetch_rmbkups->reference;
            $bkup_actual_value_covered = $fetch_rmbkups->actual_value_covered;
            $bkup_policy_document_no = $fetch_rmbkups->policy_document_no;
            $bkup_remarks = $fetch_rmbkups->remarks;
            $bkup_rm_status = $fetch_rmbkups->status;
            $bkup_assign_user = $fetch_rmbkups->assign_user;
            $location = $fetch_rmbkups->location;
        }

        if ($bkup_department_id != $department_id) {
            $dept_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_legislation_id != $legislation_id) {
            $legislation_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_description != $description) {
            $description_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_activity_id != $activity_id) {
            $activity_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_mode_id != $mode_id) {
            $mode_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_purpose_id != $purpose_id) {
            $purpose_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_purpose_descr != $purpose_descr) {
            $purpose_descr_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_renewed_date != $renewed_date) {
            $renew_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_due_date != $due_date) {
            $duedate_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_purpose_value != $purpose_value) {
            $puposeval_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_budgeted_cost != $budgeted_cost) {
            $budget_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_actual_cost != $actual_cost) {
            $actual_cost_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_reference != $reference) {
            $reference_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_actual_value_covered != $actual_value_covered) {
            $act_val_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_policy_document_no != $policy_document_no) {
            $pol_doc_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_remarks != $remarks) {
            $remarkschange = "style='background-color: #d9bce7;'";
        }
        if ($bkup_rm_status != $rm_status) {
            $status_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_location_id != $location_id) {
            $location_change = "style='background-color: #d9bce7;'";
        }
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
                                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                                <li class="active"><?php echo $page; ?></li>
                            </ol>
                        </section>

                        <!-- Main content -->
                        <section class="content">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box">
                                        <div class="box-header">
                                            <h4 style="float: left; font-weight: bolder;">
                                                SC ID : <?php echo $rmid; ?>, Employee name : <?php echo $mem_name; ?>          
                                            </h4>
                                            <h4 style="float: right;">
                                                <a href="<?php echo $link; ?>">
                                                    <button class="btn btn-info">
                                                        <span class="fa fa-arrow-left"></span> </button>  
                                                </a>
                                            </h4>
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            <table class="table table-hover table-striped table-responsive">
                                                <tr>
                                                    <td <?php echo $dept_change; ?>> <b>Department </b></td> 
                                                    <td <?php echo $dept_change; ?>>
                                                        <?php
                                                        $department_SQL = "SELECT * FROM mas_department WHERE id='$department_id'";
                                                        $fetch_department = json_decode(ret_json_str($department_SQL));
                                                        foreach ($fetch_department as $fetch_departments) {
                                                            echo $fetch_departments->department;
                                                        }
                                                        if (!empty($dept_change)) {
                                                            $departmentc_SQL = "SELECT * FROM mas_department WHERE id='$bkup_department_id'";
                                                            $fetch_departmentc = json_decode(ret_json_str($departmentc_SQL));
                                                            foreach ($fetch_departmentc as $fetch_departmentcs) {
                                                                echo " <i>(" . $fetch_departmentcs->department . ") </i>";
                                                            }
                                                        }
                                                        ?> 
                                                    </td>
                                                    <td <?php echo $legislation_change; ?>>
                                                        <b>Legislation  </b>  
                                                    </td> 
                                                    <td <?php echo $legislation_change; ?>>
                                                        <?php
                                                        $legislation_SQL = "SELECT * FROM mas_legislation WHERE id='$legislation_id'";
                                                        $fetch_legislation = json_decode(ret_json_str($legislation_SQL));
                                                        foreach ($fetch_legislation as $fetch_legislations) {
                                                            echo $fetch_legislations->legislation;
                                                        }
                                                        if (!empty($legislation_change)) {
                                                            $legislationc_SQL = "SELECT * FROM mas_legislation WHERE id='$bkup_legislation_id'";
                                                            $fetch_legislationc = json_decode(ret_json_str($legislationc_SQL));
                                                            foreach ($fetch_legislationc as $fetch_legislationcs) {
                                                                echo " <i>(" . $fetch_legislationcs->legislation . ") </i>";
                                                            }
                                                        }
                                                        ?>    
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td <?php echo $description_change; ?>><b>Description  </b></td>
                                                    <td <?php echo $description_change; ?>>
                                                        <?php
                                                        echo $description;
                                                        if (!empty($description_change)) {
                                                            echo " <i>(" . $bkup_description . ") </i>";
                                                        }
                                                        ?> 
                                                    </td>
                                                    <td <?php echo $activity_change; ?>><b>Activity </b></td>
                                                    <td <?php echo $activity_change; ?>>
                                                        <?php
                                                        $activity_SQL = "SELECT * FROM mas_activity WHERE id='$activity_id'";
                                                        $fetch_activity = json_decode(ret_json_str($activity_SQL));
                                                        foreach ($fetch_activity as $fetch_activities) {
                                                            echo $fetch_activities->activity;
                                                        }
                                                        if (!empty($activity_change)) {
                                                            $activityc_SQL = "SELECT * FROM mas_activity WHERE id='$bkup_activity_id'";
                                                            $fetch_activityc = json_decode(ret_json_str($activityc_SQL));
                                                            foreach ($fetch_activityc as $fetch_activitiecs) {
                                                                echo " <i>(" . $fetch_activitiecs->activity . ") </i>";
                                                            }
                                                        }
                                                        ?>   
                                                    </td> 
                                                </tr>
                                                <tr>
                                                    <td <?php echo $mode_change; ?>> <b>Mode  </b></td>
                                                    <td <?php echo $mode_change; ?>>
                                                        <?php
                                                        $mode_SQL = "SELECT * FROM mas_mode WHERE id='$mode_id'";
                                                        $fetch_mode = json_decode(ret_json_str($mode_SQL));
                                                        foreach ($fetch_mode as $fetch_modes) {
                                                            echo $fetch_modes->mode;
                                                        }
                                                        if (!empty($mode_change)) {
                                                            $modec_SQL = "SELECT * FROM mas_mode WHERE id='$bkup_mode_id'";
                                                            $fetch_modec = json_decode(ret_json_str($modec_SQL));
                                                            foreach ($fetch_modec as $fetch_modecs) {
                                                                echo " <i>(" . $fetch_modecs->mode . ") </i>";
                                                            }
                                                        }
                                                        ?> 
                                                    </td>
                                                    <td <?php echo $location_change; ?>><b>Location  </b></td>
                                                    <td <?php echo $location_change; ?>>
                                                        <?php
                                                        $location_SQL = "SELECT * FROM mas_location WHERE id='$location_id'";
                                                        $fetch_location = json_decode(ret_json_str($location_SQL));
                                                        foreach ($fetch_location as $fetch_locations) {
                                                            echo $fetch_locations->location;
                                                        }
                                                        if (!empty($location_change)) {
                                                            $locationc_SQL = "SELECT * FROM mas_location WHERE id='$bkup_location_id'";
                                                            $fetch_locationc = json_decode(ret_json_str($locationc_SQL));
                                                            foreach ($fetch_locationc as $fetch_locationcs) {
                                                                echo " <i>(" . $fetch_locationcs->location . ") </i>";
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td <?php echo $purpose_change; ?>><b>Purpose  </b></td>
                                                    <td <?php echo $purpose_change; ?>>
                                                        <?php
                                                        if ($purpose_id == -1) {
                                                            echo "Others";
                                                        } else {
                                                            $purpose_SQL = "SELECT * FROM mas_purpose WHERE id='$purpose_id'";
                                                            $fetch_purpose = json_decode(ret_json_str($purpose_SQL));
                                                            foreach ($fetch_purpose as $fetch_purposes) {
                                                                echo $fetch_purposes->purpose;
                                                            }
                                                        }
                                                        if (!empty($purpose_change)) {
                                                            $purposec_SQL = "SELECT * FROM mas_purpose WHERE id='$bkup_purpose_id'";
                                                            $fetch_purposec = json_decode(ret_json_str($purposec_SQL));
                                                            foreach ($fetch_purposec as $fetch_purposecs) {
                                                                echo " <i>(" . $fetch_purposecs->purpose . ") </i>";
                                                            }
                                                        }
                                                        ?>  
                                                    </td>
                                                    <td <?php echo $purpose_descr_change; ?>> <b>Purpose description  </b></td>
                                                    <td <?php echo $purpose_descr_change; ?>>
                                                        <?php
                                                        echo $purpose_descr;
                                                        if (!empty($purpose_descr_change)) {
                                                            echo " <i>(" . $bkup_purpose_descr . ") </i>";
                                                        }
                                                        ?>  
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td <?php echo $duedate_change; ?>><b>Due date </b></td>
                                                    <td <?php echo $duedate_change; ?>>
                                                        <?php
                                                        echo date("d-m-Y", strtotime($due_date));
                                                        if (!empty($duedate_change)) {
                                                            echo " <i>(" . date("d-m-Y", strtotime($bkup_due_date)) . ") </i>";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td <?php echo $renew_change; ?>> <b>Task completion date  </b></td>
                                                    <td <?php echo $renew_change; ?>>
                                                        <?php
                                                        echo $renewed_date == "" ? "" : date("d-m-Y", strtotime($renewed_date));
                                                        if (!empty($purpose_change)) {
                                                            echo " <i>(" . $bkup_renewed_date == "" ? "" : date("d-m-Y", strtotime($bkup_renewed_date)) . ") </i>";
                                                        }
                                                        ?>    
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Task still not completed  </b></td>
                                                    <td>
                                                        <?php
                                                        if (empty($renewed_date)) {
                                                            if ($tasks_not_completed >= 0) {
                                                                echo "Due : ";
                                                            } else {
                                                                echo "Overdue :  ";
                                                            }
                                                            echo abs($tasks_not_completed) . " day(s)";
                                                        } else {
                                                            echo "";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><b>Task delayed  </b></td>
                                                    <td>
                                                        <?php echo $tasks_delay; ?> day(s)
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td <?php echo $budget_change; ?>><b>Budgeted cost  </b></td> 
                                                    <td <?php echo $budget_change; ?>>
                                                        <?php
                                                        echo $budgeted_cost;
                                                        if (!empty($budget_change)) {
                                                            echo " <i>(" . $bkup_budgeted_cost . ") </i>";
                                                        }
                                                        ?>      
                                                    </td>
                                                    <td <?php echo $act_val_change; ?>> <b>Actual value covered </b></td> 
                                                    <td <?php echo $act_val_change; ?>>
                                                        <?php
                                                        echo $actual_value_covered;
                                                        if (!empty($act_val_change)) {
                                                            echo " <i>(" . $bkup_actual_value_covered . ") </i>";
                                                        }
                                                        ?>      
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td <?php echo $actual_cost_change; ?>><b>Actual cost </b></td>
                                                    <td <?php echo $actual_cost_change; ?>>
                                                        <?php
                                                        echo $actual_cost;
                                                        if (!empty($actual_cost_change)) {
                                                            echo " <i>(" . $bkup_actual_cost . ") </i>";
                                                        }
                                                        ?>  
                                                    </td>
                                                    <td <?php echo $reference_change; ?>><b>Reference  </b></td>
                                                    <td <?php echo $reference_change; ?>>
                                                        <?php
                                                        echo $reference;
                                                        if (!empty($reference_change)) {
                                                            echo " <i>(" . $bkup_reference . ") </i>";
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td <?php echo $pol_doc_change; ?>><b>Document ref  </b></td>
                                                    <td <?php echo $pol_doc_change; ?>>
                                                        <?php
                                                        echo $policy_document_no;
                                                        if (!empty($pol_doc_change)) {
                                                            echo " <i>(" . $bkup_policy_document_no . ") </i>";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td <?php echo $assign_change; ?>> <b>Assignee  </b></td>
                                                    <td <?php echo $assign_change; ?>>
                                                        <?php
                                                        echo $assign_person;
                                                        if (!empty($assign_change)) {
                                                            echo " <i>(" . $bkup_assign_user . ") </i>";
                                                        }
                                                        ?> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td <?php echo $status_change; ?>><b>Status </b></td>
                                                    <td <?php echo $status_change; ?>>
                                                        <?php
                                                        if ($rm_status == "0") {
                                                            echo "Not-completed";
                                                        } else if ($rm_status == "-1") {
                                                            echo "Canceled";
                                                        } else {
                                                            echo "Completed";
                                                        }
                                                        if (!empty($status_change)) {
                                                            if ($bkup_rm_status == "0") {
                                                                echo " <i>(Not-completed)</i>";
                                                            } else if ($bkup_rm_status == "2") {
                                                                echo " <i>(Canceled) </i>";
                                                            } else {
                                                                echo " <i>(Completed) </i>";
                                                            }
                                                        }
                                                        ?>     
                                                    </td>
                                                    <td <?php echo $remark_change; ?>><b>Remarks  </b></td>
                                                    <td <?php echo $remark_change; ?>>
                                                        <?php
                                                        echo $remarks;
                                                        if (!empty($remark_change)) {
                                                            echo " <i>(" . $bkup_remarks . ")</i>";
                                                        }
                                                        ?>  
                                                    </td>
                                                </tr>
                                                <?php
                                                $countufSQL = "SELECT * FROM upload_document_files WHERE compliance_type='SC' AND non_stat_stat_id='$rm_id'";
                                                $upldFileExists = connect_db()->countEntries($countufSQL);
                                                if ($upldFileExists > 0) {
                                                    ?>
                                                    <tr>
                                                        <th colspan="4"><center>UPLOADED FILES</center></th>
                                                    </tr>
                                                    <?php
                                                    $upload_SQL = "";
                                                    $upload_SQL .= "SELECT a.id, a.description, a.document_no, b.rm_id, a.compliance_type, a.file_data, a.file_data_extension, ";
                                                    $upload_SQL .= "a.upload_by, a.upload_date FROM upload_document_files a INNER JOIN risk_management b ON a.non_stat_stat_id=b.id ";
                                                    $upload_SQL .= "WHERE a.compliance_type='SC' AND a.non_stat_stat_id='$rm_id' ORDER BY a.upload_date DESC";
                                                    $fetch_upload = json_decode(ret_json_str($upload_SQL));
                                                    foreach ($fetch_upload as $fetch_uploads) {
                                                        $file_id = $fetch_uploads->id;
                                                        $statutory_id = $fetch_uploads->rm_id;
                                                        $uploaded_by = $fetch_uploads->upload_by;
                                                        $uploaded_date = $fetch_uploads->upload_date;
                                                        $document_no = $fetch_uploads->document_no;
                                                        $description = $fetch_uploads->description;
                                                        $stat_file_data = $fetch_uploads->file_data;
                                                        $stat_file_data_extension = $fetch_uploads->file_data_extension;
                                                        $stat_file_data_loc = $statutory_file_dir . str_replace(" ", "_", $file_id) . "." . $stat_file_data_extension;
                                                        $stat_files = fopen($stat_file_data_loc, "w+");
                                                        fwrite($stat_files, base64_decode($stat_file_data));
                                                        fclose($stat_files);
                                                        ?>
                                                        <tr>
                                                            <th>Document no.</th>
                                                            <td><?php echo $document_no; ?> &nbsp;&nbsp;
                                                                <a href="<?php echo $stat_file_data_loc; ?>" target="_blank">
                                                                    <span class="fa fa-file "></span></a>
                                                            </td>
                                                            <th>Description</th>
                                                            <td><?php echo nl2br($description); ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </table>
                                            <br/><br/>
                                            <?php
                                            if ($update_status != 1) {
                                                if (($assign_user == $login_user) && ($update_status == "3")) {
                                                    ?>
                                                    <button type="button" id="approve_rm" class="btn btn-primary">
                                                        Approve</button>
                                                    <?php
                                                }
                                            }
                                            if ($rm_status == 0) {
                                                if (($update_status != 3) || ($update_status != 4)) {
                                                    if ($assign_user == $login_user) {
                                                        ?>
                                                        <a href="mark_complete_rm.php?rm_id=<?php echo $rm_id; ?>" class="btn btn-info">
                                                            MARK COMPLETE  
                                                        </a>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            <b id="rm_success_message" class="text-success"></b>
                                            <b id="rm_error_message" class="text-danger"></b>
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
                <script type="text/javascript">
                    $(document).ready(function () {
                        /* *********************************** APPROVE RISK MANAGEMENT *********************************** */

                        $('#approve_rm').click(function () {
                            var rm_id = "<?php echo $rm_id; ?>";
                            var approve_rm = 2;
                            $.ajax({
                                type: "POST",
                                url: "getAPI.php?approve_risk_management",
                                dataType: "json",
                                data: {
                                    rm_id: rm_id,
                                    approve_rm: approve_rm
                                },
                                success: function (RetVal) {
                                    if (RetVal.message === "Success") {
                                        $("#rm_success_message").text(RetVal.data);
                                        $("#rm_error_message").text("");
                                    } else {
                                        $("#rm_success_message").text("");
                                        $("#rm_error_message").text(RetVal.data);
                                    }

                                }
                            });
                        });

                        /* *********************************** APPROVE RISK MANAGEMENT *********************************** */
                    });
                </script>
            </body>
        </html>
        <?php
    }
} else {
    header("location:index.php");
}
?>
