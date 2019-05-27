<?php

namespace App\Exceptions;

use App\Modules\Apps\Models\EmailQueue;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
//use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\URL;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (env('APP_TYPE', 'LOCAL') == 'LIVE') {
            $getCode = $e->getCode();
            $message = $e->getMessage();
            $getFile = $e->getFile();
            $getLine = $e->getLine();
            $getUrl = URL::current();

            $body_msg = '<span style="color:black;text-align:justify;"><b>';
            $body_msg .= '<b><h3>Error details:</h3><br>Date Time: </b>'.(new DateTime())->format("d-M-Y H:i:s").'<br>
            <b>Project Name: </b>'.env('PROJECT_NAME').'<br><b>Url: </b>'.$getUrl.'<br><b>Error Messages:</b> '.$message.'<br>
            <b>Error File:</b> '.$getFile.'<br><b>Error Line:</b> '.$getLine.'<br><b>Error Code: </b>'.$getCode.
            $body_msg .= '</span>';
            $body_msg .= '<br/><br/><br/>Thanks<br/>';
            $body_msg .= '<b>'.env('PROJECT_NAME').'</b>';

            $header = "Application errors";
            $param = $body_msg;
            $email_content = view("Users::message", compact('header', 'param'))->render();

            $emailQueue = new EmailQueue();
            $emailQueue->service_id = 0;
            $emailQueue->app_id = 0;
            $emailQueue->email_content = $email_content;
            $emailQueue->email_to = "imam.reyad93@gmail.com";
            $emailQueue->sms_to =  "";
            $emailQueue->email_subject = $header;
            $emailQueue->attachment = '';
            $emailQueue->save();

            return response()->view('errors.custom');
        }
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if ($e instanceof TokenMismatchException) {
            //redirect to form an example of how I handle mine
            return redirect()->back()->with('error', "Opps! Seems you couldn't submit form for a longtime. Please try again");
        }
        return parent::render($request, $e);
    }
}