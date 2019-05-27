<?php

require 'dbCon.php'; /* return name of current default database */
require 'mail_setting.php';

/* return name of current default database */
$pdf_service_url = env('pdf_service_url');


/*
 * Sending to PDF Server from pdf_print_requests table
 */
if ($result0 = $mysqli->query("SELECT id,url_request,no_of_try FROM pdf_print_requests
                                                                WHERE status=0 ORDER BY id DESC LIMIT 5")) {

    $requestCounter = 0;
    while ($row = $result0->fetch_assoc()) {
        $id = $row['id'];
        $requested_url = $row['url_request'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $requested_url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
            echo "\n<br />";
            $response = '';
        } else {
            curl_close($ch);
        }

        $dataResponse = json_decode($response);
        $no_of_try = $row['no_of_try'] + 1; // indicates number of trying to send the data to the PDF server

        if (!empty($dataResponse->response) && ($dataResponse->response->status == -1 || $dataResponse->response->status == 1)) {
            $status = 1; // data sent
        } else if ($no_of_try > 10) {
            $status = -9; // data is invalid, abort sending
        } else {
            $status = 0; // data not send yet, will try again
        }

        $sql = "UPDATE pdf_print_requests SET status=$status, no_of_try=$no_of_try  WHERE id=$id";
        $mysqli->query($sql);
        $requestCounter++;
    }
    echo "<br/>No of Pdf print request is : " . $requestCounter . "<br/>";
    $result0->close();
}
/* End of Sending to PDF Server from pdf_print_requests table */


/*
 * Updating certificate related rows of Application Base table
 */
if ($result1 = $mysqli->query("SELECT id, app_id, service_id, other_significant_id, secret_key, pdf_type
                                                                FROM pdf_queue WHERE status=0")) {
    while ($row = $result1->fetch_assoc()) {
        $id = $row['id'];
        $app_id = $row['app_id'];
        $service_id = $row['service_id'];
        $other_significant_id = $row['other_significant_id'];
        $reg_key = $row['secret_key'];
        $pdf_type = $row['pdf_type'];

        $data = array();
        $data['data'] = array(
            'reg_key' => $reg_key, // Authentication key
            'pdf_type' => $pdf_type, // letter type
            'ref_id' => $app_id, //app_id
            'param' => array(
                'app_id' => $app_id, // app_id
                'service_id' => $service_id, // service_id of each module
                'other_significant_id' => $other_significant_id, // default 0, for gate pass: gp_id, for undertaking: tracking no etc.
            )
        );

        $data1 = json_encode($data);
        $url = $pdf_service_url . "api/job-status?requestData=$data1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo curl_error($ch);
            echo "\n<br />";
            $response = '';
        } else {
            curl_close($ch);
        }

        $dataResponse = json_decode($response);

        if (!empty($dataResponse->response) && $dataResponse->response->status == 1) {
            $attachmentUrl = $dataResponse->response->download_link;
            $sql = "UPDATE pdf_queue SET attachment='" . $attachmentUrl . "' WHERE id=$id";

            switch ($service_id) {
                case 1: // Project Clearance
                    $sql2 = "UPDATE project_clearance SET certificate='" . $attachmentUrl . "' WHERE id=$app_id";

                case 2: // Visa Assistance
                    $sql2 = "UPDATE visa_assistance SET certificate='" . $attachmentUrl . "' WHERE id=$app_id";

                case 3: // Visa Recommendation
                    $sql2 = "UPDATE visa_recommendation SET certificate='" . $attachmentUrl . "' WHERE id=$app_id";

                case 4: // Work Permit
                    $sql2 = "UPDATE work_permit SET certificate='" . $attachmentUrl . "' WHERE id=$app_id";

                case 5: // Export Permit
                    $sql2 = "UPDATE export_permit SET certificate='" . $attachmentUrl . "' WHERE id=$app_id";

                    // For undertaking
                    if (in_array($pdf_type, ['beza.epundertaking.local', 'beza.epundertaking.l', 'beza.epundertaking.d', 'beza.epundertaking.uat'])) {
                        $sql3 = "UPDATE export_permit SET undertaking='" . $attachmentUrl . "' WHERE id=$app_id";

                        $mysqli->query($sql3);
                    }

                    // For gate pass
                    if (in_array($pdf_type, ['beza.epgatepass.local', 'beza.epgatepass.l', 'beza.epgatepass.d', 'beza.epgatepass.uat'])) {
                        $sql3 = "UPDATE ep_gatepass SET generated_gatepass='" . $attachmentUrl .
                                "' WHERE id=$other_significant_id AND app_id=$app_id";
                        $mysqli->query($sql3);
                    }

                case 6: // Import Permit
                    $sql2 = "UPDATE import_permit SET certificate='" . $attachmentUrl . "' WHERE id=$app_id";

                    // For undertaking
                    if (in_array($pdf_type, ['beza.ipundertaking.local', 'beza.ipundertaking.l', 'beza.ipundertaking.d', 'beza.ipundertaking.uat'])) {
                        $sql3 = "UPDATE import_permit SET undertaking='" . $attachmentUrl . "' WHERE id=$app_id";

                        $mysqli->query($sql3);
                    }

                    // For gate pass
                    if (in_array($pdf_type, ['beza.ipgatepass.local', 'beza.ipgatepass.l', 'beza.ipgatepass.d', 'beza.ipgatepass.uat'])) {
                        $sql3 = "UPDATE ip_gatepass SET generated_gatepass='" . $attachmentUrl .
                                "' WHERE id=$other_significant_id AND app_id=$app_id";
                        $mysqli->query($sql3);
                    }

                case 7: // Land Requisition
                    $sql2 = "UPDATE land_requisition SET certificate='" . $attachmentUrl . "' WHERE id=$app_id";

                case 9: // Project Registration
                    $sql2 = "UPDATE project_clearance SET pr_certificate='" . $attachmentUrl . "' WHERE id=$app_id";

                    break;

                default:
            }

            $mysqli->query($sql);
            $mysqli->query($sql2);
        } else {
            $sql = "UPDATE pdf_queue SET attachment='' WHERE id=$id";
            $mysqli->query($sql);
        }
    }
    $result1->close();
}
/* End of updating certificate related rows of Application Base table */

/*
 * Sending emails from email_queue
 */
$count = 0;
if ($result2 = $mysqli->query("SELECT id,email_to,email_cc,email_content,no_of_try,no_of_try 
                                                            FROM email_queue WHERE email_status=0 ORDER BY id DESC LIMIT 5")) {
    while ($row = $result2->fetch_assoc()) {
        $email_to = '';
        $id = $row['id'];
        $email_content = $row['email_content'];
        $email_to = str_replace("'", "", $row['email_to']);
        $email_cc = str_replace("'", "", $row['email_cc']);
        $attachment = $row['attachment'];

        $no_of_try = $row['no_of_try'];

        $mail->setFrom('no-reply@beza.com.bd', 'OSS Framework');
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

        $attachments = '<br/><a href="' . $attachment . '"><u>Click here for download the document.</u></a>';

        $mail->Subject = 'Application Update Information';
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


/*
 * Sending SMS from email_queue
 */
if ($result3 = $mysqli->query("SELECT id, sms_content, sms_to FROM email_queue
                                WHERE sms_status=0 AND sms_to!='' ORDER BY id DESC  limit 5")) {
    while ($row = $result3->fetch_assoc()) {
        $id = $row['id'];

        $smsBody = $row['sms_content'];
        $sms = str_replace(" ", "+", $smsBody);

        $mobile_no = $row['sms_to'];
        $mobileNo = str_replace("+88", "", "$mobile_no");

//        $url = "http://202.4.119.45:777/syn_sms_gw/index.php?txtMessage=$sms&msisdn=$mobileNo&usrname=bus_auto_user&password=bus_auto_user@sms";
        $url = '';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($curl);
        curl_close($curl);

        $count++;
        $sql = "UPDATE email_queue SET sms_status=1 WHERE id=$id";
        $mysqli->query($sql);
    }
    $result3->close();
}
/* End of sending SMS from email_queue */

$mysqli->close();
if ($count == 0) {
    echo '<br/>No email in queue to send! ' . date("j F, Y, g:i a");
}
die();
