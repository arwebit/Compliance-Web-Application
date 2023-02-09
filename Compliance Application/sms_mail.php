<?php
include './file_includes.php';
$headers = "";
$headers .= "From: " . $contact_name . "<" . $contact_email . ">\r\n";
$headers .= "Reply-To: " . $contact_name . "<" . $contact_email . ">\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


$scmailsms_SQL="";
$scmailsms_SQL .= "SELECT a.due_date, c.purpose, b.email, e.email first_officer_email, f.email second_officer_email,g.email third_officer_email FROM risk_management a INNER JOIN member_registration b ";
$scmailsms_SQL .= "ON a.assign_user=b.user_name INNER JOIN mas_purpose c ON a.purpose_id=c.id LEFT JOIN mem_reporting_officer d ON a.assign_user=d.user_name ";
$scmailsms_SQL .= "LEFT JOIN member_registration e ON e.user_name=d.first_officer LEFT JOIN member_registration f ON f.user_name=d.second_officer LEFT ";
$scmailsms_SQL .= "JOIN member_registration g ON g.user_name=d.third_officer ORDER BY a.rm_id DESC";
$fetch_scsmsmail = json_decode(ret_json_str($scmailsms_SQL));
foreach ($fetch_scsmsmail as $fetch_scsmsmails) {
    $due_date = $fetch_scsmsmails->due_date;
    $str_due_date = strtotime($due_date);
    $datediff = $str_due_date - strtotime(curr_date_time());
    $left_days = round($datediff / (60 * 60 * 24));
    $receipient_email_id = $fetch_scsmsmails->email;
    $fo_email_id = $fetch_scsmsmails->first_officer_email;
    $so_email_id = $fetch_scsmsmails->second_officer_email;
    $tho_email_id = $fetch_scsmsmails->third_officer_email;
    $purpose = $fetch_scsmsmails->purpose;
    $mail_message = "";

    if (($left_days == "7")||($left_days == "3")||($left_days == "0")) {
        $mail_message = "";
        $mail_subject="Reminder of statutory compliance";
        $mail_message .= "Dear Sir/Madam<br/><br/>";
        $mail_message .= "This is to remind you that the '<b>" . $purpose . "</b>' Return is due for filing as on ";
        $mail_message .= "'<b>" . date('d/m/Y', strtotime($due_date)) . "</b>'. Kindly take necessary action for same<br/><br/>";
        $mail_message .= "Thank you<br/><br/>";
        $mail_message .= "Yours Sincerely<br/><br/>";
        $mail_message .= "Risk Management Team<br/><br/>";
        $mail_message .= "Email: riskmgmthelpdesk@gmail.com &nbsp;&nbsp;&nbsp;&nbsp; Ph: +91 9994344008";
        mail($receipient_email_id, $mail_subject, $mail_message, $headers);
          if ((!empty($fo_email_id)) || ($fo_email_id != "")) {
            mail($fo_email_id, $mail_subject, $mail_message, $headers);
        }
        if ((!empty($so_email_id)) || ($so_email_id != "")) {
            mail($so_email_id, $mail_subject, $mail_message, $headers);
        }
        if ((!empty($tho_email_id)) || ($tho_email_id != "")) {
            mail($tho_email_id, $mail_subject, $mail_message, $headers);
        }
    } else {
        if ($left_days == -1) {
            $mail_message = "";
            $mail_subject="Overdue of statutory compliance";
            $mail_message .= "Dear Sir/Madam<br/><br/>";
            $mail_message .= "This is to bring to your notice that the '<b>" . $purpose . "</b>' Return was <b>NOT filed</b> as on ";
            $mail_message .= "'<b>" . date('d/m/Y', strtotime($due_date)) . "</b>'. There is a delay of <b>1 day</b> as of now. ";
            $mail_message .= " Kindly take necessary action for same<br/><br/>";
            $mail_message .= "Thank you<br/><br/>";
            $mail_message .= "Yours Sincerely<br/><br/>";
            $mail_message .= "Risk Management Team<br/><br/>";
            $mail_message .= "Email: riskmgmthelpdesk@gmail.com &nbsp;&nbsp;&nbsp;&nbsp; Ph: +91 9994344008";
            mail($receipient_email_id, $mail_subject, $mail_message, $headers);
             if ((!empty($fo_email_id)) || ($fo_email_id != "")) {
            mail($fo_email_id, $mail_subject, $mail_message, $headers);
        }
        if ((!empty($so_email_id)) || ($so_email_id != "")) {
                mail($so_email_id, $mail_subject, $mail_message, $headers);
            }
            if ((!empty($tho_email_id)) || ($tho_email_id != "")) {
                mail($tho_email_id, $mail_subject, $mail_message, $headers);
            }
        }
    }
}






$nscmailsms_SQL="";
$nscmailsms_SQL .= "SELECT a.due_date, a.mng_id, b.email, e.email first_officer_email, f.email second_officer_email,g.email third_officer_email FROM mng_cmp a INNER JOIN member_registration b ";
$nscmailsms_SQL .= "ON a.username=b.user_name LEFT JOIN mem_reporting_officer d ON a.username=d.user_name ";
$nscmailsms_SQL .= "LEFT JOIN member_registration e ON e.user_name=d.first_officer LEFT JOIN member_registration f ON f.user_name=d.second_officer LEFT ";
$nscmailsms_SQL .= "JOIN member_registration g ON g.user_name=d.third_officer";
$fetch_nscsmsmail = json_decode(ret_json_str($nscmailsms_SQL));
foreach ($fetch_nscsmsmail as $fetch_nscsmsmails) {
    $due_date = $fetch_nscsmsmails->due_date;
    $str_due_date = strtotime($due_date);
    $datediff = $str_due_date - strtotime(curr_date_time());
    $left_days = round($datediff / (60 * 60 * 24));
    $receipient_email_id = $fetch_nscsmsmails->email;
    $fo_email_id = $fetch_nscsmsmails->first_officer_email;
    $so_email_id = $fetch_nscsmsmails->second_officer_email;
    $tho_email_id = $fetch_nscsmsmails->third_officer_email;
    $mng_id = $fetch_nscsmsmails->mng_id;
    $mail_message = "";

     if (($left_days == "7")||($left_days == "3")||($left_days == "0")) {
        $mail_message = "";
        $mail_subject="Reminder of non-statutory compliance";
        $mail_message .= "Dear Sir/Madam<br/><br/>";
        $mail_message .= "This is to remind you that the non-statutory no. '<b>" . $mng_id . "</b>' Return is due for filing as on ";
        $mail_message .= "'<b>" . date('d/m/Y', strtotime($due_date)) . "</b>'. Kindly take necessary action for same<br/><br/>";
        $mail_message .= "Thank you<br/><br/>";
        $mail_message .= "Yours Sincerely<br/><br/>";
        $mail_message .= "Risk Management Team<br/><br/>";
        $mail_message .= "Email: riskmgmthelpdesk@gmail.com &nbsp;&nbsp;&nbsp;&nbsp; Ph: +91 9994344008";
        mail($receipient_email_id, $mail_subject, $mail_message, $headers);
          if ((!empty($fo_email_id)) || ($fo_email_id != "")) {
            mail($fo_email_id, $mail_subject, $mail_message, $headers);
        }
        if ((!empty($so_email_id)) || ($so_email_id != "")) {
            mail($so_email_id, $mail_subject, $mail_message, $headers);
        }
        if ((!empty($tho_email_id)) || ($tho_email_id != "")) {
            mail($tho_email_id, $mail_subject, $mail_message, $headers);
        }
    } else {
        if ($left_days == -1) {
            $mail_message = "";
            $mail_subject="Overdue of non-statutory compliance";
            $mail_message .= "Dear Sir/Madam<br/><br/>";
            $mail_message .= "This is to bring to your notice that the non-statutory no. '<b>" . $mng_id . "</b>' Return was <b>NOT filed</b> as on ";
            $mail_message .= "'<b>" . date('d/m/Y', strtotime($due_date)) . "</b>'. There is a delay of <b>1 day</b> as of now. ";
            $mail_message .= " Kindly take necessary action for same<br/><br/>";
            $mail_message .= "Thank you<br/><br/>";
            $mail_message .= "Yours Sincerely<br/><br/>";
            $mail_message .= "Risk Management Team<br/><br/>";
            $mail_message .= "Email: riskmgmthelpdesk@gmail.com &nbsp;&nbsp;&nbsp;&nbsp; Ph: +91 9994344008";
            mail($receipient_email_id, $mail_subject, $mail_message, $headers);
             if ((!empty($fo_email_id)) || ($fo_email_id != "")) {
            mail($fo_email_id, $mail_subject, $mail_message, $headers);
        }
        if ((!empty($so_email_id)) || ($so_email_id != "")) {
                mail($so_email_id, $mail_subject, $mail_message, $headers);
            }
            if ((!empty($tho_email_id)) || ($tho_email_id != "")) {
                mail($tho_email_id, $mail_subject, $mail_message, $headers);
            }
        }
    }
}
?>

