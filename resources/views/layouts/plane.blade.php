<?php echo
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8"/>
        <title>{!!trans('messages.logo_title')!!}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <link rel="shortcut icon" type="image/png" href="{{ asset("assets/images/favicon.ico") }}"/>
        <link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}" />
        <link rel="stylesheet" href="{{ asset("assets/scripts/datatable/dataTables.bootstrap.min.css") }}" />
        <link rel="stylesheet" href="{{ asset("assets/scripts/datatable/responsive.bootstrap.min.css") }}" />
        <link rel="stylesheet" href="{{ asset("assets/stylesheets/bootstrap-datetimepicker.css") }}" />
        <link rel="stylesheet" href="{{ asset("assets/stylesheets/custom.css") }}" />
        <link rel="stylesheet" href="{{ asset("custom/css/custom.css") }}" />
        <link rel="stylesheet" href="{{ asset("assets/newsTicker/ticker-style.css") }}" />
        <link rel="stylesheet" href="{{ asset("assets/plugins/toastr.min.css") }}" />
        <style>

            legend{
                margin-bottom: 5px;
            }
            fieldset.scheduler-border {
                border: 1px solid #5f5f5f !important;
                padding: 0 1.1em 0.2em 1.1em !important;
                margin: 0 0 1.5em 0 !important;
                -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
            }

            legend.scheduler-border {
                font-size: 1.2em !important;
                font-weight: bold !important;
                text-align: left !important;
                width:auto;
                padding:0 10px;
                border-bottom:none;
            }
        </style>
        <noscript>Sorry, your browser does not support JavaScript!</noscript>
        <script src="{{ asset("assets/scripts/jquery.min.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/scripts/custom.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/amcharts/amcharts.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/amcharts/pie.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/amcharts/serial.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/amcharts/raphael-min.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/amcharts/morris-0.4.1.min.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/plugins/toastr.min.js") }}" type="text/javascript"></script>
        <?php
        use App\Modules\Settings\Models\Configuration;
        // for image file
        $IMAGE_DIMENSION = Configuration::where('caption','IMAGE_SIZE')->pluck('value');
        $IMAGE_SIZE = Configuration::where('caption','IMAGE_SIZE')->pluck('value2');

        // Image size
        $split_img_size = explode('-',$IMAGE_SIZE);;
        $IMAGE_MIN_SIZE = $split_img_size[0];
        $IMAGE_MAX_SIZE = $split_img_size[1];

        // image dimension
        $split_img_dimension = explode('x',$IMAGE_DIMENSION);
        $split_img_variation = explode('~',$split_img_dimension[1]);
        $IMAGE_WIDTH = $split_img_dimension[0];
        $IMAGE_HEIGHT = $split_img_variation[0];
        $IMAGE_DIMENSION_PERCENT = $split_img_variation[1];
        //========================= image config end =====================

        // for doc file
        $DOC_DIMENSION = Configuration::where('caption','DOC_IMAGE_SIZE')->pluck('value');
        $DOC_SIZE = Configuration::where('caption','DOC_IMAGE_SIZE')->pluck('value2');

        // Doc size
        $split_doc_size = explode('-',$DOC_SIZE);;
        $DOC_MIN_SIZE = $split_doc_size[0];
        $DOC_MAX_SIZE = $split_doc_size[1];

        // doc dimension
        $split_doc_dimension = explode('x',$DOC_DIMENSION);
        $split_doc_variation = explode('~',$split_doc_dimension[1]);
        $DOC_WIDTH = $split_doc_dimension[0];
        $DOC_HEIGHT = $split_doc_variation[0];
        $DOC_DIMENSION_PERCENT = $split_doc_variation[1];

        ?>
        <script type="text/javascript">
            var base_url ='{{url()}}';

            //Image Configuration
            var IMAGE_MIN_SIZE = '{{ $IMAGE_MIN_SIZE }}';
            var IMAGE_MAX_SIZE = '{{ $IMAGE_MAX_SIZE }}';
            var IMAGE_MIN_WIDTH = '{{ $IMAGE_WIDTH }}';
            var IMAGE_MAX_WIDTH = Math.floor('{{ $IMAGE_WIDTH+(($IMAGE_WIDTH*$IMAGE_DIMENSION_PERCENT)/100) }}');

            var IMAGE_MIN_HEIGHT = '{{ $IMAGE_HEIGHT }}';
            var IMAGE_MAX_HEIGHT = Math.floor('{{ $IMAGE_HEIGHT+(($IMAGE_HEIGHT*$IMAGE_DIMENSION_PERCENT)/100) }}');

            //Doc Configuration
            var DOC_MIN_SIZE = '{{ $DOC_MIN_SIZE }}';
            var DOC_MAX_SIZE = '{{ $DOC_MAX_SIZE }}';

            var DOC_MIN_WIDTH = '{{ $DOC_WIDTH }}';
            var DOC_MAX_WIDTH = Math.floor('{{ $DOC_WIDTH+(($DOC_WIDTH*$DOC_DIMENSION_PERCENT)/100) }}');

            var DOC_MIN_HEIGHT = '{{ $DOC_HEIGHT }}';
            var DOC_MAX_HEIGHT = Math.floor('{{ $DOC_HEIGHT+(($DOC_HEIGHT*$DOC_DIMENSION_PERCENT)/100) }}');

        </script>

    </head>
    <body>
    {{ csrf_field() }}
    @yield('body')
        <!-- jQuery -->
{{--        <script src="{{ asset("assets/scripts/jquery.min.js") }}" src="" type="text/javascript"></script>--}}
        <!-- Bootstrap Core JavaScript -->
        <script src="{{ asset("assets/scripts/bootstrap.min.js") }}" src="" type="text/javascript"></script>
        <!-- Metis Menu Plugin JavaScript -->
        <script src="{{ asset("assets/scripts/metis-menu.min.js") }}" src="" type="text/javascript"></script>
        <!-- Morris Charts JavaScript -->
        <script src="{{ asset("assets/scripts/raphael-min.js") }}" src="" type="text/javascript"></script>
        <script src="{{ asset("assets/scripts/metis-menu.min.js") }}"></script>
        <!-- Custom Theme JavaScript -->
        <script src="{{ asset("assets/scripts/sb-admin-2.js") }}" src="" type="text/javascript"></script>
        <script src="{{ asset("assets/scripts/jquery.validate.js") }}"></script>
        <script src="{{ asset("assets/scripts/moment.js") }}"></script>
        <script src="{{ asset("assets/scripts/bootstrap-datetimepicker.js") }}"></script>
        <script src="{{ asset("assets/scripts/image-processing.js") }}"></script>
        <script src="{{ asset("assets/amcharts/amcharts.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/amcharts/pie.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/amcharts/serial.js") }}" type="text/javascript"></script>
        @yield('footer-script')
        <script  type="text/javascript">
            $("input[type=text]:not([class*='textOnly'],[class*='email'],[class*='exam'],[class*='number'],[class*='bnEng'],[class*='textOnlyEng'],[class*='datepicker'],[class*='mobile_number_validation'])").addClass('engOnly');
            // tooltip demo
            $('.tooltip-demo').tooltip({
                selector: "[data-toggle=tooltip]",
                container: "body"
            })
            // popover demo
            $("[data-toggle=popover]")
                .popover()
        </script>
        @if(Auth::user())
            <script type="text/javascript">
                var setSession = '';
                function getSession(){
                    $.get("/users/get-user-session", function(data, status){
                        if(data.responseCode == 1) {
                            setSession = setTimeout(getSession, 6000);
                        }else{
                            alert('Your session has been closed. Please login again');
                            window.location.replace('/login');
                        }
                    });
                }
                setSession = setTimeout(getSession, 6000);
            </script>
        @endif
        <?php if(Auth::user()){
            $user_id =  \App\Libraries\Encryption::encodeId(Auth::user()->id);
        }
        else{
            $user_id = \App\Libraries\Encryption::encodeId(0);
        }

        if (isset($exception)){
            $message="Invalid Id! 401";
        }
        else{
            $message='Ok';
        }

        ?>

        {{-- url store script --}}
        <script type="text/javascript">
            var ip_address = '<?php echo $_SERVER['REMOTE_ADDR'];?>';
            var user_id = '<?php echo $user_id;?>';
            var message = '<?php echo $message;?>';
            var project_name = "OCPL_BASE."+"<?php echo env('SERVER_TYPE', 'unknown');?>";
        </script>

        <script src="{{ asset("assets/scripts/url-webservice.js") }}"></script>

    </body>
</html>