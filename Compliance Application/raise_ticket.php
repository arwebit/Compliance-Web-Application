<?php
$toEmail = array("arghya992@gmail.com", "tutorcode992@gmail.com");
//$toEmail = array("indtaxramya@gmail.com", "navdwar25@gmail.com");
$cc = implode(",", $toEmail);
if (isset($_REQUEST['raise_ticket'])) {
    $id = date("YmdHis");
    $ticket_subject = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['ticket_subject']));
    $ticket_description = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['ticket_description']));
    $ticket_type = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['ticket_type']));

    if (empty($ticket_subject)) {
        $ticket_subErr = "Required";
    }
    if (empty($ticket_description)) {
        $ticket_descriptionErr = "Required";
    }
    if (empty($ticket_type)) {
        $ticket_typeErr = "Required";
    }
    if (($ticket_subErr == "") && ($ticket_descriptionErr == "") && ($ticket_typeErr == "")) {
        $ticketcountSQL = "SELECT * FROM ticket";
        $ticketcount = (connect_db()->countEntries($ticketcountSQL)) + 1;
        if ($ticketcount < 100) {
            if ($ticketcount < 10) {
                $ticketcount = "00" . $ticketcount;
            } else {
                $ticketcount = "0" . $ticketcount;
            }
        }
        $ticket_id = "TKT/$ticketcount";
        $ticketInsertSQL = "";
        $ticketInsertSQL .= "INSERT INTO ticket VALUES('$id','$ticket_id', '$ticket_subject', '$ticket_type', ";
        $ticketInsertSQL .= "'$ticket_description','$login_user', '$login_company_id', NOW(), null, 0)";
        $ticketInsertStatus = connect_db()->cud($ticketInsertSQL);
        if ($ticketInsertStatus == true) {
            $employee_SQL = "";
            $employee_SQL .= "SELECT b.email, c.company_name, a.ticket_date FROM ticket a INNER JOIN member_registration b ON ";
            $employee_SQL .= "a.ticket_user=b.user_name INNER JOIN mas_company c ON a.ticket_user_company=c.id WHERE a.ticket_id='$ticket_id' ";
            $employee_SQL .= "AND b.user_name='$login_user' AND b.company_id='$login_company_id'";
            $fetch_employee = json_decode(ret_json_str($employee_SQL));
            foreach ($fetch_employee as $fetch_employees) {
                $recipient = $fetch_employees->email;
                $company_name = $fetch_employees->company_name;
                $ticket_date = $fetch_employees->ticket_date;
            }
            $mail_message = "";
            $mail_subject = "New ticket raised - " . $ticket_id;
            $mail_message .= "Hi,<br/><br/>";
            $mail_message .= "The ticket is raised by " . $login_user . " - " . $company_name . " on " . date("d-m-Y", strtotime($ticket_date)) . " <br/><br/>";
            $mail_message .= "<b>Subject : </b>" . $ticket_subject . "<br/><br/>";
            $mail_message .= "<b>Type : </b>" . $ticket_type . "<br/><br/>";
            $mail_message .= "<b>Issue : </b><br/>" . $ticket_description . "<br/><br/>";
            $mail_message .= "Thank you";
            $headers = "";
            $headers .= "From: " . $login_user . "<" . $recipient . ">\r\n";
            $headers .= "Reply-To: " . $login_user . "<" . $recipient . ">\r\n";
            $headers .= "Cc: " . $cc . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($recipient, $mail_subject, $mail_message, $headers);
            $tsuccess_msg = "Successfully raised ticket no. : " . $ticket_id;
        } else {
            $terror_msg = "Server error";
        }
    } else {
        $terror_msg = "Provide all the fields";
    }
}


if (isset($_REQUEST['ticket_option'])) {
    $ticket_op_id = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['ticket_op_id']));
    $ticket_option = mysqli_real_escape_string(connect_db()->getConnection(), trim($_REQUEST['ticket_option']));
    if ($ticket_option == "close") {
        $ticket_status = -1;
        $tkt_status_msg="has not been resolved";
    } else {
        $ticket_status = 1;
        $tkt_status_msg="has been resolved";
    }
    $ticketUpdateSQL = "UPDATE ticket SET status='$ticket_status', ticket_closed_date=NOW() WHERE id='$ticket_op_id'";
    $ticketUpdateStatus = connect_db()->cud($ticketUpdateSQL);
    if ($ticketUpdateStatus == true) {
        $employee_SQL = "";
        $employee_SQL .= "SELECT b.email, a.ticket_id, a.ticket_user, a.ticket_closed_date FROM ticket a ";
        $employee_SQL .= "INNER JOIN member_registration b ON a.ticket_user=b.user_name WHERE a.id='$ticket_op_id'";
        $fetch_employee = json_decode(ret_json_str($employee_SQL));
        foreach ($fetch_employee as $fetch_employees) {
            $recipient = $fetch_employees->email;
            $ticket_id= $fetch_employees->ticket_id;
            $ticket_closed_date= $fetch_employees->ticket_closed_date;
        }

        $mail_message = "";
        $mail_subject = "Status of ticket - " . $ticket_id ;
        $mail_message .= "Hi,<br/><br/>";
        $mail_message .= "The ticket - <b>" . $ticket_id . "</b> ".$tkt_status_msg ;
        $mail_message .= " and has closed on ".date("d-m-Y", strtotime($ticket_closed_date)).". <br/><br/>";
        $mail_message .= "Thank you";
        $headers = "";
        $headers .= "From: " . $login_user . "<" . $recipient . ">\r\n";
        $headers .= "Reply-To: " . $login_user . "<" . $recipient . ">\r\n";
        $headers .= "Cc: " . $cc . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail($recipient, $mail_subject, $mail_message, $headers);
    }
}
?> 
