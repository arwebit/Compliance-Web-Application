<?php

include './file_includes.php';
$id = date("YmdHis");
$allowed = array('csv', 'CSV');
define("MAX_SIZE", 1048576); // Size limit 1 MB ( Here size is converted to BYTES)
if (isset($_REQUEST['upload_master_data'])) {
    $count_stat_data = 0;
    $masupl_delSQL = "DELETE FROM upl_master";
    connect_db()->cud($masupl_delSQL);
    $company_id = $_POST['company_id'];
    if ($_FILES['master_data_upload']['name']) {
        $file_name = $_FILES['master_data_upload']['name'];
        $file_size = $_FILES['master_data_upload']['size']; // File size in "BYTES"
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (!(in_array($ext, $allowed))) {
            $master_file_error = "Upload CSV file";
        } else {
            if ($file_size > MAX_SIZE) {
                $master_file_error = "Upload less than or equal to 1 MB";
            } else {
                $csvFile = fopen($_FILES['master_data_upload']['tmp_name'], 'r');
                fgetcsv($csvFile);
                while (($line = fgetcsv($csvFile)) !== FALSE) {
                    $count_stat_data++;
                    $master_desc[] = mysqli_real_escape_string(connect_db()->getConnection(), trim($line[0]));
                }
            }
        }
    } else {
        $master_file_error = "Insert file";
    }
    if ($master_file_error == "") {
        for ($i = 0; $i < $count_stat_data; $i++) {
            $importmasSQL = "";
            $importmasSQL .= "INSERT INTO upl_master VALUES('$master_desc[$i]','$company_id')";
            $importmasStatus = connect_db()->cud($importmasSQL);
            if ($importmasStatus == true) {
                $master_success_msg = "Successfully imported master data. Please comfirm";
            } else {
                $master_error_msg = "Recorrect errors";
            }
        }
    } else {
        $master_error_msg = $master_file_error;
    }
}
if (isset($_REQUEST['confirm_master_data'])) {
    $table_name=trim($_REQUEST['table_name']);
    $create_user=trim($_REQUEST['create_user']);
    $mas_SQL = "SELECT * FROM upl_master";
    $fetch_master = json_decode(ret_json_str($mas_SQL));
    foreach ($fetch_master as $fetch_masters) {
        $id++;
        $company_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_masters->company_id));
        $master_desc = mysqli_real_escape_string(connect_db()->getConnection(), trim($fetch_masters->master_desc));

        $masterInsertSQL = "";
        $masterInsertSQL .= "INSERT INTO $table_name VALUES('$id','$company_id','$master_desc',1, '$create_user', NOW(), ";
        $masterInsertSQL .= "'$create_user', NOW())";
        $masterInsertStatus = connect_db()->cud($masterInsertSQL);
        if ($masterInsertStatus == true) {
            $masupl_delSQL = "DELETE FROM upl_master";
    connect_db()->cud($masupl_delSQL);
            $master_success_msg = "Successfully inserted";
        } else {
            $master_error_msg = "Error inserting";
        }
    }
}

?>

