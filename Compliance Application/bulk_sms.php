<?php

if (isset($_REQUEST['send_circular'])) {
    $clause = "";
    $subject = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['subject']));
    $message = trim($_REQUEST['message']);

    if ((empty($_REQUEST['department'])) && (empty($_REQUEST['location']))) {
        $clause = "";
    } else {
        if (!empty($_REQUEST['department'])) {
            $clause .= "AND department IN (" . implode(",", $_REQUEST['department']) . ") ";
        }
        if (!empty($_REQUEST['location'])) {
            $clause .= "AND location IN (" . implode(",", $_REQUEST['location']) . ")";
        }
    }

    if (empty($subject)) {
        $subErr = "Required";
    }
    if (empty($message)) {
        $msgErr = "Required";
    }
    if (($subErr == "")&&($msgErr == "")) {
        $user_emailSQL = "SELECT * FROM member_registration WHERE company_id='$login_company_id' " . $clause;
        $fetch_userEmail = json_decode(ret_json_str($user_emailSQL));
        if (($fetch_userEmail != null) || ($fetch_userEmail != "")) {
            foreach ($fetch_userEmail as $fetch_userEmails) {
                $to_email = $fetch_userEmails->email;
                $headers = "";
                $headers .= "From: " . $contact_name . "<" . $contact_email . ">\r\n";
                $headers .= "Reply-To: " . $contact_name . "<" . $contact_email . ">\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                $mail_sent = mail($to_email, $subject, $message, $headers);
                if ($mail_sent == true) {
                    $success_msg = "Circular sent";
                } else {
                    $error_msg = "Circular not sent";
                }
            }
        } else {
            $error_msg = "No emails found";
        }
    }else{
        $error_msg = "Provide all the fields";
    }
}
?> 
