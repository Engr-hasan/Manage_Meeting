<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<html lang="en" class="no-js">
    <head>
        <meta charset="utf-8"/>
        <title>..::{{env('PROJECT_NAME')}}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta content="" name="description"/>
        <meta content="" name="author"/>

        <link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}" />
        <link rel="stylesheet" href="{{ asset("assets/stylesheets/bootstrap-datetimepicker.css") }}" />
        <link rel="stylesheet" href="{{ asset("assets/stylesheets/custom.css") }}" />
        <link rel="stylesheet" href="{{ asset("assets/stylesheets/custom-front.css") }}" />
        <link rel="stylesheet" href="{{ asset("custom/css/custom.css") }}" />
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
        @yield('body')
        <!-- jQuery -->
        <script src="{{ asset("assets/scripts/jquery.min.js") }}" src="" type="text/javascript"></script>
        <script src="{{ asset("assets/scripts/custom.js") }}" src="" type="text/javascript"></script>
        <script src="{{ asset("assets/scripts/jquery.validate.js") }}"></script>
        <script src="{{ asset("assets/scripts/moment.js") }}"></script>
        <script src="{{ asset("assets/scripts/bootstrap-datetimepicker.js") }}"></script>
        <script type="text/javascript">
            var base_url = '{{url()}}';
        </script>
        <script src="{{ asset("assets/scripts/image-processing.js") }}"></script>
        @yield('footer-script')
    </body>
</html>