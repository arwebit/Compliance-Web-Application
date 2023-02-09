<?php
$main_page = "Non-statutory Compliance";
$page = "Show Non-statutory Compliance";
include './file_includes.php';
if ($_SESSION['crm_member']) {
    $login_user = $_SESSION['crm_member'];
    if ($_GET['mng_id'] && $_GET['link']) {
        $link = $_GET['link'];
        $mngid = $_GET['mng_id'];
        $mngSQL = "";
        $mngSQL .= "SELECT b.mem_name, a.username, a.assign_user, c.mem_name as assigner, a.mng_id, a.department_id, a.location_id, ";
        $mngSQL .= "a.description, a.comp_nature, a.renewed_date, a.due_date, a.purpose_value, a.reference, a.budgeted_cost, ";
        $mngSQL .= "a.actual_transaction_value, a.policy_document_no, a.remarks, a.status, a.update_status FROM mng_cmp a ";
        $mngSQL .= "INNER JOIN member_registration b ON a.username=b.user_name INNER JOIN member_registration c ON ";
        $mngSQL .= "a.assign_user=c.user_name WHERE a.id='$mngid'";
        $fetch_mngc = json_decode(ret_json_str($mngSQL));
        foreach ($fetch_mngc as $fetch_mng) {
            $mng_id = $fetch_mng->mng_id;
            $username = $fetch_mng->username;
            $mem_name = $fetch_mng->mem_name;
            $department_id = $fetch_mng->department_id;
            $description = $fetch_mng->description;
            $comp_nature = $fetch_mng->comp_nature;
            $renewed_date = $fetch_mng->renewed_date == "" ? "" : $fetch_mng->renewed_date;
            $due_date = $fetch_mng->due_date;
            $purpose_value = $fetch_mng->purpose_value;
            $budgeted_cost = $fetch_mng->budgeted_cost;
            $location_id = $fetch_mng->location_id;
            $actual_transaction_value = $fetch_mng->actual_transaction_value;
            $reference = $fetch_mng->reference;
            $policy_document_no = $fetch_mng->policy_document_no;
            $remarks = $fetch_mng->remarks;
            $mngstatus = $fetch_mng->status;
            $update_status = $fetch_mng->update_status;
            $assign_person = $fetch_mng->assigner;
            $assign_user = $fetch_mng->assign_user;
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

        $mngbkup_SQL = "";
        $mngbkup_SQL .= "SELECT b.mem_name, a.username, a.assign_user, c.mem_name as assigner, a.mng_id, a.department_id, ";
        $mngbkup_SQL .= "a.description, a.comp_nature, a.renewed_date, a.due_date, a.purpose_value, a.reference, a.location_id, ";
        $mngbkup_SQL .= "a.actual_transaction_value, a.policy_document_no, a.remarks, a.budgeted_cost, a.status, a.update_status FROM mng_cmp_bkup a ";
        $mngbkup_SQL .= "INNER JOIN member_registration b ON a.username=b.user_name INNER JOIN member_registration c ON ";
        $mngbkup_SQL .= "a.assign_user=c.user_name WHERE a.id='$mngid'";
        $fetch_mngbkup = json_decode(ret_json_str($mngbkup_SQL));
        foreach ($fetch_mngbkup as $fetch_mngbkups) {
            $bkup_department_id = $fetch_mngbkups->department_id;
            $bkup_description = $fetch_mngbkups->description;
            $bkup_comp_nature = $fetch_mngbkups->comp_nature;
            $bkup_renewed_date = $fetch_mngbkups->renewed_date == "" ? "" : $fetch_mngbkups->renewed_date;
            $bkup_due_date = $fetch_mngbkups->due_date;
            $bkup_purpose_value = $fetch_mngbkups->purpose_value;
            $bkup_budgeted_cost = $fetch_mngbkups->budgeted_cost;
            $bkup_location_id = $fetch_mngbkups->location_id;
            $bkup_actual_transaction_value = $fetch_mngbkups->actual_transaction_value;
            $bkup_reference = $fetch_mngbkups->reference;
            $bkup_policy_document_no = $fetch_mngbkups->policy_document_no;
            $bkup_remarks = $fetch_mngbkups->remarks;
            $bkup_mng_status = $fetch_mngbkups->status;
            $bkup_update_status = $fetch_mngbkups->update_status;
            $bkup_assign_person = $fetch_mngbkups->assigner;
            $bkup_assign_user = $fetch_mngbkups->assign_user;
        }

        if ($bkup_department_id != $department_id) {
            $dept_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_description != $description) {
            $description_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_comp_nature != $comp_nature) {
            $comp_nature_change = "style='background-color: #d9bce7;'";
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
        if ($bkup_reference != $reference) {
            $reference_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_policy_document_no != $policy_document_no) {
            $pol_doc_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_remarks != $remarks) {
            $remarkschange = "style='background-color: #d9bce7;'";
        }
        if ($bkup_mng_status != $mngstatus) {
            $status_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_budgeted_cost != $budgeted_cost) {
            $budget_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_location_id != $location_id) {
            $location_change = "style='background-color: #d9bce7;'";
        }
        if ($bkup_actual_transaction_value != $actual_transaction_value) {
            $actual_transaction_val_change = "style='background-color: #d9bce7;'";
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
                                                NSC ID : <?php echo $mng_id; ?>, Employee name : <?php echo $mem_name; ?>          
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
                                            <table class="table table-hover">
                                                <tr>
                                                    <td <?php echo $dept_change; ?>><b>Department </b></td>
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
                                                                echo " <i>(" . $fetch_departmentcs->department . ")</i>";
                                                            }
                                                        }
                                                        ?>   
                                                    </td>
                                                    <td <?php echo $comp_nature_change; ?>><b>Compliance nature</b></td>
                                                    <td <?php echo $comp_nature_change; ?>>
                                                        <?php
                                                        echo $comp_nature;
                                                        if (!empty($comp_nature_change)) {
                                                            echo " <i>(" . $bkup_comp_nature . ")</i>";
                                                        }
                                                        ?>   
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td <?php echo $description_change; ?>> <b>Description  </b></td>
                                                    <td <?php echo $description_change; ?>>
                                                        <?php
                                                        echo $description;
                                                        if (!empty($description_change)) {
                                                            echo " <i>(" . $bkup_description . ")</i>";
                                                        }
                                                        ?>   
                                                    </td>
                                                    <td <?php echo $location_change; ?>><b>Location </b></td>
                                                    <td>
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
                                                                echo " <i>(" . $fetch_locationcs->location . ")</i>";
                                                            }
                                                        }
                                                        ?>      
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td <?php echo $duedate_change; ?>> <b>Due date </b></td>
                                                    <td <?php echo $duedate_change; ?>>
                                                        <?php
                                                        echo date("d-m-Y", strtotime($due_date));
                                                        if (!empty($duedate_change)) {
                                                            echo " <i>(" . date("d-m-Y", strtotime($bkup_due_date)) . ")</i>";
                                                        }
                                                        ?>   
                                                    </td>
                                                    <td <?php echo $renew_change; ?>><b>Task completion date </b></td>
                                                    <td <?php echo $renew_change; ?>>
                                                        <?php
                                                        echo $renewed_date == "" ? "" : date("d-m-Y", strtotime($renewed_date));
                                                        if (!empty($purpose_change)) {
                                                            echo " <i>(" . $bkup_renewed_date == "" ? "" : date("d-m-Y", strtotime($bkup_renewed_date)) . ")</i>";
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
                                                            echo " <i>(" . $bkup_budgeted_cost . ")</i>";
                                                        }
                                                        ?>  
                                                    </td>
                                                    <td <?php echo $actual_transaction_val_change; ?>><b>Actual transaction value  </b></td>
                                                    <td <?php echo $actual_transaction_val_change; ?>>
                                                        <?php
                                                        echo $actual_transaction_value;
                                                        if (!empty($actual_transaction_val_change)) {
                                                            echo " <i>(" . $bkup_actual_transaction_value . ")</i>";
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td <?php echo $pol_doc_change; ?>><b>Document Ref  </b></td>
                                                    <td <?php echo $pol_doc_change; ?>>
                                                        <?php
                                                        echo $policy_document_no;
                                                        if (!empty($pol_doc_change)) {
                                                            echo " <i>(" . $bkup_policy_document_no . ")</i>";
                                                        }
                                                        ?>  
                                                    </td>
                                                    <td <?php echo $assign_change; ?>><b>Assignee  </b></td>
                                                    <td <?php echo $assign_change; ?>>
                                                        <?php
                                                        echo $assign_person;
                                                        if (!empty($assign_change)) {
                                                            echo " (" . $bkup_assign_user . ")";
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td <?php echo $reference_change; ?>><b>Reference  </b></td>
                                                    <td <?php echo $reference_change; ?>>
                                                        <?php
                                                        echo $reference;
                                                        if (!empty($reference_change)) {
                                                            echo " <i>(" . $bkup_reference . ")</i>";
                                                        }
                                                        ?>  
                                                    </td>
                                                    <td <?php echo $remarkschange; ?>><b>Remarks  </b></td>
                                                    <td <?php echo $remarkschange; ?>>
                                                        <?php
                                                        echo $remarks;
                                                        if (!empty($remarkschange)) {
                                                            echo " <i>(" . $bkup_remarks . ")</i>";
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td <?php echo $status_change; ?>><b>Status  </b></td>
                                                    <td <?php echo $status_change; ?>>
                                                        <?php
                                                        if ($mngstatus == "0") {
                                                            echo "Not-completed";
                                                        } else if ($mngstatus == "-1") {
                                                            echo "Canceled";
                                                        } else {
                                                            echo "Completed";
                                                        }
                                                        if (!empty($status_change)) {
                                                            if ($bkup_mng_status == "0") {
                                                                echo " <i>(Not-completed)</i>";
                                                            } else if ($bkup_mng_status == "2") {
                                                                echo "<i>(Canceled)</i>";
                                                            } else {
                                                                echo " <i>(Completed)</i>";
                                                            }
                                                        }
                                                        ?>   
                                                    </td>
                                                    <td>&nbsp;</td><td>&nbsp;</td>
                                                </tr>
                                                <?php
                                                $countufSQL = "SELECT * FROM upload_document_files WHERE compliance_type='NSC' AND non_stat_stat_id='$mngid'";
                                                $upldFileExists = connect_db()->countEntries($countufSQL);
                                                if ($upldFileExists > 0) {
                                                    ?>
                                                    <tr>
                                                        <th colspan="4"><center>UPLOADED FILES</center></th>
                                                    </tr>
                                                    <?php
                                                    $upload_SQL = "";
                                                    $upload_SQL .= "SELECT a.id, a.document_no, a.description, b.mng_id, a.compliance_type, a.file_data, a.file_data_extension, ";
                                                    $upload_SQL .= "a.upload_by, a.upload_date FROM upload_document_files a INNER JOIN mng_cmp b ON a.non_stat_stat_id=b.id ";
                                                    $upload_SQL .= "WHERE a.compliance_type='NSC' AND a.non_stat_stat_id='$mngid' ORDER BY a.upload_date DESC";
                                                    $fetch_upload = json_decode(ret_json_str($upload_SQL));
                                                    foreach ($fetch_upload as $fetch_uploads) {
                                                        $file_id = $fetch_uploads->id;
                                                        $mng_id = $fetch_uploads->mng_id;
                                                        $uploaded_by = $fetch_uploads->upload_by;
                                                        $uploaded_date = $fetch_uploads->upload_date;
                                                        $document_no = $fetch_uploads->document_no;
                                                        $description = $fetch_uploads->description;
                                                        $non_stat_file_data = $fetch_uploads->file_data;
                                                        $non_stat_file_data_extension = $fetch_uploads->file_data_extension;
                                                        $non_stat_file_data_loc = $non_statutory_file_dir . str_replace(" ", "_", $file_id) . "." . $non_stat_file_data_extension;
                                                        $non_stat_files = fopen($non_stat_file_data_loc, "w+");
                                                        fwrite($non_stat_files, base64_decode($non_stat_file_data));
                                                        fclose($non_stat_files);
                                                        ?>
                                                        <tr>
                                                            <th>Document no.</th>
                                                            <td><?php echo $document_no; ?> &nbsp;&nbsp;
                                                                <a href="<?php echo $non_stat_file_data_loc; ?>" target="_blank">
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
                                                    <button type="button" id="approve_mng" class="btn btn-primary mr-2">
                                                        Approve</button>
                                                    <?php
                                                }
                                            }
                                            if ($mngstatus == 0) {
                                                if (($update_status != 3) || ($update_status != 4)) {
                                                    if ($assign_user == $login_user) {
                                                        ?>
                                                        <a href="mark_complete_mng.php?mng_id=<?php echo $mngid; ?>" class="btn btn-info">
                                                            MARK COMPLETE  
                                                        </a>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            <b id="mng_success_message" class="text-success"></b>
                                            <b id="mng_error_message" class="text-danger"></b>
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
                        /* *********************************** APPROVE MANAGEMENT COMPLIANCE *********************************** */

                        $('#approve_mng').click(function () {
                            var mng_id = "<?php echo $mngid; ?>";
                            var approve_mng = 2;
                            $.ajax({
                                type: "POST",
                                url: "getAPI.php?approve_mng_cmp",
                                dataType: "json",
                                data: {
                                    mng_id: mng_id,
                                    approve_mng: approve_mng
                                },
                                success: function (RetVal) {
                                    if (RetVal.message === "Success") {
                                        $("#mng_success_message").text(RetVal.data);
                                        $("#mng_error_message").text("");
                                    } else {
                                        $("#mng_success_message").text("");
                                        $("#mng_error_message").text(RetVal.data);
                                    }

                                }
                            });
                        });

                        /* *********************************** APPROVE MANAGEMENT COMPLIANCE *********************************** */

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
