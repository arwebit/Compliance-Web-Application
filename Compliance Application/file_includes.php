<?php

error_reporting(1);
session_start();
ob_start();

define("ROOTFOLDER", "Compliance_Web_Application/");
//define("ROOTFOLDER", "");
date_default_timezone_set('Asia/Kolkata');
include './classes/DBOperation.php';
include './classes/DBconfig.php';
$statutory_file_name = "Statutory_management";
$non_statutory_file_name = "Non-statutory_management";
$contact_name = "Soumyanjan Dey";
$contact_email = "arghya992@gmail.com";
/* $contact_name = "Risk Management";
  $contact_email = "riskmgmthelpdesk@gmail.com"; */

function curr_date_time() {
    $cuur_date = date("Y-m-d");
    return $cuur_date;
}

function DB_curr_date_time() {
    $dt_timeSQL = "SELECT NOW() CURR_DATE_TIME FROM DUAL";
    $date_time_val = json_encode(connect_db()->fetchData($dt_timeSQL));
    return $date_time_val;
}

function site_url() {
    $link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/" . ROOTFOLDER;
    return $link;
}

function curr_fy() {
    $month = 01;
    $current_month = date('m', strtotime(curr_date_time()));
    if ($current_month >= '01' && $current_month < '4') {

        if ($month >= '01' && $month < '04') {
            $fy = date('y');
        }

        if ($month >= 4) {
            $fy = date('y') - 1;
        }
    }

    if ($current_month >= 4) {
        if ($month >= 4) {
            $fy = date('y');
        }

        if ($month < 4) {
            $fy = date('y') + 1;
        }
    }
    return $fy;
}

if (!is_dir("upload_files")) {
    mkdir("upload_files");
    if (!is_dir("upload_files/statutory")) {
        mkdir("upload_files/statutory");
        $statutory_file_dir = "upload_files/statutory/";
    } else {
        $statutory_file_dir = "upload_files/statutory/";
        array_map('unlink', glob("$statutory_file_dir*.*"));
    }
    if (!is_dir("upload_files/non_statutory")) {
        mkdir("upload_files/non_statutory");
        $non_statutory_file_dir = "upload_files/non_statutory/";
    } else {
        $non_statutory_file_dir = "upload_files/non_statutory/";
        array_map('unlink', glob("$non_statutory_file_dir*.*"));
    }
} else {
    if (!is_dir("upload_files/statutory")) {
        mkdir("upload_files/statutory");
        $statutory_file_dir = "upload_files/statutory/";
    } else {
        $statutory_file_dir = "upload_files/statutory/";
        array_map('unlink', glob("$statutory_file_dir*.*"));
    }
    if (!is_dir("upload_files/non_statutory")) {
        mkdir("upload_files/non_statutory");
        $non_statutory_file_dir = "upload_files/non_statutory/";
    } else {
        $non_statutory_file_dir = "upload_files/non_statutory/";
        array_map('unlink', glob("$non_statutory_file_dir*.*"));
    }
}
ob_flush();
?>

