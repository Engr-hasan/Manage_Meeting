#!/usr/bin/python                   #created by Reyad
import pymysql
import requests
import os
import json
from datetime import datetime
from dotenv import load_dotenv
load_dotenv()
hostname = os.getenv("DB_HOST")
username = os.getenv("DB_USERNAME")
password = os.getenv("DB_PASSWORD")
database = os.getenv("DB_DATABASE")
myConnection = pymysql.connect(host=hostname, user=username, passwd=password, db=database)


def sms_queue(conn):
    current_execute_file_path = os.path.basename(__file__)
    get_time_index = current_execute_file_path.split('=')
    if 1 < len(get_time_index):
        time_index = int(get_time_index[1])
    else:
        time_index = 0

    hour = 48
    limit = 20
    offset = 0

    if time_index > 15:
        exit()
    elif time_index > 0:
        offset = (time_index * limit)+limit

    # exit()
    cur = conn.cursor()
    # for details in cur.fetchall()
    query = "SELECT id, sms_content, sms_to,sms_status,sent_on,no_of_try " \
            " FROM email_queue WHERE sms_to!=''" \
            " AND no_of_try<3 " \
            " AND (sms_status = 0 OR (sms_status=-1 AND ADDDATE(sent_on, INTERVAL 180 SECOND) < NOW()))" \
            " AND `created_at` >= DATE_SUB(NOW(), INTERVAL "+str(hour)+" HOUR)" \
            " ORDER BY id DESC  limit "+str(offset)+", "+str(limit)+" "
    result = cur.execute(query)

    number = 0
    mail_messages = ''
    if result > 0:
        for id, sms_content, sms_to, sms_status, sent_on, no_of_try in cur.fetchall():
            try:
                id = id
                sms_status = sms_status
                sent_on = str(sent_on)
                no_of_try = no_of_try
                mobile_no = sms_to.replace('+88', '')
                sms = sms_content.replace(' ', '+')
                sql1 = "UPDATE email_queue SET sms_status=%s, sent_on=NOW(), " \
                       " no_of_try=%s, cron_id=%s" \
                       " WHERE id=%s AND sms_status=%s" \
                       " AND (sent_on IS NULL OR sent_on=%s )"
                # print(sql1)
                data4 = ('-1', no_of_try+1, time_index, id, sms_status, sent_on)
                status = cur.execute(sql1, data4)

                if status is None:
                    continue

                url = "http://202.4.119.45:777/syn_sms_gw/index.php?txtMessage="+sms+"&msisdn=" \
                      ""+mobile_no+"&usrname=bus_auto_user&password=bus_auto_user@sms"
                response = requests.get(url)
                all_response = json.loads(response.text)

                if all_response['is_success'] == '1':
                    sql4 = "UPDATE email_queue set sms_status =%s, response=%s, sent_on = %s  WHERE id =%s "
                    data2 = (1, str(json.dumps(all_response)), datetime.now().strftime('%Y-%m-%d %H:%M:%S'), id)
                    mail_messages = "SMS has been sent date on " + datetime.now().strftime('%Y-%m-%d %H:%M:%S')

                elif all_response['is_success'] == 'null':
                    sql4 = "UPDATE email_queue set sms_status=%s, response=%s WHERE id =%s "
                    data2 = ('-9', str(json.dumps(all_response)), id)
                else:
                    sql4 = "UPDATE email_queue set sms_status =%s , response= %s WHERE id =%s"
                    data2 = (0, str(json.dumps(all_response)), id)
                cur.execute(sql4, data2)

                # query1 = "UPDATE email_queue SET sms_status = %s where id= %s"
                # data = (1, id)
                # cur.execute(query1, data)
                conn.commit()
                number += 1
            except ValueError:
                mail_messages = 'Something went wrong...' + ValueError

            print(mail_messages)

    if number == 0:
        print("No SMS in queue to send! " + datetime.now().strftime('%Y-%m-%d %H:%M:%S'))


print("Using MySQLdb…")
sms_queue(myConnection)
myConnection.close()

