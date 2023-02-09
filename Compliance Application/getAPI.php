<?php

include './file_includes.php';

/* * *********************************** CHANGE USER PASSWORDS STARTS ************************************ */
if (isset($_REQUEST['change_upass'])) {
    $user = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user']));
    $new_pass = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['new_pass']));

    if (empty($new_pass)) {
        $new_passErr = "Required";
    }
    if ($new_passErr == "") {
        $passUpdateSQL = "UPDATE member_login_access SET password='" . md5($new_pass) . "' WHERE username='$user' ";
        $passUpdateStatus = connect_db()->cud($passUpdateSQL);
        if ($passUpdateStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully changed";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $passErrors = array("NewpassErr" => $new_passErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($passErrors);
    }
    echo json_encode($response);
}
/* * *********************************** CHANGE USER PASSWORDS ENDS ************************************ */
/* * *********************************** MASTER TABLES STARTS ************************************ */

/* * *********************************** INSERT COMPANY STARTS ************************************ */
if (isset($_REQUEST['add_company'])) {
    $id = date("YmdHis");
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));
    if (empty($company)) {
        $companyErr = "Required";
    } /* else {
      if (!preg_match("/^[a-zA-Z0-9]*$/", $company)) {
      $companyErr = "Only alpha-numeric and white space allowed";
      }
      } */
    if ($companyErrors == "") {
        $mas_companyInsertSQL = "INSERT INTO mas_company VALUES('$id','$company',1,'$user_name',NOW(),'$user_name',NOW())";
        $mas_companyStatus = connect_db()->cud($mas_companyInsertSQL);
        if ($mas_companyStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $companyErrors = array("CompanyErr" => $companyErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($companyErrors);
    }
    echo json_encode($response);
}
/* * *********************************** INSERT COMPANY ENDS ************************************ */
/* * *********************************** UPDATE COMPANY STARTS ************************************ */
if (isset($_REQUEST['edit_company'])) {
    $company_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company_id']));
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));
    $company_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company_status']));
    if (empty($company)) {
        $companyErr = "Required";
    } /* else {
      if (!preg_match("/^[a-zA-Z0-9]*$/", $company)) {
      $companyErr = "Only alpha-numeric and white space allowed";
      }
      } */
    if ($companyErrors == "") {
        $mas_companyEditSQL .= "UPDATE mas_company SET company_name='$company', status='$company_status', ";
        $mas_companyEditSQL .= "modify_user='$user_name', modify_date=NOW() WHERE id='$company_id'";
        $mas_companyStatus = connect_db()->cud($mas_companyEditSQL);
        if ($mas_companyStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $companyErrors = array("CompanyErr" => $companyErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($companyErrors);
    }
    echo json_encode($response);
}
/* * *********************************** UPDATE COMPANY ENDS ************************************ */
/* * *********************************** INSERT DEPARTMENT STARTS ************************************ */
if (isset($_REQUEST['add_department'])) {
    $id = date("YmdHis");
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $department = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['department']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));
    $chk_dept = str_replace(" ", "", strtoupper($department));
    if (empty($department)) {
        $departmentErr = "Required";
    } else {
        $department_existSQL = "";
        $department_existSQL .= "SELECT * FROM mas_department WHERE company_id='$company' AND ";
        $department_existSQL .= "REPLACE(TRIM(UPPER(department)),' ','')='$chk_dept'";
        $department_existCount = connect_db()->countEntries($department_existSQL);
        if ($department_existCount > 0) {
            $departmentErr = "Duplicate department";
        }
    }
    if ($departmentErr == "") {
        $mas_departmentInsertSQL = "";
        $mas_departmentInsertSQL .= "INSERT INTO mas_department VALUES('$id','$company','$department',1,'$user_name',";
        $mas_departmentInsertSQL .= "NOW(),'$user_name',NOW())";
        $mas_departmentStatus = connect_db()->cud($mas_departmentInsertSQL);
        if ($mas_departmentStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $departmentErrors = array("DepartmentErr" => $departmentErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($departmentErrors);
    }
    echo json_encode($response);
}
/* * *********************************** INSERT DEPARTMENT ENDS ************************************ */
/* * *********************************** UPDATE DEPARTMENT STARTS ************************************ */
if (isset($_REQUEST['edit_department'])) {
    $department_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['department_id']));
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $department = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['department']));
    $hdepartment = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['hdepartment']));
    $department_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['department_status']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));

    $chk_dept = str_replace(" ", "", strtoupper($department));
    $chk_hdept = str_replace(" ", "", strtoupper($hdepartment));
    if (empty($department)) {
        $departmentErr = "Required";
    } else {
        $department_existSQL = "";
        $department_existSQL .= "SELECT * FROM mas_department WHERE company_id='$company' AND ";
        $department_existSQL .= "REPLACE(TRIM(UPPER(department)),' ','')='$chk_dept' AND REPLACE(TRIM(UPPER(department)),' ','')!='$chk_hdept'";
        $department_existCount = connect_db()->countEntries($department_existSQL);
        if ($department_existCount > 0) {
            $departmentErr = "Duplicate department";
        }
    }
    if ($departmentErr == "") {
        $mas_departmentEditSQL .= "UPDATE mas_department SET department='$department', status='$department_status', ";
        $mas_departmentEditSQL .= "modify_user='$user_name', modify_date=NOW() WHERE id='$department_id'";
        $mas_departmentStatus = connect_db()->cud($mas_departmentEditSQL);
        if ($mas_departmentStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $departmentErrors = array("DepartmentErr" => $departmentErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($departmentErrors);
    }
    echo json_encode($response);
}
/* * *********************************** UPDATE DEPARTMENT ENDS ************************************ */
/* * *********************************** INSERT LEGISLATIONS STARTS ************************************ */
if (isset($_REQUEST['add_legislation'])) {
    $id = date("YmdHis");
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $legislation = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['legislation']));
    $slegislation = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['slegislation']));
    $department_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['department_id']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));
    /* $chk_leg = str_replace(" ", "", strtoupper($legislation));
      $chk_sleg = str_replace(" ", "", strtoupper($slegislation)); */

    if (empty($legislation)) {
        $legislationErr = "Required";
    } /* else {
      $legislation_existSQL = "";
      $legislation_existSQL .= "SELECT * FROM mas_legislation WHERE company_id='$company' AND ";
      $legislation_existSQL .= "REPLACE(TRIM(UPPER(legislation)),' ','')='$chk_leg'";
      $legislation_existCount = connect_db()->countEntries($legislation_existSQL);
      if ($legislation_existCount > 0) {
      $legislationErr = "Duplicate legislation";
      }
      } */
    if (empty($slegislation)) {
        $slegislationErr = "Required";
    } /* else {
      $legislation_existSQL = "";
      $legislation_existSQL .= "SELECT * FROM mas_legislation WHERE company_id='$company' AND ";
      $legislation_existSQL .= "REPLACE(TRIM(UPPER(short_legislation)),' ','')='$chk_sleg'";
      $legislation_existCount = connect_db()->countEntries($legislation_existSQL);
      if ($legislation_existCount > 0) {
      $slegislationErr = "Duplicate short legislation";
      }
      } */
    if (empty($department_id)) {
        $departmentErr = "Required";
    }
    if (($legislationErr == "") && ($departmentErr == "") && ($slegislationErr == "")) {
        $mas_legislationInsertSQL = "";
        $mas_legislationInsertSQL .= "INSERT INTO mas_legislation VALUES('$id', '$company','$department_id','$legislation',";
        $mas_legislationInsertSQL .= "'$slegislation',1,'$user_name', NOW(), '$user_name', NOW())";
        $mas_legislationStatus = connect_db()->cud($mas_legislationInsertSQL);
        if ($mas_legislationStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $legislationErrors = array("LegislationErr" => $legislationErr, "DepartmentErr" => $departmentErr, "ShortLegislationErr" => $slegislationErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($legislationErrors);
    }
    echo json_encode($response);
}
/* * *********************************** INSERT LEGISLATIONS ENDS ************************************ */
/* * *********************************** UPDATE LEGISLATIONS STARTS ************************************ */
if (isset($_REQUEST['edit_legislation'])) {
    $legislation_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['legislation_id']));
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $legislation = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['legislation']));
    $slegislation = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['slegislation']));
    $department_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['department_id']));
    $legislation_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['legislation_status']));

    if (empty($legislation)) {
        $legislationErr = "Required";
    } /* else {
      if (!preg_match("/^[a-zA-Z0-9]*$/", $legislation)) {
      $nameErr = "Only alpha-numeric and white space allowed";
      }
      } */
    if (empty($slegislation)) {
        $slegislationErr = "Required";
    }
    if (empty($department_id)) {
        $departmentErr = "Required";
    }
    if (($legislationErr == "") && ($departmentErr == "") && ($slegislationErr == "")) {
        $mas_legislationEditSQL = "";
        $mas_legislationEditSQL .= "UPDATE mas_legislation SET department_id='$department_id', legislation='$legislation', short_legislation='$slegislation', ";
        $mas_legislationEditSQL .= "status='$legislation_status', modify_user='$user_name', modify_date=NOW() WHERE id='$legislation_id'";
        $mas_legislationStatus = connect_db()->cud($mas_legislationEditSQL);
        if ($mas_legislationStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $legislationErrors = array("LegislationErr" => $legislationErr, "DepartmentErr" => $departmentErr, "ShortLegislationErr" => $slegislationErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($legislationErrors);
    }
    echo json_encode($response);
}
/* * *********************************** UPDATE LEGISLATIONS ENDS ************************************ */
/* * *********************************** INSERT LOCATION STARTS ************************************ */
if (isset($_REQUEST['add_location'])) {
    $id = date("YmdHis");
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $location = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['location']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));
    $chk_loc = str_replace(" ", "", strtoupper($location));
    if (empty($location)) {
        $locationErr = "Required";
    } else {
        $location_existSQL = "";
        $location_existSQL .= "SELECT * FROM mas_location WHERE company_id='$company' AND ";
        $location_existSQL .= "REPLACE(TRIM(UPPER(location)),' ','')='$chk_loc'";
        $location_existCount = connect_db()->countEntries($location_existSQL);
        if ($location_existCount > 0) {
            $locationErr = "Duplicate location";
        }
    }
    if ($locationErr == "") {
        $mas_locationInsertSQL = "";
        $mas_locationInsertSQL .= "INSERT INTO mas_location VALUES('$id','$company','$location',1,";
        $mas_locationInsertSQL .= "'$user_name', NOW(),'$user_name', NOW())";
        $mas_locationStatus = connect_db()->cud($mas_locationInsertSQL);
        if ($mas_locationStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $locationErrors = array("LocationErr" => $locationErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($locationErrors);
    }
    echo json_encode($response);
}
/* * *********************************** INSERT LOCATION ENDS ************************************ */
/* * *********************************** UPDATE LOCATION STARTS ************************************ */
if (isset($_REQUEST['edit_location'])) {
    $location_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['location_id']));
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $location = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['location']));
    $hlocation = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['hlocation']));
    $location_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['location_status']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));

    $chk_loc = str_replace(" ", "", strtoupper($location));
    $chk_hloc = str_replace(" ", "", strtoupper($hlocation));

    if (empty($location)) {
        $locationErr = "Required";
    } else {
        $location_existSQL = "";
        $location_existSQL .= "SELECT * FROM mas_location WHERE company_id='$company' AND ";
        $location_existSQL .= "REPLACE(TRIM(UPPER(location)),' ','')='$chk_loc' AND REPLACE(TRIM(UPPER(location)),' ','')!='$chk_hloc'";
        $location_existCount = connect_db()->countEntries($location_existSQL);
        if ($location_existCount > 0) {
            $locationErr = "Duplicate location";
        }
    }

    if ($locationErr == "") {
        $mas_locationEditSQL .= "UPDATE mas_location SET location='$location',status='$location_status', ";
        $mas_locationEditSQL .= "modify_user='$user_name', modify_date=NOW() WHERE id='$location_id'";
        $mas_locationStatus = connect_db()->cud($mas_locationEditSQL);
        if ($mas_locationStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $locationErrors = array("LocationErr" => $locationErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($locationErrors);
    }
    echo json_encode($response);
}
/* * *********************************** UPDATE LOCATION ENDS ************************************ */
/* * *********************************** INSERT PURPOSE STARTS ************************************ */
if (isset($_REQUEST['add_purpose'])) {
    $id = date("YmdHis");
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $purpose = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['purpose']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));
    $chk_purp = str_replace(" ", "", strtoupper($purpose));
    if (empty($purpose)) {
        $purposeErr = "Required";
    } else {
        $purpose_existSQL = "";
        $purpose_existSQL .= "SELECT * FROM mas_purpose WHERE company_id='$company' AND ";
        $purpose_existSQL .= "REPLACE(TRIM(UPPER(purpose)),' ','')='$chk_purp'";
        $purpose_existCount = connect_db()->countEntries($purpose_existSQL);
        if ($purpose_existCount > 0) {
            $purposeErr = "Duplicate purpose";
        }
    }
    if (($purposeErr == "") && ($legislationErr == "")) {
        $mas_purposeInsertSQL = "";
        $mas_purposeInsertSQL .= "INSERT INTO mas_purpose VALUES('$id','$company','$purpose',1,";
        $mas_purposeInsertSQL .= "'$user_name', NOW(),'$user_name', NOW())";
        $mas_purposeStatus = connect_db()->cud($mas_purposeInsertSQL);
        if ($mas_purposeStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $purposeErrors = array("PurposeErr" => $purposeErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($purposeErrors);
    }
    echo json_encode($response);
}
/* * *********************************** INSERT PURPOSE ENDS ************************************ */
/* * *********************************** UPDATE PURPOSE STARTS ************************************ */
if (isset($_REQUEST['edit_purpose'])) {
    $purpose_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['purpose_id']));
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $purpose = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['purpose']));
    $hpurpose = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['hpurpose']));
    $purpose_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['purpose_status']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));
    $chk_purp = str_replace(" ", "", strtoupper($purpose));
    $chk_hpurp = str_replace(" ", "", strtoupper($hpurpose));
    if (empty($purpose)) {
        $purposeErr = "Required";
    } else {
        $purpose_existSQL = "";
        $purpose_existSQL .= "SELECT * FROM mas_purpose WHERE company_id='$company' AND ";
        $purpose_existSQL .= "REPLACE(TRIM(UPPER(purpose)),' ','')='$chk_purp' AND REPLACE(TRIM(UPPER(purpose)),' ','')!='$chk_hpurp'";
        $purpose_existCount = connect_db()->countEntries($purpose_existSQL);
        if ($purpose_existCount > 0) {
            $purposeErr = "Duplicate purpose";
        }
    }
    if ($purposeErr == "") {
        $mas_purposeEditSQL .= "UPDATE mas_purpose SET purpose='$purpose',status='$purpose_status', ";
        $mas_purposeEditSQL .= "modify_user='$user_name', modify_date=NOW() WHERE id='$purpose_id'";
        $mas_purposeStatus = connect_db()->cud($mas_purposeEditSQL);
        if ($mas_purposeStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $purposeErrors = array("PurposeErr" => $purposeErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($purposeErrors);
    }
    echo json_encode($response);
}
/* * *********************************** UPDATE PURPOSE ENDS ************************************ */
/* * *********************************** INSERT ACTIVITY STARTS ************************************ */
if (isset($_REQUEST['add_activity'])) {
    $id = date("YmdHis");
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $activity = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['activity']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));
    $chk_actv = str_replace(" ", "", strtoupper($activity));
    if (empty($activity)) {
        $activityErr = "Required";
    } else {
        $activity_existSQL = "";
        $activity_existSQL .= "SELECT * FROM mas_activity WHERE company_id='$company' AND ";
        $activity_existSQL .= "REPLACE(TRIM(UPPER(activity)),' ','')='$chk_actv'";
        $activity_existCount = connect_db()->countEntries($activity_existSQL);
        if ($activity_existCount > 0) {
            $activityErr = "Duplicate activity";
        }
    }
    if ($activityErr == "") {
        $mas_activityInsertSQL = "";
        $mas_activityInsertSQL .= "INSERT INTO mas_activity VALUES('$id','$company','$activity',1,";
        $mas_activityInsertSQL .= "'$user_name', NOW(),'$user_name', NOW())";
        $mas_activityStatus = connect_db()->cud($mas_activityInsertSQL);
        if ($mas_activityStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $activityErrors = array("ActivityErr" => $activityErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($activityErrors);
    }
    echo json_encode($response);
}
/* * *********************************** INSERT ACTIVITY ENDS ************************************ */
/* * *********************************** UPDATE ACTIVITY STARTS ************************************ */
if (isset($_REQUEST['edit_activity'])) {
    $activity_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['activity_id']));
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $activity = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['activity']));
    $hactivity = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['hactivity']));
    $activity_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['activity_status']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));
    $chk_act = str_replace(" ", "", strtoupper($activity));
    $chk_hact = str_replace(" ", "", strtoupper($hactivity));

    if (empty($activity)) {
        $activityErr = "Required";
    } else {
        $activity_existSQL = "";
        $activity_existSQL .= "SELECT * FROM mas_activity WHERE company_id='$company' AND ";
        $activity_existSQL .= "REPLACE(TRIM(UPPER(activity)),' ','')='$chk_act' AND REPLACE(TRIM(UPPER(activity)),' ','')!='$chk_hact'";
        $activity_existCount = connect_db()->countEntries($activity_existSQL);
        if ($activity_existCount > 0) {
            $activityErr = "Duplicate activity";
        }
    }
    if ($activityErr == "") {
        $mas_activityEditSQL .= "UPDATE mas_activity SET activity='$activity', status='$activity_status', ";
        $mas_activityEditSQL .= "modify_user='$user_name', modify_date=NOW() WHERE id='$activity_id'";
        $mas_activityStatus = connect_db()->cud($mas_activityEditSQL);
        if ($mas_activityStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $activityErrors = array("ActivityErr" => $activityErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($activityErrors);
    }
    echo json_encode($response);
}
/* * *********************************** UPDATE ACTIVITY ENDS ************************************ */
/* * *********************************** INSERT MODES STARTS ************************************ */
if (isset($_REQUEST['add_mode'])) {
    $id = date("YmdHis");
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $mode = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mode']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));
    $chk_mode = str_replace(" ", "", strtoupper($mode));
    if (empty($mode)) {
        $modeErr = "Required";
    } else {
        $mode_existSQL = "";
        $mode_existSQL .= "SELECT * FROM mas_mode WHERE company_id='$company' AND ";
        $mode_existSQL .= "REPLACE(TRIM(UPPER(mode)),' ','')='$chk_mode'";
        $mode_existCount = connect_db()->countEntries($mode_existSQL);
        if ($mode_existCount > 0) {
            $modeErr = "Duplicate mode";
        }
    }
    if ($modeErr == "") {
        $mas_modeInsertSQL = "";
        $mas_modeInsertSQL .= "INSERT INTO mas_mode VALUES('$id','$company','$mode',1,";
        $mas_modeInsertSQL .= "'$user_name', NOW(),'$user_name', NOW())";
        $mas_modeStatus = connect_db()->cud($mas_modeInsertSQL);
        if ($mas_modeStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $modeErrors = array("ModeErr" => $modeErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($modeErrors);
    }
    echo json_encode($response);
}
/* * *********************************** INSERT MODES ENDS ************************************ */
/* * *********************************** UPDATE MODES STARTS ************************************ */
if (isset($_REQUEST['edit_mode'])) {
    $mode_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mode_id']));
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $mode = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mode']));
    $hmode = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['hmode']));
    $mode_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mode_status']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));
    $chk_mode = str_replace(" ", "", strtoupper($mode));
    $chk_hmode = str_replace(" ", "", strtoupper($hmode));

    if (empty($mode)) {
        $modeErr = "Required";
    } else {
        $mode_existSQL = "";
        $mode_existSQL .= "SELECT * FROM mas_mode WHERE company_id='$company' AND ";
        $mode_existSQL .= "REPLACE(TRIM(UPPER(mode)),' ','')='$chk_mode' AND REPLACE(TRIM(UPPER(mode)),' ','')!='$chk_hmode'";
        $mode_existCount = connect_db()->countEntries($mode_existSQL);
        if ($mode_existCount > 0) {
            $modeErr = "Duplicate mode";
        }
    }
    if ($modeErr == "") {
        $mas_modeEditSQL .= "UPDATE mas_mode SET mode='$mode', status='$mode_status', ";
        $mas_modeEditSQL .= "modify_user='$user_name', modify_date=NOW() WHERE id='$mode_id'";
        $mas_modeStatus = connect_db()->cud($mas_modeEditSQL);
        if ($mas_modeStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $modeErrors = array("ModeErr" => $modeErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($modeErrors);
    }
    echo json_encode($response);
}
/* * *********************************** UPDATE MODES ENDS ************************************ */

/* * *********************************** MASTER TABLES ENDS ************************************ */

/* * *********************************** FETCHING DATA FROM ANOTHER STARTS ************************************ */

/* * *********************************** GET LEGISLATIONS STARTS ************************************ */
if (isset($_REQUEST['get_legislation'])) {
    $department_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['department_id']));
    $legislation_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['legislation_id']));
    if (empty($department_id)) {
        $department_idErr = "Required";
    }
    if ($department_idErr == "") {
        $legislation_existSQL = "SELECT * FROM mas_legislation WHERE department_id='$department_id'";
        $legislation_existCount = connect_db()->countEntries($legislation_existSQL);
        if ($legislation_existCount > 0) {
            $leg_options = "";
            $leg_options .= "<option value=''>SELECT LEGISLATION</option>";
            $legislation_SQL = "SELECT * FROM mas_legislation WHERE department_id='$department_id'";
            $fetch_legislation = json_decode(ret_json_str($legislation_SQL));
            foreach ($fetch_legislation as $fetch_legislations) {
                if ($legislation_id == $fetch_legislations->id) {
                    $sel = "selected='selected'";
                } else {
                    $sel = "";
                }
                $leg_options .= "<option value='$fetch_legislations->id' $sel>";
                $leg_options .= "$fetch_legislations->legislation";
                $leg_options .= "</option>";
            }
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = $leg_options;
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $gt_lgslnErrors = array("DepartmentErr" => $department_idErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($gt_lgslnErrors);
    }
    echo json_encode($response);
}
/* * *********************************** GET LEGISLATIONS ENDS ************************************ */
/* * *********************************** FETCHING DATA FROM ANOTHER ENDS ************************************ */

/* * *********************************** MAIN FUNCTIONS STARTS ************************************ */

/* * *********************************** INSERT MEMBERS STARTS ************************************ */
if (isset($_REQUEST['add_member'])) {
    $id = date("YmdHis");
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $mem_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_name']));
    $mem_email = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_email']));
    $mem_mobile = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_mobile']));
    $mem_designation = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_designation']));
    $mem_role = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_role']));
    $mem_company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_company']));
    $mem_department = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_department']));
    $mem_location = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_location']));
    $mem_fro = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_fro']));
    $mem_sro = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_sro']));
    $mem_tro = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_tro']));
    $login_user = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['login_user']));

    if (empty($user_name)) {
        $user_nameErr = "Required";
    } else {
        $dup_usernameSQL = "SELECT * FROM member_registration WHERE user_name='$user_name'";
        $dup_usernameCount = connect_db()->countEntries($dup_usernameSQL);
        if ($dup_usernameCount > 0) {
            $user_nameErr = "Duplicate. Try again";
        } else {
            $md5_pass = md5($user_name);
        }
        /* if (!preg_match("/^[a-zA-Z0-9]*$/", $user_name)) {
          $user_nameErr = "Only alpha-numeric and white space allowed";
          } */
    }
    if (empty($mem_name)) {
        $mem_nameErr = "Required";
    }
    if (empty($mem_department)) {
        $mem_department = "0";
    }
    if (empty($mem_location)) {
        $mem_location = "0";
    }
    if ((empty($mem_fro)) && (empty($mem_sro)) && (empty($mem_tro))) {
        $ro_check = "0";
    } else {
        $ro_check = "1";
    }
    if (empty($mem_email)) {
        $mem_emailErr = "Required";
    } else {
        if (!filter_var($mem_email, FILTER_VALIDATE_EMAIL)) {
            $mem_emailErr = "Invalid email format";
        }
        /* $dup_mem_emailSQL = "SELECT * FROM member_registration WHERE email='$mem_email'";
          $dup_mem_emailCount = connect_db()->countEntries($dup_mem_emailSQL);
          if ($dup_mem_emailCount > 0) {
          $mem_emailErr = "Duplicate. Try again";
          } */
    }
    if (empty($mem_mobile)) {
        $mem_mobileErr = "Required";
    } else {
        if (strlen($mem_mobile) != 10) {
            $mem_mobileErr = "Mobile no. must be 10 digits";
        }
        if ($mem_mobile < 0) {
            $mem_mobileErr = "Cannot enter negative value";
        }
        /*  if (!preg_match("/^[0-9]*$/", $mem_mobile)) {
          $mem_mobileErr = "Only numeric and white space allowed";
          } */
        /* $dup_mem_mobSQL = "SELECT * FROM member_registration WHERE mobile='$mem_mobile'";
          $dup_mem_mobCount = connect_db()->countEntries($dup_mem_mobSQL);
          if ($dup_mem_mobCount > 0) {
          $mem_mobileErr = "Duplicate. Try again";
          } */
    }
    if (empty($mem_designation)) {
        $mem_designationErr = "Required";
    }
    if (empty($mem_company)) {
        $mem_companyErr = "Required";
    }
    if (($user_nameErr == "") && ($mem_companyErr == "") && ($mem_nameErr == "") && ($mem_emailErr == "") && ($mem_mobileErr == "") && ($mem_designationErr == "")) {
        $memInsertSQL = "";
        $memLoginInsertSQL = "INSERT INTO member_login_access VALUES('$id','$user_name','$md5_pass',1,'$mem_role')";
        $memInsertSQL .= "INSERT INTO member_registration VALUES('$id','$user_name','$mem_name','$mem_email','$mem_mobile', '$mem_location',";
        $memInsertSQL .= "'$mem_department','$mem_designation','$mem_company',1,'$login_user', NOW(), '$login_user', NOW())";
        if ($ro_check == "1") {
            $memroInsertSQL = "INSERT INTO  mem_reporting_officer VALUES('$id','$user_name','$mem_fro','$mem_sro','$mem_tro',1)";
            $memroInsertStatus = connect_db()->cud($memroInsertSQL);
        }
        $memLoginInsertStatus = connect_db()->cud($memLoginInsertSQL);
        $memInsertStatus = connect_db()->cud($memInsertSQL);
        if ((($memLoginInsertStatus == true) && ($memInsertStatus == true)) || $memroInsertStatus == true) {
            $mail_message = "";
            $mail_subject = "New user creation";
            $mail_message .= "Dear Sir/Madam<br/><br/>";
            $mail_message .= "Your risk management user has been created. Below are your credentials : <br/><br/>";
            $mail_message .= "<b>Username : $user_name</b>, <b>Password : $user_name</b><br/><br/>";
            $mail_message .= "Please change your password immediately after login<br/><br/>";
            $mail_message .= "Thank you<br/><br/>";
            $mail_message .= "Yours Sincerely<br/><br/>";
            $mail_message .= "Risk Management Team<br/><br/>";
            $mail_message .= "Email: riskmgmthelpdesk@gmail.com &nbsp;&nbsp;&nbsp;&nbsp; Ph: +91 9994344008";
            $headers = "";
            $headers .= "From: " . $contact_name . "<" . $contact_email . ">\r\n";
            $headers .= "Reply-To: " . $contact_name . "<" . $contact_email . ">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($mem_email, $mail_subject, $mail_message, $headers);
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $userErrors = array("UsernameErr" => $user_nameErr, "CompanyErr" => $mem_companyErr, "MemNameErr" => $mem_nameErr, "MemEmailErr" => $mem_emailErr, "MemMobileErr" => $mem_mobileErr, "MemDesignationErr" => $mem_designationErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($userErrors);
    }
    echo json_encode($response);
}
/* * *********************************** INSERT MEMBERS ENDS ************************************ */

/* * *********************************** UPDATE MEMBERS STARTS ************************************ */
if (isset($_REQUEST['edit_member'])) {
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $mem_email = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_email']));
    $mem_hemail = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_hemail']));
    $mem_mobile = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_mobile']));
    $mem_hmobile = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_hmobile']));
    $mem_designation = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_designation']));
    $mem_department = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_department']));
    $mem_location = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mem_location']));

    if (empty($mem_email)) {
        $mem_emailErr = "Required";
    } else {
        if (!filter_var($mem_email, FILTER_VALIDATE_EMAIL)) {
            $mem_emailErr = "Invalid email format";
        }
        /* $dup_mem_emailSQL = "SELECT * FROM member_registration WHERE email='$mem_email' AND email!='$mem_hemail'";
          $dup_mem_emailCount = connect_db()->countEntries($dup_mem_emailSQL);
          if ($dup_mem_emailCount > 0) {
          $mem_emailErr = "Duplicate. Try again";
          } */
    }
    if (empty($mem_mobile)) {
        $mem_mobileErr = "Required";
    } else {
        if (strlen($mem_mobile) != 10) {
            $mem_mobileErr = "Mobile no. must be 10 digits";
        }
        if ($mem_mobile < 0) {
            $mem_mobileErr = "Cannot enter negative value";
        }
        /*  if (!preg_match("/^[0-9]*$/", $mem_mobile)) {
          $mem_mobileErr = "Only numeric and white space allowed";
          }
          $dup_mem_mobSQL = "SELECT * FROM member_registration WHERE email='$mem_mobile' AND email!='$mem_hmobile'";
          $dup_mem_mobCount = connect_db()->countEntries($dup_mem_mobSQL);
          if ($dup_mem_mobCount > 0) {
          $mem_mobileErr = "Duplicate. Try again";
          } */
    }
    if (empty($mem_designation)) {
        $mem_designation = "---";
    }
    if (empty($mem_location)) {
        $mem_location = "0";
    }
    if (empty($mem_department)) {
        $mem_department = "0";
    }
    if (($mem_emailErr == "") && ($mem_mobileErr == "")) {
        $memUpdateSQL = "";
        $memUpdateSQL .= "UPDATE member_registration SET email='$mem_email', mobile='$mem_mobile', location='$mem_location', department='$mem_department', ";
        $memUpdateSQL .= "designation='$mem_designation', modify_user='$user_name', modify_date=NOW() WHERE user_name='$user_name' ";
        $memUpdateStatus = connect_db()->cud($memUpdateSQL);
        if ($memUpdateStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved.";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $userErrors = array("MemEmailErr" => $mem_emailErr, "MemMobileErr" => $mem_mobileErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($userErrors);
    }
    echo json_encode($response);
}
/* * *********************************** UPDATE MEMBERS ENDS ************************************ */

/* * *********************************** UPDATE EMPLOYEE ROLE STARTS ************************************ */
if (isset($_REQUEST['edit_role'])) {
    $role_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['role_id']));
    $user_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_id']));
    if (empty($role_id)) {
        $role_idErr = "Required";
    }
    if ($role_idErr == "") {
        $mas_roleEditSQL .= "UPDATE member_login_access SET role='$role_id' WHERE id='$user_id'";
        $mas_roleEditSQL = connect_db()->cud($mas_roleEditSQL);
        if ($mas_roleEditSQL == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully saved";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $roleErrors = array("RoleErr" => $role_idErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($roleErrors);
    }
    echo json_encode($response);
}
/* * *********************************** UPDATE EMPLOYEE ROLE ENDS ************************************ */
/* * *********************************** LOGIN ACCESS STARTS ************************************ */

if (isset($_REQUEST['member_login'])) {
    $username = mysqli_real_escape_string(connect_db()->getConnection(), trim($_POST['user_name']));
    $userpass = mysqli_real_escape_string(connect_db()->getConnection(), trim($_POST['user_pass']));

    if (empty($username) || empty($userpass)) {
        $loginErr = "Please provide all the fields";
    }
    if ($loginErr == "") {
        $user_existSQL = "SELECT * FROM member_login_access WHERE username='$username' AND password='" . md5($userpass) . "'";
        $user_existCount = connect_db()->countEntries($user_existSQL);
        if ($user_existCount > 0) {
            $user_activeSQL = "SELECT * FROM member_login_access WHERE username='$username' AND active_status=1";
            $user_activeCount = connect_db()->countEntries($user_activeSQL);
            if ($user_activeCount > 0) {
                if ($_SESSION['crm_member']) {
                    unset($_SESSION['crm_member']);
                }
                $_SESSION['crm_member'] = $username;
                $response['error'] = false;
                $response['message'] = "Success";
                $response['data'] = "Welcome " . $username;
                echo "<script type='text/javascript'>window.location.href='home.php';</script>";
            } else {
                $response['error'] = true;
                $response['message'] = "Login failed";
                $response['data'] = "User not active. Please contact administrator";
                echo "<script type='text/javascript'>window.location.href='login.php?user_nameErr=User not active. Please contact administrator';</script>";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Login failed";
            $response['data'] = "Wrong username or password";
            echo "<script type='text/javascript'>window.location.href='login.php?loginErr=Wrong username or password';</script>";
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Data input failed";
        $response['data'] = $loginErr;
        echo "<script type='text/javascript'>window.location.href='login.php?loginErr=$loginErr';</script>";
    }
}
/* * *********************************** LOGIN ACCESS ENDS ************************************ */

/* * *********************************** CHANGE PASSWORDS STARTS ************************************ */
if (isset($_REQUEST['change_pass'])) {
    $login_user = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['login_user']));
    $old_pass = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['old_pass']));
    $new_pass = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['new_pass']));

    if (empty($old_pass)) {
        $old_passErr = "Required";
    } else {
        $passexistSQL = "SELECT * FROM member_login_access WHERE password='" . md5($old_pass) . "' AND username='$login_user' ";
        $passexistCount = connect_db()->countEntries($passexistSQL);
        if ($passexistCount == 0) {
            $old_passErr = "Old password is wrong";
        }
    }
    if (empty($new_pass)) {
        $new_passErr = "Required";
    }
    if (($old_passErr == "") && ($new_passErr == "")) {
        $passUpdateSQL = "UPDATE member_login_access SET password='" . md5($new_pass) . "' WHERE username='$login_user' ";
        $passUpdateStatus = connect_db()->cud($passUpdateSQL);
        if ($passUpdateStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully changed";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $passErrors = array("OldpassErr" => $old_passErr, "NewpassErr" => $new_passErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($passErrors);
    }
    echo json_encode($response);
}
/* * *********************************** CHANGE PASSWORDS ENDS ************************************ */
/* * *********************************** CHANGE EMPLOYEE STATUS STARTS ************************************ */
if (isset($_REQUEST['change_emp_status'])) {
    $emp_user = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['emp_user']));
    $emp_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['emp_status']));

    if (empty($emp_user)) {
        $emp_userErr = "Required";
    }
    if ($emp_userErr == "") {
        $emp_statUpdateSQL = "UPDATE member_login_access SET active_status='$emp_status' WHERE username='$emp_user' ";
        $emp_statUpdateStatus = connect_db()->cud($emp_statUpdateSQL);
        if ($emp_statUpdateStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully changed";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $emp_statErrors = array("EmpuserErr" => $emp_userErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($emp_statErrors);
    }
    echo json_encode($response);
}
/* * *********************************** CHANGE EMPLOYEE STATUS ENDS ************************************ */
/* * *********************************** INSERT/UPDATE REPORTING OFFICER STARTS ************************************ */
if (isset($_REQUEST['reporting_officer'])) {
    $id = date("YmdHis");
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $rep_officer1 = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rep_officer1']));
    $rep_officer2 = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rep_officer2']));
    $rep_officer3 = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rep_officer3']));

    if (empty($rep_officer1)) {
        $rep_officerErr = "Required";
    }
    if ($rep_officerErr == "") {
        $roexistSQL = "SELECT * FROM mem_reporting_officer WHERE user_name='$user_name' ";
        $roexistCount = connect_db()->countEntries($roexistSQL);
        if ($roexistCount > 0) {
            $ro_offSQL = "";
            $ro_offSQL .= "UPDATE mem_reporting_officer SET first_officer='$rep_officer1', second_officer='$rep_officer2', ";
            $ro_offSQL .= "third_officer='$rep_officer3', update_status='1' WHERE user_name='$user_name'";
            $succ_msg = "Successfully saved";
        } else {
            $ro_offSQL = "";
            $ro_offSQL .= "INSERT INTO mem_reporting_officer VALUES('$id','$user_name','$rep_officer1','$rep_officer2',";
            $ro_offSQL .= "'$rep_officer3',1)";
            $succ_msg = "Successfully saved";
        }

        $roStatus = connect_db()->cud($ro_offSQL);
        if ($roStatus == true) {
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = $succ_msg;
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $roErrors = array("OffErr" => $rep_officerErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($roErrors);
    }
    echo json_encode($response);
}
/* * *********************************** INSERT/UPDATE REPORTING OFFICER ENDS ************************************ */
/* * *********************************** INSERT STATUTORY COMPLAINCE STARTS ************************************ */
if (isset($_REQUEST['add_risk_management'])) {
    $id = date("YmdHis");
    $curr_dt = curr_date_time();
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $assign_person = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['assign_person']));
    $department_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['department_id']));
    $description = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['description']));
    $mode_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mode_id']));
    $due_date = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['due_date']));
    $budgeted_cost = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['budgeted_cost']));
    $reference = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['reference']));
    $pol_doc_no = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['pol_doc_no']));
    $rm_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rm_status']));
    $legislation_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['legislation_id']));
    $activity_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['activity_id']));
    $purpose_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['purpose_id']));
    $purpose_others = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['purpose_others']));
    $location = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['location']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));

    if (empty($assign_person)) {
        $assign_personErr = "Required";
    }
    if (empty($location)) {
        $locationErr = "Required";
    }
    if (empty($department_id)) {
        $department_idErr = "Required";
    }
    if (empty($description)) {
        $descriptionErr = "Required";
    }
    if (empty($mode_id)) {
        $mode_idErr = "Required";
    }
    if (empty($due_date)) {
        $due_dateErr = "Required";
    } else {
        $due_date = date("Y-m-d", strtotime(str_replace("/", "-", $due_date)));
        /* if (date('Y-m-d', strtotime($due_date)) < date('Y-m-d', strtotime($curr_dt))) {
          $due_dateErr = "Date should not be before current date";
          } */
    }

    if (empty($budgeted_cost)) {
        $budgeted_cost = "0.00";
    }
    if (empty($reference)) {
        $referenceErr = "Required";
    }
    if (empty($pol_doc_no)) {
        $pol_doc_noErr = "Required";
    }
    if (empty($legislation_id)) {
        $legislation_idErr = "Required";
    }
    if (empty($purpose_id)) {
        $purpose_idErr = "Required";
    }
    if (empty($activity_id)) {
        $activity_idErr = "Required";
    }
    if (($locationErr == "") && ($assign_personErr == "") && ($department_idErr == "") && ($descriptionErr == "") && ($mode_idErr == "") && ($due_dateErr == "") && ($budgeted_costErr == "") && ($referenceErr == "") && ($pol_doc_noErr == "") && ($legislation_idErr == "") && ($purpose_idErr == "") && ($activity_idErr == "")) {
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
        $rmInsertSQL = "";
        $rmInsertSQL .= "INSERT INTO risk_management VALUES('$id','$sc_id','$user_name','$department_id', '$legislation_id', '$description', ";
        $rmInsertSQL .= "'$activity_id', '$mode_id', '$purpose_id', '$purpose_others', '$due_date', null, 0, '$budgeted_cost', 0, '$reference', ";
        $rmInsertSQL .= "0, '$pol_doc_no', '$assign_person', '', '$company','$rm_status', '$location', 2, NOW(), null)";
        $rmInsertStatus = connect_db()->cud($rmInsertSQL);
        if ($rmInsertStatus == true) {
            $rmbkupInsertSQL = "INSERT INTO rm_bkup SELECT * FROM risk_management WHERE rm_id='$sc_id'";
            connect_db()->cud($rmbkupInsertSQL);

             $ass_per_email_SQL = "SELECT email FROM member_registration WHERE user_name='$assign_person'";
              $fetch_ass_per_email = json_decode(ret_json_str($ass_per_email_SQL));
              foreach ($fetch_ass_per_email as $fetch_ass_per_emails) {
              $ass_per_email = $fetch_ass_per_emails->email;
              }
              $user_email_SQL = "SELECT email FROM member_registration WHERE user_name='$user_name'";
              $fetch_user_email = json_decode(ret_json_str($user_email_SQL));
              foreach ($fetch_user_email as $fetch_user_emails) {
              $user_email = $fetch_user_emails->email;
              }
              if ((!empty($ass_per_email)) || (!empty($user_email))) {
              $mail_message = "";
              $mail_subject = "New statutory compliance entry";
              $mail_message .= "Dear Sir/Madam<br/><br/>";
              $mail_message .= "A statutory compliance <b>$sc_id</b> has been registered. The due date of this compliance is : <b>";
              $mail_message .= date("d-m-Y", strtotime($due_date)) . "</b>. Kindly take necessary action for same<br/><br/>";
              $mail_message .= "Thank you<br/><br/>";
              $mail_message .= "Yours Sincerely<br/><br/>";
              $mail_message .= "Risk Management Team<br/><br/>";
              $mail_message .= "Email: riskmgmthelpdesk@gmail.com &nbsp;&nbsp;&nbsp;&nbsp; Ph: +91 9994344008";
              $headers = "";
              $headers .= "From: " . $contact_name . "<" . $contact_email . ">\r\n";
              $headers .= "Reply-To: " . $contact_name . "<" . $contact_email . ">\r\n";
              $headers .= "MIME-Version: 1.0\r\n";
              $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
              mail($ass_per_email, $mail_subject, $mail_message, $headers);
              mail($user_email, $mail_subject, $mail_message, $headers);
              } 
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully created";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $rmErrors = array("LocationErr" => $locationErr, "AssignPersonErr" => $assign_personErr, "DepartmentErr" => $department_idErr, "DescriptionErr" => $descriptionErr, "ModeErr" => $mode_idErr, "Due_dateErr" => $due_dateErr, "Budgeted_costErr" => $budgeted_costErr, "ReferenceErr" => $referenceErr, "Pol_doc_noErr" => $pol_doc_noErr, "LegislationErr" => $legislation_idErr, "ActivityErr" => $activity_idErr, "Purpose_valueErr" => $purpose_valueErr, "PurposeErr" => $purpose_idErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($rmErrors);
    }
    echo json_encode($response);
}
/* * *********************************** INSERT STATUTORY COMPLAINCE ENDS ************************************ */

/* * *********************************** UPDATE STATUTORY COMPLAINCE STARTS ************************************ */
if (isset($_REQUEST['edit_risk_management'])) {
    $rm_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rm_id']));
    $curr_dt = curr_date_time();
    $assign_person = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['assign_person']));
    $department_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['department_id']));
    $description = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['description']));
    $mode_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mode_id']));
    $due_date = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['due_date']));
    $budgeted_cost = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['budgeted_cost']));
    $reference = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['reference']));
    $pol_doc_no = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['pol_doc_no']));
    $rm_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rm_status']));
    $legislation_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['legislation_id']));
    $activity_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['activity_id']));
    $purpose_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['purpose_id']));
    $purpose_others = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['purpose_others']));
    $update_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['update_status']));
    $location = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['location']));

    if (empty($assign_person)) {
        $assign_personErr = "Required";
    }
    if (empty($location)) {
        $locationErr = "Required";
    }
    if (empty($department_id)) {
        $department_idErr = "Required";
    }
    if (empty($description)) {
        $descriptionErr = "Required";
    }
    if (empty($mode_id)) {
        $mode_idErr = "Required";
    }
    if (empty($due_date)) {
        $due_dateErr = "Required";
    } else {
        $due_date = date("Y-m-d", strtotime(str_replace("/", "-", $due_date)));
        /* if (date('Y-m-d', strtotime($due_date)) < date('Y-m-d', strtotime($curr_dt))) {
          $due_dateErr = "Date should not be before current date";
          } */
    }
    if (empty($budgeted_cost)) {
        $budgeted_cost = "0.00";
    }
    if (empty($reference)) {
        $referenceErr = "Required";
    }
    if (empty($pol_doc_no)) {
        $pol_doc_noErr = "Required";
    }
    if (empty($legislation_id)) {
        $legislation_idErr = "Required";
    }
    if (empty($purpose_id)) {
        $purpose_idErr = "Required";
    }
    if (empty($activity_id)) {
        $activity_idErr = "Required";
    }
    if (($locationErr == "") && ($assign_personErr == "") && ($department_idErr == "") && ($descriptionErr == "") && ($mode_idErr == "") && ($due_dateErr == "") && ($budgeted_costErr == "") && ($referenceErr == "") && ($pol_doc_noErr == "") && ($legislation_idErr == "") && ($purpose_idErr == "") && ($activity_idErr == "")) {
        $rmUpdateSQL = "";
        $rmUpdateSQL .= "UPDATE risk_management SET department_id='$department_id', legislation_id='$legislation_id', description='$description', ";
        $rmUpdateSQL .= "activity_id='$activity_id', mode_id='$mode_id', purpose_id='$purpose_id', purpose_descr='$purpose_others', ";
        $rmUpdateSQL .= "due_date='$due_date', budgeted_cost='$budgeted_cost', reference='$reference', policy_document_no='$pol_doc_no', location_id='$location', status='$rm_status', ";
        $rmUpdateSQL .= "modify_date=NOW(), assign_user ='$assign_person', update_status='$update_status' WHERE id='$rm_id'";
        $rmUpdateStatus = connect_db()->cud($rmUpdateSQL);
        if ($rmUpdateStatus == true) {
            if ($update_status == 2) {
                $rmbkup_delSQL = "DELETE FROM rm_bkup WHERE id='$rm_id'";
                $rmbkup_delStatus = connect_db()->cud($rmbkup_delSQL);
                if ($rmbkup_delStatus == true) {
                    $rmbkupInsertSQL = "INSERT INTO rm_bkup SELECT * FROM risk_management WHERE id='$rm_id'";
                    connect_db()->cud($rmbkupInsertSQL);
                }
                $response['error'] = false;
                $response['message'] = "Success";
                $response['data'] = "Successfully saved";
            } else {
                $response['error'] = false;
                $response['message'] = "Success";
                $response['data'] = "Successfully saved. Waiting for approval";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $rmErrors = array("LocationErr" => $locationErr, "AssignPersonErr" => $assign_personErr, "RemarksErr" => $remarksErr, "RenewDateErr" => $renew_dateErr, "DepartmentErr" => $department_idErr, "DescriptionErr" => $descriptionErr, "ModeErr" => $mode_idErr, "Due_dateErr" => $due_dateErr, "Budgeted_costErr" => $budgeted_costErr, "ReferenceErr" => $referenceErr, "Pol_doc_noErr" => $pol_doc_noErr, "LegislationErr" => $legislation_idErr, "ActivityErr" => $activity_idErr, "Purpose_valueErr" => $purpose_valueErr, "PurposeErr" => $purpose_idErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($rmErrors);
    }
    echo json_encode($response);
}
/* * *********************************** UPDATE STATUTORY COMPLAINCE ENDS ************************************ */
/* * *********************************** APPROVE STATUTORY COMPLAINCE STARTS ************************************ */
if (isset($_REQUEST['approve_risk_management'])) {
    $rm_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rm_id']));
    $update_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['approve_rm']));
    $rmApproveSQL = "UPDATE risk_management SET update_status='$update_status' WHERE id='$rm_id'";
    $rmApproveStatus = connect_db()->cud($rmApproveSQL);
    if ($rmApproveStatus == true) {
        $rmbkup_delSQL = "DELETE FROM rm_bkup WHERE id='$rm_id'";
        $rmbkup_delStatus = connect_db()->cud($rmbkup_delSQL);
        if ($rmbkup_delStatus == true) {
            $rmbkupInsertSQL = "INSERT INTO rm_bkup SELECT * FROM risk_management WHERE id='$rm_id'";
            connect_db()->cud($rmbkupInsertSQL);
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully approved";
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Server error";
        $response['data'] = "Server error. Please try again later";
    }

    echo json_encode($response);
}
/* * *********************************** APPROVE STATUTORY COMPLAINCE ENDS ************************************ */

/* * *********************************** MARK COMPLETE STATUTORY COMPLAINCE STARTS ************************************ */
if (isset($_REQUEST['mark_complete_rm'])) {
    $rm_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rm_id']));
    $curr_dt = curr_date_time();
    $renew_date = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['renew_date']));
    $purpose_value = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['purpose_value']));
    $actual_cost = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['actual_cost']));
    $actual_value_covered = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['actual_value_covered']));
    $remarks = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['remarks']));
    $rm_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rm_status']));
    $update_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['update_status']));

    if (empty($remarks)) {
        $remarksErr = "Required";
    }
    if (empty($renew_date)) {
        $renew_dateErr = "Required";
    } else {
        $renew_date = date("Y-m-d", strtotime(str_replace("/", "-", $renew_date)));
        /* if (date('Y-m-d', strtotime($renew_date)) > date('Y-m-d', strtotime($curr_dt))) {
          $renew_dateErr = "Task complete date should not exceed current date";
          } */
    }
    if (($renew_dateErr == "") && ($remarksErr == "")) {
        $mark_cmplt_rmSQL .= "UPDATE risk_management SET status='$rm_status', renewed_date='$renew_date', update_status='$update_status', ";
        $mark_cmplt_rmSQL .= "remarks= '$remarks', actual_value_covered='$actual_value_covered',actual_cost='$actual_cost', ";
        $mark_cmplt_rmSQL .= "modify_date=NOW() WHERE id='$rm_id'";
        $mark_cmplt_rmStatus = connect_db()->cud($mark_cmplt_rmSQL);
        if ($mark_cmplt_rmStatus == true) {
            $rmbkup_delSQL = "DELETE FROM rm_bkup WHERE id='$rm_id'";
            $rmbkup_delStatus = connect_db()->cud($rmbkup_delSQL);
            if ($rmbkup_delStatus == true) {
                $rmbkupInsertSQL = "INSERT INTO rm_bkup SELECT * FROM risk_management WHERE id='$rm_id'";
                connect_db()->cud($rmbkupInsertSQL);

                $ass_per_email_SQL = "";
                $ass_per_email_SQL .= "SELECT a.rm_id, b.email user_email, c.email assiger_email FROM risk_management a INNER JOIN member_registration b ";
                $ass_per_email_SQL .= "ON a.username=b.user_name INNER JOIN member_registration c ON a.assign_user=c.user_name WHERE a.id='$rm_id' ";
                $fetch_ass_per_email = json_decode(ret_json_str($ass_per_email_SQL));
                foreach ($fetch_ass_per_email as $fetch_ass_per_emails) {
                    $ass_per_email = $fetch_ass_per_emails->assiger_email;
                    $user_email = $fetch_ass_per_emails->user_email;
                    $rm_cmid = $fetch_ass_per_emails->rm_id;
                }

                if ((!empty($ass_per_email)) || (!empty($user_email))) {
                    $mail_message = "";
                    $mail_subject = "Completion of statutory compliance";
                    $mail_message .= "Dear Sir/Madam<br/><br/>";
                    $mail_message .= "A statutory compliance no. :<b> $rm_cmid</b> has been marked as complete.";
                    $mail_message .= "Kindly take necessary action for same<br/><br/>";
                    $mail_message .= "Thank you<br/><br/>";
                    $mail_message .= "Yours Sincerely<br/><br/>";
                    $mail_message .= "Risk Management Team<br/><br/>";
                    $mail_message .= "Email: riskmgmthelpdesk@gmail.com &nbsp;&nbsp;&nbsp;&nbsp; Ph: +91 9994344008";
                    $headers = "";
                    $headers .= "From: " . $contact_name . "<" . $contact_email . ">\r\n";
                    $headers .= "Reply-To: " . $contact_name . "<" . $contact_email . ">\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $s1 = mail($ass_per_email, $mail_subject, $mail_message, $headers);
                    $s3 = mail($user_email, $mail_subject, $mail_message, $headers);
                }
                $response['error'] = false;
                $response['message'] = "Success";
                $response['data'] = "Successfully marked as complete";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $markErrors = array("RenewDateErr" => $renew_dateErr, "RemarksErr" => $remarksErr);
        $response['error'] = true;
        $response['message'] = "Provide required fields";
        $response['data'] = json_encode($markErrors);
    }
    echo json_encode($response);
}
/* * *********************************** MARK COMPLETE STATUTORY COMPLAINCE ENDS ************************************ */
/* * *********************************** CANCEL STATUTORY COMPLAINCE STARTS ************************************ */
if (isset($_REQUEST['mark_cancel_rm'])) {
    $rm_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rm_id']));
    $curr_dt = curr_date_time();
    $remarks = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['remarks']));
    $rm_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rm_status']));
    $update_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['update_status']));

    if (empty($remarks)) {
        $remarksErr = "Required";
    }
    if ($remarksErr == "") {
        $mark_cancel_rmSQL = "";
        $mark_cancel_rmSQL .= "UPDATE risk_management SET status='$rm_status', remarks='$remarks', ";
        $mark_cancel_rmSQL .= "update_status='$update_status', modify_date=NOW() WHERE id='$rm_id'";
        $mark_cancel_rmStatus = connect_db()->cud($mark_cancel_rmSQL);
        if ($mark_cancel_rmStatus == true) {
            $rmbkup_delSQL = "DELETE FROM rm_bkup WHERE id='$rm_id'";
            $rmbkup_delStatus = connect_db()->cud($rmbkup_delSQL);
            if ($rmbkup_delStatus == true) {
                $rmbkupInsertSQL = "INSERT INTO rm_bkup SELECT * FROM risk_management WHERE id='$rm_id'";
                connect_db()->cud($rmbkupInsertSQL);

                $ass_per_email_SQL = "";
                $ass_per_email_SQL .= "SELECT a.rm_id, a.modify_date, b.email user_email, c.email assiger_email FROM risk_management a INNER JOIN member_registration b ";
                $ass_per_email_SQL .= "ON a.username=b.user_name INNER JOIN member_registration c ON a.assign_user=c.user_name WHERE a.id='$rm_id' ";
                $fetch_ass_per_email = json_decode(ret_json_str($ass_per_email_SQL));
                foreach ($fetch_ass_per_email as $fetch_ass_per_emails) {
                    $ass_per_email = $fetch_ass_per_emails->assiger_email;
                    $user_email = $fetch_ass_per_emails->user_email;
                    $canceled_date = $fetch_ass_per_emails->modify_date;
                    $rm_cnid = $fetch_ass_per_emails->rm_id;
                }

                if ((!empty($ass_per_email)) || (!empty($user_email))) {
                    $mail_message = "";
                    $mail_subject = "Cancelation of statutory compliance";
                    $mail_message .= "Dear Sir/Madam<br/><br/>";
                    $mail_message .= "This is to inform you that the statutory compliance no. :<b> $rm_cnid</b> has been cancelled by ";
                    $mail_message .= "<b>$username</b> on <b>" . date('d/m/Y', strtotime($canceled_date)) . "</b><br/><br/>";
                    $mail_message .= "Thank you<br/><br/>";
                    $mail_message .= "Yours Sincerely<br/><br/>";
                    $mail_message .= "Risk Management Team<br/><br/>";
                    $mail_message .= "Email: riskmgmthelpdesk@gmail.com &nbsp;&nbsp;&nbsp;&nbsp; Ph: +91 9994344008";
                    $headers = "";
                    $headers .= "From: " . $contact_name . "<" . $contact_email . ">\r\n";
                    $headers .= "Reply-To: " . $contact_name . "<" . $contact_email . ">\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    mail($ass_per_email, $mail_subject, $mail_message, $headers);
                    mail($user_email, $mail_subject, $mail_message, $headers);
                }

                $response['error'] = false;
                $response['message'] = "Success";
                $response['data'] = "Successfully canceled";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $cancelErrors = array("RemarksErr" => $remarksErr);
        $response['error'] = true;
        $response['message'] = "Provide required fields";
        $response['data'] = json_encode($cancelErrors);
    }
    echo json_encode($response);
}
/* * *********************************** CANCEL STATUTORY COMPLAINCE ENDS ************************************ */
/* * *********************************** INSERT NON-STATUTORY COMPLIANCE STARTS ************************************ */
if (isset($_REQUEST['add_management_comp'])) {
    $id = date("YmdHis");
    $curr_dt = curr_date_time();
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $assign_person = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['assign_person']));
    $department_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['department_id']));
    $description = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['description']));
    $budgeted_cost = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['budgeted_cost']));
    $cmp_nature = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['cmp_nature']));
    $due_date = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['due_date']));
    $remarks = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['remarks']));
    $reference = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['reference']));
    $pol_doc_no = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['pol_doc_no']));
    $rm_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rm_status']));
    $company = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['company']));
    $location = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['location']));

    if (empty($assign_person)) {
        $assign_personErr = "Required";
    }
    if (empty($location)) {
        $locationErr = "Required";
    }
    if (empty($department_id)) {
        $department_idErr = "Required";
    }
    if (empty($description)) {
        $descriptionErr = "Required";
    }
    if (empty($cmp_nature)) {
        $cmp_natureErr = "Required";
    }
    if (empty($due_date)) {
        $due_dateErr = "Required";
    } else {
        $due_date = date("Y-m-d", strtotime(str_replace("/", "-", $due_date)));
        /* if (date('Y-m-d', strtotime($due_date)) < date('Y-m-d', strtotime($curr_dt))) {
          $due_dateErr = "Date should not be before current date";
          } */
    }

    if (empty($remarks)) {
        $remarksErr = "Required";
    }
    if (empty($pol_doc_no)) {
        $pol_doc_noErr = "Required";
    }

    if (($locationErr == "") && ($cmp_natureErr == "") && ($assign_personErr == "") && ($department_idErr == "") && ($descriptionErr == "") && ($due_dateErr == "") && ($remarksErr == "") && ($pol_doc_noErr == "")) {
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
        $mngInsertSQL = "";
        $mngInsertSQL .= "INSERT INTO mng_cmp VALUES('$id','$mng_id','$user_name','$department_id', '$description', ";
        $mngInsertSQL .= "'$due_date', null, 0, '$budgeted_cost', 0, '$reference', '$cmp_nature', '$pol_doc_no', ";
        $mngInsertSQL .= "'$assign_person', '$remarks', '$location','$company','$rm_status', 2, NOW(), null)";
        $mngInsertStatus = connect_db()->cud($mngInsertSQL);
        if ($mngInsertStatus == true) {
            $mngbkupInsertSQL = "INSERT INTO mng_cmp_bkup SELECT * FROM mng_cmp WHERE mng_id='$mng_id'";
            connect_db()->cud($mngbkupInsertSQL);

            $ass_per_email_SQL = "SELECT email FROM member_registration WHERE user_name='$assign_person'";
            $fetch_ass_per_email = json_decode(ret_json_str($ass_per_email_SQL));
            foreach ($fetch_ass_per_email as $fetch_ass_per_emails) {
                $ass_per_email = $fetch_ass_per_emails->email;
            }
            $user_email_SQL = "SELECT email FROM member_registration WHERE user_name='$user_name'";
            $fetch_user_email = json_decode(ret_json_str($user_email_SQL));
            foreach ($fetch_user_email as $fetch_user_emails) {
                $user_email = $fetch_user_emails->email;
            }
            if ((!empty($ass_per_email)) || (!empty($user_email))) {
                $mail_message = "";
                $mail_subject = "New non-statutory compliance entry";
                $mail_message .= "Dear Sir/Madam<br/><br/>";
                $mail_message .= "A non-statutory compliance <b>$mng_id</b> has been registered. The due date of this compliance is : <b>";
                $mail_message .= date("d-m-Y", strtotime($due_date)) . "</b>. Kindly take necessary action for same<br/><br/>";
                $mail_message .= "Thank you<br/><br/>";
                $mail_message .= "Yours Sincerely<br/><br/>";
                $mail_message .= "Risk Management Team<br/><br/>";
                $mail_message .= "Email: riskmgmthelpdesk@gmail.com &nbsp;&nbsp;&nbsp;&nbsp; Ph: +91 9994344008";
                $headers = "";
                $headers .= "From: " . $contact_name . "<" . $contact_email . ">\r\n";
                $headers .= "Reply-To: " . $contact_name . "<" . $contact_email . ">\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                mail($ass_per_email, $mail_subject, $mail_message, $headers);
                mail($user_email, $mail_subject, $mail_message, $headers);
            }
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully created";
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $mngErrors = array("LocationErr" => $locationErr, "AssignPersonErr" => $assign_personErr, "DepartmentErr" => $department_idErr, "Comp_natureErr" => $comp_natureErr, "DescriptionErr" => $descriptionErr, "Due_dateErr" => $due_dateErr, "RemarksErr" => $remarksErr, "Pol_doc_noErr" => $pol_doc_noErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($mngErrors);
    }
    echo json_encode($response);
}
/* * *********************************** INSERT NON-STATUTORY COMPLIANCE ENDS ************************************ */
/* * *********************************** UPDATE NON-STATUTORY COMPLIANCE STARTS ************************************ */
if (isset($_REQUEST['edit_management_comp'])) {
    $mng_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mng_id']));
    $curr_dt = curr_date_time();
    $user_name = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['user_name']));
    $assign_person = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['assign_person']));
    $department_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['department_id']));
    $description = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['description']));
    $budgeted_cost = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['budgeted_cost']));
    $cmp_nature = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['cmp_nature']));
    $due_date = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['due_date']));
    $remarks = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['remarks']));
    $reference = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['reference']));
    $pol_doc_no = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['pol_doc_no']));
    $rm_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rm_status']));
    $update_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['update_status']));
    $location = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['location']));

    if (empty($assign_person)) {
        $assign_personErr = "Required";
    }
    if (empty($location)) {
        $locationErr = "Required";
    }
    if (empty($department_id)) {
        $department_idErr = "Required";
    }
    if (empty($description)) {
        $descriptionErr = "Required";
    }
    if (empty($cmp_nature)) {
        $cmp_natureErr = "Required";
    }
    if (empty($due_date)) {
        $due_dateErr = "Required";
    } else {
        $due_date = date("Y-m-d", strtotime(str_replace("/", "-", $due_date)));
        /* if (date('Y-m-d', strtotime($due_date)) < date('Y-m-d', strtotime($curr_dt))) {
          $due_dateErr = "Date should not be before current date";
          } */
    }

    if (empty($remarks)) {
        $remarksErr = "Required";
    }
    if (empty($pol_doc_no)) {
        $pol_doc_noErr = "Required";
    }

    if (($locationErr == "") && ($cmp_natureErr == "") && ($assign_personErr == "") && ($department_idErr == "") && ($descriptionErr == "") && ($due_dateErr == "") && ($remarksErr == "") && ($pol_doc_noErr == "")) {
        $mngUpdateSQL = "";
        $mngUpdateSQL .= "UPDATE mng_cmp SET department_id='$department_id', comp_nature='$cmp_nature', description='$description', ";
        $mngUpdateSQL .= "due_date='$due_date', reference='$reference', policy_document_no='$pol_doc_no', remarks='$remarks', ";
        $mngUpdateSQL .= "status='$rm_status', assign_user ='$assign_person', budgeted_cost='$budgeted_cost', location_id='$location', ";
        $mngUpdateSQL .= "modify_date=NOW(), update_status='$update_status' WHERE id='$mng_id'";
        $mngUpdateStatus = connect_db()->cud($mngUpdateSQL);
        if ($mngUpdateStatus == true) {
            if ($update_status == 2) {
                $mngbkup_delSQL = "DELETE FROM mng_cmp_bkup WHERE id='$mng_id'";
                $mngbkup_delStatus = connect_db()->cud($mngbkup_delSQL);
                if ($mngbkup_delStatus == true) {
                    $mngbkupInsertSQL = "INSERT INTO mng_cmp_bkup SELECT * FROM mng_cmp WHERE id='$mng_id'";
                    connect_db()->cud($mngbkupInsertSQL);
                }
                $response['error'] = false;
                $response['message'] = "Success";
                $response['data'] = "Successfully saved";
            } else {
                $response['error'] = false;
                $response['message'] = "Success";
                $response['data'] = "Successfully saved. Waiting for approval";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $mngErrors = array("LocationErr" => $locationErr, "AssignPersonErr" => $assign_personErr, "DepartmentErr" => $department_idErr, "Comp_natureErr" => $comp_natureErr, "DescriptionErr" => $descriptionErr, "Due_dateErr" => $due_dateErr, "RemarksErr" => $remarksErr, "Pol_doc_noErr" => $pol_doc_noErr);
        $response['error'] = true;
        $response['message'] = "Something went wrong";
        $response['data'] = json_encode($mngErrors);
    }
    echo json_encode($response);
}
/* * *********************************** UPDATE NON-STATUTORY COMPLIANCE ENDS ************************************ */
/* * *********************************** APPROVE NON-STATUTORY COMPLIANCE STARTS ************************************ */
if (isset($_REQUEST['approve_mng_cmp'])) {
    $mng_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mng_id']));
    $update_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['approve_mng']));
    $mngApproveSQL = "UPDATE mng_cmp SET update_status='$update_status' WHERE id='$mng_id'";
    $mngApproveStatus = connect_db()->cud($mngApproveSQL);
    if ($mngApproveStatus == true) {
        $mngbkup_delSQL = "DELETE FROM mng_cmp_bkup WHERE id='$mng_id'";
        $mngbkup_delStatus = connect_db()->cud($mngbkup_delSQL);
        if ($mngbkup_delStatus == true) {
            $mngbkupInsertSQL = "INSERT INTO mng_cmp_bkup SELECT * FROM mng_cmp WHERE id='$mng_id'";
            connect_db()->cud($mngbkupInsertSQL);
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = "Successfully approved";
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Server error";
        $response['data'] = "Server error. Please try again later";
    }

    echo json_encode($response);
}
/* * *********************************** APPROVE NON-STATUTORY COMPLIANCE ENDS ************************************ */
/* * *********************************** MARK COMPLETE NON-STATUTORY COMPLIANCE STARTS ************************************ */
if (isset($_REQUEST['mark_complete_mng'])) {
    $mng_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mng_id']));
    $curr_dt = curr_date_time();
    $renew_date = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['renew_date']));
    $actual_cost = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['actual_cost']));
    $remarks = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['remarks']));
    $reference = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['reference']));
    $mng_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mng_status']));
    $update_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['update_status']));

    if (empty($remarks)) {
        $remarksErr = "Required";
    }
    if (empty($renew_date)) {
        $renew_dateErr = "Required";
    } else {
        $renew_date = date("Y-m-d", strtotime(str_replace("/", "-", $renew_date)));
        /* if (date('Y-m-d', strtotime($renew_date)) > date('Y-m-d', strtotime($curr_dt))) {
          $renew_dateErr = "Task complete date should not exceed current date";
          } */
    }
    if (($renew_dateErr == "") && ($remarksErr == "")) {
        $mark_cmplt_mngSQL .= "UPDATE mng_cmp SET status='$mng_status', renewed_date='$renew_date', update_status='$update_status', ";
        $mark_cmplt_mngSQL .= "reference='$reference', remarks='$remarks', actual_transaction_value='$actual_cost', modify_date=NOW() WHERE id='$mng_id'";
        $mark_cmplt_mngStatus = connect_db()->cud($mark_cmplt_mngSQL);
        if ($mark_cmplt_mngStatus == true) {
            $mngbkup_delSQL = "DELETE FROM mng_cmp_bkup WHERE id='$mng_id'";
            $mngbkup_delStatus = connect_db()->cud($mngbkup_delSQL);
            if ($mngbkup_delStatus == true) {
                $mngbkupInsertSQL = "INSERT INTO mng_cmp_bkup SELECT * FROM mng_cmp WHERE id='$mng_id'";
                connect_db()->cud($mngbkupInsertSQL);

                $ass_per_email_SQL = "";
                $ass_per_email_SQL .= "SELECT a.mng_id, b.email user_email, c.email assiger_email FROM mng_cmp a INNER JOIN member_registration b ";
                $ass_per_email_SQL .= "ON a.username=b.user_name INNER JOIN member_registration c ON a.assign_user=c.user_name WHERE a.id='$mng_id' ";
                $fetch_ass_per_email = json_decode(ret_json_str($ass_per_email_SQL));
                foreach ($fetch_ass_per_email as $fetch_ass_per_emails) {
                    $ass_per_email = $fetch_ass_per_emails->assiger_email;
                    $user_email = $fetch_ass_per_emails->user_email;
                    $mng_cmid = $fetch_ass_per_emails->mng_id;
                }

                if ((!empty($ass_per_email)) || (!empty($user_email))) {

                    $mail_message = "";
                    $mail_subject = "Completion of non-statutory compliance";
                    $mail_message .= "Dear Sir/Madam<br/><br/>";
                    $mail_message .= "A non-statutory complaince no. : <b>$mng_cmid</b> has been marked as complete.";
                    $mail_message .= "Kindly take necessary action for same<br/><br/>";
                    $mail_message .= "Thank you<br/><br/>";
                    $mail_message .= "Yours Sincerely<br/><br/>";
                    $mail_message .= "Risk Management Team<br/><br/>";
                    $mail_message .= "Email: riskmgmthelpdesk@gmail.com &nbsp;&nbsp;&nbsp;&nbsp; Ph: +91 9994344008";
                    $headers = "";
                    $headers .= "From: " . $contact_name . "<" . $contact_email . ">\r\n";
                    $headers .= "Reply-To: " . $contact_name . "<" . $contact_email . ">\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    mail($ass_per_email, $mail_subject, $mail_message, $headers);
                }
                $response['error'] = false;
                $response['message'] = "Success";
                $response['data'] = "Successfully marked as complete";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $markErrors = array("RenewDateErr" => $renew_dateErr, "RemarksErr" => $remarksErr);
        $response['error'] = true;
        $response['message'] = "Provide required fields";
        $response['data'] = json_encode($markErrors);
    }
    echo json_encode($response);
}
/* * *********************************** MARK COMPLETE NON-STATUTORY COMPLIANCE ENDS ************************************ */
/* * *********************************** CANCEL NON-STATUTORY COMPLAINCE STARTS ************************************ */
if (isset($_REQUEST['mark_cancel_mng'])) {
    $mng_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['mng_id']));
    $curr_dt = curr_date_time();
    $remarks = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['remarks']));
    $rm_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['rm_status']));
    $update_status = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['update_status']));

    if (empty($remarks)) {
        $remarksErr = "Required";
    }
    if ($remarksErr == "") {
        $mark_cancel_mngSQL = "";
        $mark_cancel_mngSQL .= "UPDATE mng_cmp SET status='$rm_status', remarks='$remarks', ";
        $mark_cancel_mngSQL .= "update_status='$update_status', modify_date=NOW() WHERE id='$mng_id'";
        $mark_cancel_mngStatus = connect_db()->cud($mark_cancel_mngSQL);
        if ($mark_cancel_mngStatus == true) {
            $mngbkup_delSQL = "DELETE FROM mng_cmp_bkup WHERE id='$mng_id'";
            $mngbkup_delStatus = connect_db()->cud($mngbkup_delSQL);
            if ($mngbkup_delStatus == true) {
                $mngbkupInsertSQL = "INSERT INTO mng_cmp_bkup SELECT * FROM mng_cmp WHERE id='$mng_id'";
                connect_db()->cud($mngbkupInsertSQL);
                $ass_per_email_SQL = "";
                $ass_per_email_SQL .= "SELECT a.mng_id, a.modify_date, b.email user_email, c.email assiger_email FROM mng_cmp a INNER JOIN member_registration b ";
                $ass_per_email_SQL .= "ON a.username=b.user_name INNER JOIN member_registration c ON a.assign_user=c.user_name WHERE a.id='$mng_id' ";
                $fetch_ass_per_email = json_decode(ret_json_str($ass_per_email_SQL));
                foreach ($fetch_ass_per_email as $fetch_ass_per_emails) {
                    $ass_per_email = $fetch_ass_per_emails->assiger_email;
                    $user_email = $fetch_ass_per_emails->user_email;
                    $canceled_date = $fetch_ass_per_emails->modify_date;
                    $mng_cnid = $fetch_ass_per_emails->mng_id;
                }

                if ((!empty($ass_per_email)) || (!empty($user_email))) {
                    $mail_message = "";
                    $mail_subject = "Cancelation of non-statutory compliance";
                    $mail_message .= "Dear Sir/Madam<br/><br/>";
                    $mail_message .= "This is to inform you that the non-statutory compliance no. :<b> $mng_cnid</b> has been cancelled by ";
                    $mail_message .= "<b>$username</b> on <b>" . date('d/m/Y', strtotime($canceled_date)) . "</b><br/><br/>";
                    $mail_message .= "Thank you<br/><br/>";
                    $mail_message .= "Yours Sincerely<br/><br/>";
                    $mail_message .= "Risk Management Team<br/><br/>";
                    $mail_message .= "Email: riskmgmthelpdesk@gmail.com &nbsp;&nbsp;&nbsp;&nbsp; Ph: +91 9994344008";
                    $headers = "";
                    $headers .= "From: " . $contact_name . "<" . $contact_email . ">\r\n";
                    $headers .= "Reply-To: " . $contact_name . "<" . $contact_email . ">\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    mail($ass_per_email, $mail_subject, $mail_message, $headers);
                    mail($user_email, $mail_subject, $mail_message, $headers);
                }
                $response['error'] = false;
                $response['message'] = "Success";
                $response['data'] = "Successfully canceled";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Server error";
            $response['data'] = "Server error. Please try again later";
        }
    } else {
        $cancelErrors = array("RemarksErr" => $remarksErr);
        $response['error'] = true;
        $response['message'] = "Provide required fields";
        $response['data'] = json_encode($cancelErrors);
    }
    echo json_encode($response);
}
/* * *********************************** CANCEL NON-STATUTORY COMPLAINCE ENDS ************************************ */

/* * ********************************** UPLOAD STATUTORY FILES STARTS ************************************ */
if (isset($_REQUEST['upload_statutory_files'])) {
    $stat_file_id = date("YmdHis");
    define("MAX_SIZE", 2097152); // Size limit 2 MB ( Here size is converted to BYTES)
    $allowed = array('jpg', 'jpeg', 'doc', 'docx', 'xlsx', 'xls', 'pdf', 'JPG', 'JPEG', 'DOCX', 'DOC', 'XLSX', 'XLS', 'PDF');
    $uploaded_by = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['uploaded_by']));
    $sc_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['sc_id']));
    $document_no = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['document_no']));
    $description = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['description']));

    if (empty($sc_id)) {
        $scidErr = "Required";
    } else {
        $sc_idSQL = "SELECT * FROM risk_management WHERE rm_id='$sc_id'";
        $sc_id_exist = connect_db()->countEntries($sc_idSQL);
        if ($sc_id_exist > 0) {
            $fetch_sc_id = json_decode(ret_json_str($sc_idSQL));
            foreach ($fetch_sc_id as $fetch_sc_ids) {
                $stat_id = $fetch_sc_ids->id;
            }
        } else {
            $scidErr = "No statutory compliance id exists";
        }
    }
    if (file_exists($_FILES['statutory_data_upload']['tmp_name'][0])) {
        $count_files = count($_FILES['statutory_data_upload']['name']);
        $path = $statutory_file_dir;
        for ($i = 0; $i < $count_files; $i++) {
            $file_name[] = basename($_FILES['statutory_data_upload']['name'][$i]);
            $file_size[] = $_FILES['statutory_data_upload']['size'][$i]; // File size in "BYTES"
            $ext[] = strtolower(pathinfo($file_name[$i], PATHINFO_EXTENSION));
        }
        for ($p = 0; $p < $count_files; $p++) {
            if (!(in_array($ext[$p], $allowed))) {
                $err = 1;
            } else {
                if ($file_size[$p] > MAX_SIZE) {
                    $err = 2;
                } else {
                    $err = 0;
                }
            }
        }
        if ($err == 1) {
            $stat_file_dataErr = "Upload JPEG, DOC, DOCX, XLS, XLSX and PDF files";
        }
        if ($err == 2) {
            $stat_file_dataErr = "Maximum limit : 2 MB";
        }
        if ($err == 0) {
            for ($j = 0; $j < $count_files; $j++) {
                $filename[] = $stat_file_id . "." . $ext[$j];
                $file_path[] = $path . $filename[$j];
                if (move_uploaded_file($_FILES['statutory_data_upload']['tmp_name'][$j], $path . $filename[$j])) {
                    $file_move = 1;
                    $stat_file_data[] = file_get_contents($file_path[$j]);
                    $stat_file_b64[] = base64_encode($stat_file_data[$j]);
                    unlink($path . $filename[$j]);
                } else {
                    $file_move = 0;
                    unlink($path . $filename[$j]);
                    $stat_file_dataErr = "File cannot be inserted into folder";
                }
            }
        }
    } else {
        $stat_file_dataErr = "Required";
    }

    if (($stat_file_dataErr == "") && ($scidErr == "") && ($file_move == 1)) {
        for ($k = 0; $k < $count_files; $k++) {
            $sc_uploadInsertSQL = "";
            $sc_uploadInsertSQL .= "INSERT INTO upload_document_files VALUES('$stat_file_id', '$stat_id', 'SC', ";
            $sc_uploadInsertSQL .= "'$stat_file_b64[$k]', '$ext[$k]', '$document_no', '$description','$uploaded_by', NOW(),1)";
            $sc_uploadInsertStatus = connect_db()->cud($sc_uploadInsertSQL);
            if ($sc_uploadInsertStatus == true) {
                $response['error'] = false;
                $response['message'] = "Success";
                $response['data'] = "Successfully uploaded";
            } else {
                $response['error'] = true;
                $response['message'] = "Server error";
                $response['data'] = "Unable to insert";
            }
        }
    } else {
        $scupErrs = array("FileErr" => $stat_file_dataErr, "StatutoryIDErr" => $scidErr);
        $response['error'] = true;
        $response['message'] = "Recorrect the errors";
        $response['data'] = json_encode($scupErrs);
    }
    $stat_upload_ret_val = json_encode($response);
}
/* * *********************************** UPLOAD STATUTORY FILES ENDS ************************************ */
/* * ********************************** UPLOAD NON-STATUTORY FILES STARTS ************************************ */
if (isset($_REQUEST['upload_non_statutory_files'])) {
    $non_stat_file_id = date("YmdHis");
    define("MAX_SIZE", 2097152); // Size limit 2 MB ( Here size is converted to BYTES)
    $allowed = array('jpg', 'jpeg', 'doc', 'docx', 'xlsx', 'xls', 'pdf', 'JPG', 'JPEG', 'DOCX', 'DOC', 'XLSX', 'XLS', 'PDF');
    $uploaded_by = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['uploaded_by']));
    $nsc_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['nsc_id']));
    $document_no = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['document_no']));
    $description = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['description']));

    if (empty($nsc_id)) {
        $nscidErr = "Required";
    } else {
        $nsc_idSQL = "SELECT * FROM mng_cmp WHERE mng_id='$nsc_id'";
        $nsc_id_exist = connect_db()->countEntries($nsc_idSQL);
        if ($nsc_id_exist > 0) {
            $fetch_nsc_id = json_decode(ret_json_str($nsc_idSQL));
            foreach ($fetch_nsc_id as $fetch_nsc_ids) {
                $non_stat_id = $fetch_nsc_ids->id;
            }
        } else {
            $nscidErr = "No non-statutory compliance id exists";
        }
    }
    if (file_exists($_FILES['non_statutory_data_upload']['tmp_name'][0])) {
        $count_files = count($_FILES['non_statutory_data_upload']['name']);
        $path = $non_statutory_file_dir;
        for ($i = 0; $i < $count_files; $i++) {
            $file_name[] = basename($_FILES['non_statutory_data_upload']['name'][$i]);
            $file_size[] = $_FILES['non_statutory_data_upload']['size'][$i]; // File size in "BYTES"
            $ext[] = strtolower(pathinfo($file_name[$i], PATHINFO_EXTENSION));
        }
        for ($p = 0; $p < $count_files; $p++) {
            if (!(in_array($ext[$p], $allowed))) {
                $err = 1;
            } else {
                if ($file_size[$p] > MAX_SIZE) {
                    $err = 2;
                } else {
                    $err = 0;
                }
            }
        }
        if ($err == 1) {
            $non_stat_file_dataErr = "Upload JPEG, DOC, DOCX, XLS, XLSX and PDF files";
        }
        if ($err == 2) {
            $non_stat_file_dataErr = "Maximum limit : 2 MB";
        }
        if ($err == 0) {
            for ($j = 0; $j < $count_files; $j++) {
                $filename[] = $non_stat_file_id . "." . $ext[$j];
                $file_path[] = $path . $filename[$j];
                if (move_uploaded_file($_FILES['non_statutory_data_upload']['tmp_name'][$j], $path . $filename[$j])) {
                    $file_move = 1;
                    $non_stat_file_data[] = file_get_contents($file_path[$j]);
                    $non_stat_file_b64[] = base64_encode($non_stat_file_data[$j]);
                    unlink($path . $filename[$j]);
                } else {
                    $file_move = 0;
                    unlink($path . $filename[$j]);
                    $non_stat_file_dataErr = "File cannot be inserted into folder";
                }
            }
        }
    } else {
        $stat_file_dataErr = "Required";
    }

    if (($non_stat_file_dataErr == "") && ($nscidErr == "") && ($file_move == 1)) {
        for ($k = 0; $k < $count_files; $k++) {
            $nsc_uploadInsertSQL = "";
            $nsc_uploadInsertSQL .= "INSERT INTO upload_document_files VALUES('$non_stat_file_id', '$non_stat_id', 'NSC', ";
            $nsc_uploadInsertSQL .= "'$non_stat_file_b64[$k]', '$ext[$k]', '$document_no', '$description', '$uploaded_by', NOW(),1)";
            $nsc_uploadInsertStatus = connect_db()->cud($nsc_uploadInsertSQL);
            if ($nsc_uploadInsertStatus == true) {
                $response['error'] = false;
                $response['message'] = "Success";
                $response['data'] = "Successfully uploaded";
            } else {
                $response['error'] = true;
                $response['message'] = "Server error";
                $response['data'] = "Unable to insert";
            }
        }
    } else {
        $nscuplErrs = array("FileErr" => $non_stat_file_dataErr, "NonstatutoryIDErr" => $nscidErr);
        $response['error'] = true;
        $response['message'] = "Recorrect the errors";
        $response['data'] = json_encode($nscuplErrs);
    }
    $nstat_upload_ret_val = json_encode($response);
}
/* * *********************************** UPLOAD NON-STATUTORY FILES ENDS ************************************ */


/* * ********************************** DELETE FILES STARTS ************************************ */
if (isset($_REQUEST['delete_files'])) {
    //$active_inactive_token = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['token']));
    $upload_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['upload_id']));
    $page_link = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['link']));

    $upld_delSQL = "DELETE FROM upload_document_files WHERE id='$upload_id'";
    $upld_delStatus = connect_db()->cud($upld_delSQL);
    if ($upld_delStatus == true) {
        $response['error'] = false;
        $response['message'] = "Success";
        $response['data'] = "Successfully deleted";
    } else {
        $response['error'] = true;
        $response['message'] = "Server error";
        $response['data'] = "Unable to toggle";
    }
    echo "<script>alert('Deleted');window.location.href='$page_link.php';</script>";
}
/* * ********************************** DELETE FILES ENDS ************************************ */
/* * *********************************** MAIN FUNCTIONS ENDS ************************************ */
?>
