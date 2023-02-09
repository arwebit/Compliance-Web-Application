<?php

include './file_includes.php';
$id = date("YmdHis");
$curr_dt = curr_date_time();
$allowed = array('csv', 'CSV');
define("MAX_SIZE", 1048576); // Size limit 1 MB ( Here size is converted to BYTES)
if (isset($_REQUEST['upload_statutory_data'])) {
    $count_stat_data = 0;
    $rmupl_delSQL = "DELETE FROM upl_risk_mng";
    connect_db()->cud($rmupl_delSQL);
    $company_id = $_POST['company_id'];
    if ($_FILES['statutory_data_upload']['name']) {
        $file_name = $_FILES['statutory_data_upload']['name'];
        $file_size = $_FILES['statutory_data_upload']['size']; // File size in "BYTES"
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (!(in_array($ext, $allowed))) {
            $stat_file_error = "Upload CSV file";
        } else {
            if ($file_size > MAX_SIZE) {
                $stat_file_error = "Upload less than or equal to 1 MB";
            } else {
                $csvFile = fopen($_FILES['statutory_data_upload']['tmp_name'], 'r');
                fgetcsv($csvFile);
                while (($line = fgetcsv($csvFile)) !== FALSE) {
                    $count_stat_data++;
                    $username[] =  mysqli_real_escape_string(connect_db()->getConnection(), trim($line[0]));
                    $department[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[1]));
                    $legislation[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[2]));
                    $description[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[3]));
                    $activity[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[4]));
                    $mode[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[5]));
                    $purpose[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[6]));
                    $purpose_descr[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[7]));
                    $due_date[] = "'" . date('Y-m-d', strtotime(mysqli_real_escape_string(connect_db()->getConnection(), trim($line[8])))) . "'";
                    $budgeted_cost[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[9])) == "" ? "0" : mysqli_real_escape_string(connect_db()->getConnection(), trim($line[9]));
                    $reference[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[10]));
                    $policy_document_no[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[11]));
                    $assign_user[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[12]));
                    $location[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[13]));
                }
            }
        }
    } else {
        $stat_file_error = "Insert file";
    }
    if ($stat_file_error == "") {
        for ($i = 0; $i < $count_stat_data; $i++) {
            $importrmSQL = "";
            $importrmSQL .= "INSERT INTO upl_risk_mng VALUES('$username[$i]','$department[$i]','$legislation[$i]', '$description[$i]', ";
            $importrmSQL .= "'$activity[$i]','$mode[$i]','$purpose[$i]','$purpose_descr[$i]',$due_date[$i], '$budgeted_cost[$i]', ";
            $importrmSQL .= "'$reference[$i]','$policy_document_no[$i]','$assign_user[$i]','$location[$i]','$company_id')";
            $importrmStatus = connect_db()->cud($importrmSQL);
            if ($importrmStatus == true) {
                $stat_success_msg = "Successfully imported statutory data. Please comfirm";
            } else {
                $stat_error_msg = "Recorrect errors";
            }
        }
    } else {
        $stat_error_msg = $stat_file_error;
    }
}
if (isset($_REQUEST['confirm_sc_data'])) {
    $rm_SQL = "SELECT * FROM upl_risk_mng";
    $fetch_rm = json_decode(ret_json_str($rm_SQL));
    foreach ($fetch_rm as $fetch_rms) {
        $id++;
       $rmcountSQL = "SELECT * FROM risk_management WHERE YEAR(create_date)='" . date("Y", strtotime($curr_dt)) . "'";
        $rmcount = (connect_db()->countEntries($rmcountSQL)) + 1;
        if ($rmcount < 1000) {
            if ($rmcount < 100) {
                if ($rmcount < 10) {
                    $rmcount = "000" . $rmcount;
                } else {
                    $rmcount = "00" . $rmcount;
                }
            } else {
                $rmcount = "0" . $rmcount;
            }
        } else {
            $rmcount = $rmcount;
        }


        $sc_id = "SC/FY" . curr_fy() . "-" . date("m", strtotime($curr_dt)) . "/" . $rmcount;
        $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->username));
        $department = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->department));
        $legislation = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->legislation));
        $activity = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->activity));
        $mode = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->mode));
        $purpose = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->purpose));
        $purpose_descr = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->purpose_descr));
        $due_date = date('Y-m-d', strtotime(mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->due_date))));
        $budgeted_cost = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->budgeted_cost));
        $reference = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->reference));
        $policy_document_no = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->policy_document_no));
        $assign_user = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->assign_user));
        $location = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->location));
        $company_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_rms->company_id));

        $deptSQL = "SELECT * FROM mas_department WHERE department='$department' AND company_id='$company_id'";
        $fetch_dept = json_decode(ret_json_str($deptSQL));
        foreach ($fetch_dept as $fetch_depts) {
            $department_id = $fetch_depts->id;
        }
        $legSQL = "";
        $legSQL .= "SELECT a.id FROM mas_legislation a INNER JOIN mas_department b ON a.department_id=b.id ";
        $legSQL .= " WHERE b.department='$department' AND a.legislation='$legislation' AND a.company_id='$company_id'";
        $fetch_leg = json_decode(ret_json_str($legSQL));
        foreach ($fetch_leg as $fetch_legs) {
            $legislation_id = $fetch_legs->id;
        }
        $activitySQL = "SELECT * FROM mas_activity WHERE activity='$activity' AND company_id='$company_id'";
        $fetch_act = json_decode(ret_json_str($activitySQL));
        foreach ($fetch_act as $fetch_acts) {
            $activity_id = $fetch_acts->id;
        }
        $modeSQL = "SELECT * FROM mas_mode WHERE mode='$mode' AND company_id='$company_id'";
        $fetch_mode = json_decode(ret_json_str($modeSQL));
        foreach ($fetch_mode as $fetch_modes) {
            $mode_id = $fetch_modes->id;
        }
        $locationSQL = "SELECT * FROM mas_location WHERE location='$location' AND company_id='$company_id'";
        $fetch_loc = json_decode(ret_json_str($locationSQL));
        foreach ($fetch_loc as $fetch_locs) {
            $location_id = $fetch_locs->id;
        }
        if ($purpose == "Others") {
            $purpose_id = "-1";
        } else {
            $purposeSQL = "SELECT * FROM mas_purpose WHERE purpose='$purpose' AND company_id='$company_id'";
            $fetch_purpose = json_decode(ret_json_str($purposeSQL));
            foreach ($fetch_purpose as $fetch_purposes) {
                $purpose_id = $fetch_purposes->id;
            }
        }
        $rmInsertSQL = "";
        $rmInsertSQL .= "INSERT INTO risk_management VALUES('$id','$sc_id','$user_name','$department_id', '$legislation_id', '$description', ";
        $rmInsertSQL .= "'$activity_id', '$mode_id', '$purpose_id', '$purpose_descr', '$due_date', null, 0, '$budgeted_cost', 0, ";
        $rmInsertSQL .= "'$reference', 0, '$policy_document_no', '$assign_user', '$remarks', '$company_id', 0, '$location_id', 2, NOW(), null)";
        $rmInsertStatus = connect_db()->cud($rmInsertSQL);
        if ($rmInsertStatus == true) {
            $rmbkupInsertSQL = "INSERT INTO rm_bkup SELECT * FROM risk_management WHERE rm_id='$sc_id'";
            connect_db()->cud($rmbkupInsertSQL);
            $rmupl_delSQL = "DELETE FROM upl_risk_mng";
            connect_db()->cud($rmupl_delSQL);
            $stat_success_msg = "Successfully inserted statutory data";
        } else {
            $stat_error_msg = "Error inserting statutory data";
        }
    }
}
if (isset($_REQUEST['upload_non_statutory_data'])) {
    $count_non_stat_data = 0;
    $mngupl_delSQL = "DELETE FROM upl_mng_cmp";
    connect_db()->cud($mngupl_delSQL);
    $company_id = $_POST['company_id'];
    if ($_FILES['non_statutory_data_upload']['name']) {
        $file_name = $_FILES['non_statutory_data_upload']['name'];
        $file_size = $_FILES['non_statutory_data_upload']['size']; // File size in "BYTES"
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (!(in_array($ext, $allowed))) {
            $non_stat_file_error = "Upload CSV file";
        } else {
            if ($file_size > MAX_SIZE) {
                $non_stat_file_error = "Upload less than or equal to 1 MB";
            } else {
                $csvFile = fopen($_FILES['non_statutory_data_upload']['tmp_name'], 'r');
                fgetcsv($csvFile);
                while (($line = fgetcsv($csvFile)) !== FALSE) {
                    $count_non_stat_data++;
                    $username[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[0]));
                    $department[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[1]));
                    $description[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[2]));
                    $due_date[] = "'" . date('Y-m-d', strtotime(mysqli_real_escape_string(connect_db()->getConnection(), trim($line[3])))) . "'";
                    $reference[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[4]));
                    $cmp_nature[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[5]));
                    $pol_doc_no[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[6]));
                    $assign_user[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[7]));
                    $remarks[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[8]));
                    $location[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[9]));
                    $budgeted_cost[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[10])) == "" ? "0" : mysqli_real_escape_string(connect_db()->getConnection(), trim($line[10]));
                }
            }
        }
    } else {
        $non_stat_file_error = "Insert file";
    }
    if ($non_stat_file_error == "") {
        for ($i = 0; $i < $count_non_stat_data; $i++) {
            $importmngSQL = "";
            $importmngSQL .= "INSERT INTO upl_mng_cmp VALUES('$username[$i]','$department[$i]', '$description[$i]', $due_date[$i], '$reference[$i]', ";
            $importmngSQL .= "'$cmp_nature[$i]', '$pol_doc_no[$i]', '$assign_user[$i]', '$remarks[$i]', '$location[$i]', '$budgeted_cost[$i]', '$company_id')";
            $importmngStatus = connect_db()->cud($importmngSQL);
            if ($importrmStatus == true) {
                $non_stat_success_msg = "Successfully imported non-statutory data. Please comfirm";
            } else {
                $stat_error_msg = "Recorrect errors";
            }
        }
    } else {
        $non_stat_error_msg = $non_stat_file_error;
    }
}
if (isset($_REQUEST['confirm_nsc_data'])) {
    $mng_SQL = "SELECT * FROM upl_mng_cmp";
    $fetch_mng = json_decode(ret_json_str($mng_SQL));
    foreach ($fetch_mng as $fetch_mngs) {
        $id++;
       $mngcountSQL = "SELECT * FROM mng_cmp WHERE YEAR(create_date)='" . date("Y", strtotime($curr_dt)) . "'";
        $mngcount = (connect_db()->countEntries($mngcountSQL)) + 1;
        if ($mngcount < 1000) {
            if ($mngcount < 100) {
                if ($mngcount < 10) {
                    $mngcount = "000" . $mngcount;
                } else {
                    $mngcount = "00" . $mngcount;
                }
            } else {
                $mngcount = "0" . $mngcount;
            }
        } else {
            $mngcount = $mngcount;
        }


        $mng_id = "NC/FY" . curr_fy() . "-" . date("m", strtotime($curr_dt)) . "/" . $mngcount;
        $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->username));
        $department = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->department));
        $description = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->description));
        $due_date = date('Y-m-d', strtotime(mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->due_date))));
        $reference = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->reference));
        $comp_nature = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->comp_nature));
        $location = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->location));
        $budgeted_cost = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->budgeted_cost));
        $policy_document_no = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->policy_document_no));
        $assign_user = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->assign_user));
        $remarks = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->remarks));
        $company_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_mngs->company_id));
        
        $deptSQL = "SELECT * FROM mas_department WHERE department='$department' AND company_id='$company_id'";
        $fetch_dept = json_decode(ret_json_str($deptSQL));
        foreach ($fetch_dept as $fetch_depts) {
            $department_id = $fetch_depts->id;
        }
        $locationSQL = "SELECT * FROM mas_location WHERE location='$location' AND company_id='$company_id'";
        $fetch_loc = json_decode(ret_json_str($locationSQL));
        foreach ($fetch_loc as $fetch_locs) {
            $location_id = $fetch_locs->id;
        }
        $mngInsertSQL = "";
        $mngInsertSQL .= "INSERT INTO mng_cmp VALUES('$id','$mng_id','$user_name','$department_id', '$description', '$due_date', null, ";
        $mngInsertSQL .= "0, '$budgeted_cost', 0, '$reference', '$comp_nature', '$policy_document_no', '$assign_user', '$remarks', ";
        $mngInsertSQL .= "'$location_id',' $company_id', 0, 2, NOW(), null)";
        $mngInsertStatus = connect_db()->cud($mngInsertSQL);
        if ($mngInsertStatus == true) {
            $mngbkupInsertSQL = "INSERT INTO mng_cmp_bkup SELECT * FROM mng_cmp WHERE mng_id='$mng_id'";
            connect_db()->cud($mngbkupInsertSQL);
            $mngupl_delSQL = "DELETE FROM upl_mng_cmp";
            connect_db()->cud($mngupl_delSQL);
            $non_stat_success_msg = "Successfully inserted non-statutory data";
        } else {
            $non_stat_error_msg = "Error inserting non-statutory data";
        }
    }
}
?>

