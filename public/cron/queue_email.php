<?php

require 'dbCon.php'; /* return name of current default database */
require 'mail_setting.php';


/*
 * Sending emails from email_queue
 */
$count = 0;
$mysqli->query("SET NAMES 'utf8'");
if ($result2 = $mysqli->query("SELECT id,email_to,email_cc,email_content,no_of_try,attachment,email_subject
                                                            FROM email_queue WHERE email_status=0 ORDER BY id DESC LIMIT 5")) {

    while ($row = $result2->fetch_assoc()) {
        $email_to = '';
        $id = $row['id'];
        $email_content = $row['email_content'];
        $email_to = str_replace("'", "", $row['email_to']);
        $email_cc = str_replace("'", "", $row['email_cc']);
        $attachment = $row['attachment'];
        $email_subject = $row['email_subject'];

        $no_of_try = $row['no_of_try'];

        $mail->setFrom('no-reply@ocpl.com.bd', env('PROJECT_NAME'));
        $mail->addAddress($email_to, '');     // Add a recipient email, Recipent Name is optional

        $email_cc_exp = explode(',', $email_cc);
        if (!empty($email_cc_exp[1])) {
            foreach ($email_cc_exp as $emailCC) {
                $mail->addCC($emailCC);
            }
        } else {
            $mail->addCC($email_cc);
        }

        //$mail->addBCC('jakir.ocpl@batworld.com');
        $mail->addAttachment($attachment);         // Add attachments
        //$mail->addAttachment('http://beza.sms.com.bd/uploads/2016/10/beza_57f09bb96aaa79.73874888.pdf', 'beza_57f09bb96aaa79.73874888.pdf');    // Optional name
        $mail->isHTML(true); // Set email format to HTML

        if($attachment){
            $attachments = '<br/><a href="' . $attachment . '"><u>Click here for downloading the document.</u></a>';
        } else{
            $attachments = '';
        }

        $mail->Subject = $email_subject;
        $mail->Body = $email_content . $attachments;
        $mail->AltBody = '';

        if (!$mail->send()) {
            $mail_msg = '<br/> Email could not be sent. <br/> Mailer Error: ' . $mail->ErrorInfo;
            $no_of_try = $row['no_of_try'] + 1; // indicates number of failed trying to send the data to the PDF server

            if ($no_of_try > 10) {
                $status = -9; // data is invalid, abort sending
            } else {
                $status = 0; // email has not been sent yet
            }
        } else {
            $mail_msg = '<br/> Email  has been sent on ' . date("j F, Y, g:i a");
            $status = 1;
        }
        $mail->ClearAddresses();
        $mail->ClearCCs();
        $count++;


        $sql = "UPDATE email_queue SET email_status=$status WHERE id=$id";
        $mysqli->query($sql);
        echo $mail_msg; // For showing the sending status of the email
    }
    $result2->close();
}
/* End of sending emails from email_queue */

$mysqli->close();
if ($count == 0) {
    echo '<br/>No email in queue to send! ' . date("j F, Y, g:i a");
}
die();
