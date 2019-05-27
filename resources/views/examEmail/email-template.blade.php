<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>{!! $header !!}</title>
        <style type="text/css">
            *{
                font-family: "Times New Roman", Times, serif;
            } 
        </style>
    </head>

    <body>
        <table width="80%" style="background-color:#DBEDF9;margin:0 auto; height:50px; border-radius: 4px;">
            <thead>
                <tr>
                    <td colspan="2" style="padding: 10px; border-bottom: 1px solid rgba(0, 102, 255, 0.21);">
                        <img style="margin-left: auto; margin-right: auto; display: block;" src="{{ URL::to('/assets/images/email-icon.png') }}"
                             width="80px" alt="OES"/>
                        <h4 style="text-align:center">Online Exam System</h4>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="margin-top: 20px; padding: 15px;">
                    </td>
                    <td style="margin-top: 20px; float: right;">
                        Date: {!! $dateNow !!}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="margin-top: 20px; padding: 15px;">
                        {!! $body_msg !!}
                        <br/><br/>
                    </td>
                </tr>

                <tr style="margin-top: 15px;">
                    <td colspan="2" style="padding: 1px; border-top: 1px solid rgba(0, 102, 255, 0.21);">
                        <h5 style="text-align:center">All right reserved by OES 2017.</h5>
                    </td>
                </tr>
            </tbody>  
        </table>
    </body>
</html>