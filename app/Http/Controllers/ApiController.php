<?php

namespace App\Http\Controllers;

use App\ActionInformation;
use App\EmailSmsQueue;
use App\Libraries\Encryption;
use App\UrlInformation;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;


class ApiController extends Controller
{



    public function newJob(Request $request)
    {
        $client_request = $request->all();
        $response = array();
        if ($client_request == '' || $client_request == null || !isset($client_request)){
            $response = array( // Response for invalid request
                'status' => 400,
                'success' => false,
                'error' => array(
                    'code' => 'EQR101',
                    'message' => 'Invalid Request or Parameter'
                ),
                'response' => null
            );
        }
        else{
            $requested_param = new UrlInformation();

            // URL log
            $url = $client_request['url'];
            $ip_address = $client_request['ip_address'];
            $user_id =Encryption::decodeId($client_request['user_id']);
            $project_code = $client_request['project'];
            $message = $client_request['message'];


            $prev_url = UrlInformation::where('user_id', $user_id)
                ->orderBy('id', 'DESC')
                ->first();

            if (count($prev_url) != 0){
                // store time duration in Hour:Minute format. Ex - 01:02 (1 hour 2 minute). second is not stored
                $time_diff = (new Carbon(date('Y:m:d H:i:s', time())))->diff(new Carbon($prev_url->in_time))->format('%h:%I');
                $update_out_time = UrlInformation::where('id', $prev_url->id)->update([
                    'out_time'=>date('Y:m:d H:i:s', time()),
                    'duration'=>$time_diff
                ]);
            }

            $url_info = UrlInformation::create([
                'url'=> $url,
                'ip_address'=> $ip_address,
                'project_code'=> $project_code,
                'message'=>$message,
                'in_time'=>date('Y:m:d H:i:s', time()),
                'user_id' => $user_id
            ]);

            $response = array( // Response for valid request
                'status' => 200,
                'success' => true,
                'error' => null,
                'response' => array(
                    'requested_param' => $requested_param
                )
            );
            http://localhost:8000/api/new-job?requestData={%22data%22:{%22project%22:%22beza%22,%20%22user_id%22:%2211%22,%22url%22:%22localhost://111.com%22,%22method%22:%22post%22}}

        }
        return \GuzzleHttp\json_encode($response);
    }

    public function actionNewJob(Request $request)
    {
        $client_request = $request->all();

        $response = array();

        if ($client_request == '' || $client_request == null || !isset($client_request)){
            $response = array( // Response for invalid request
                'status' => 400,
                'success' => false,
                'error' => array(
                    'code' => 'EQR101',
                    'message' => 'Invalid Request or Parameter'
                ),
                'response' => null
            );
        }
        else{
            $requested_param = new UrlInformation();
            // URL log
            $url = $client_request['url'];
            $ip_address = $client_request['ip_address'];
            $action =trim($client_request['action']);
            $user_id =Encryption::decodeId($client_request['user_id']);
            $project_code = $client_request['project'];
            $message = $client_request['message'];

            $url_info = ActionInformation::create([
                'url'=> $url,
                'action'=> $action,
                'ip_address'=> $ip_address,
                'project_code'=> $project_code,
                'message'=>$message,
                'user_id' => $user_id
            ]);

            $response = array( // Response for valid request
                'status' => 200,
                'success' => true,
                'error' => null,
                'response' => array(
                    'requested_param' => $requested_param
                )
            );
            http://localhost:8000/api/new-job?requestData={%22data%22:{%22project%22:%22beza%22,%20%22user_id%22:%2211%22,%22url%22:%22localhost://111.com%22,%22method%22:%22post%22}}
        }
        return \GuzzleHttp\json_encode($response);
    }


}
