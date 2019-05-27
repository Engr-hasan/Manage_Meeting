<?php

require 'dbCon.php'; /* return name of current default database */
require 'mail_setting.php';


/*
 * Sending emails from email_queue
 */
$count = 0;
if ($result2 = $mysqli->query("SELECT id,meting_date,agenda_ending_date FROM board_meting WHERE status=6 ORDER BY id DESC LIMIT 2")) {

    while ($row = $result2->fetch_assoc()) {
        date_default_timezone_set("asia/dhaka");
        $id = $row['id'];
        $count++;


        $currentDate =  (new DateTime())->format("Y-m-d H:i:s");
        $fixedDate = (new DateTime($row['agenda_ending_date']))->format("Y-m-d H:i:s");

//        var_dump($fixedDate);
//        "2018-01-24 11:29:41"
//        "2018-01-24 11:29:00"
//        exit;
//        var_dump($d2 >= $d2);
//        var_dump($d1 < $d2);
//        exit;
        if($currentDate >= $fixedDate) {
            $sql = "UPDATE board_meting SET status=5 WHERE id=$id";
            $mysqli->query($sql);
            echo  $mail_msg = '<br/> Today Board meeting has been fixed ' . date("j F, Y, g:i a"); // For showing the sending status of the email
        }
    }
    $result2->close();
}
/* End of sending emails from email_queue */

$mysqli->close();

if ($count == 0) {
    echo '<br/>Today not meeting ' . date("j F, Y, g:i a");
}
die();
