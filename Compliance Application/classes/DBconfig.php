<?php

date_default_timezone_set("Asia/Kolkata");

function connect_db() {
    $dboperation = new DBOperation("localhost", "root", "", "crm");
     //  $dboperation = new DBOperation("localhost", "recoitcc_cwa", "Compliance@1234567890", "recoitcc_crm");
    return $dboperation;
}
function ret_json_str($sql) {
    $ret_val = json_encode(connect_db()->fetchData($sql));
    return $ret_val;
}

?>

