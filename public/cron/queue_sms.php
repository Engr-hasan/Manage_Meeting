<?php

require 'dbCon.php'; /* return name of current default database */
require 'mail_setting.php';

/*
 * Sending SMS from email_queue
 */
$count = 0;
if ($result3 = $mysqli->query("SELECT id, sms_content, sms_to FROM email_queue
                                WHERE sms_status=0 AND sms_to!='' ORDER BY id DESC  limit 5")) {
    while ($row = $result3->fetch_assoc()) {
        $id = $row['id'];

        $smsBody = $row['sms_content'];
        $sms = str_replace(" ", "+", $smsBody);

        $mobile_no = $row['sms_to'];
        $mobileNo = str_replace("+88", "", "$mobile_no");

        $url = "http://202.4.119.45:777/syn_sms_gw/index.php?txtMessage=$sms&msisdn=$mobileNo&usrname=bus_auto_user&password=bus_auto_user@sms";


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
    echo '<br/>No SMS in queue to send! ' . date("j F, Y, g:i a");
}
die();
