 <?php
        use App\Modules\Settings\Models\Configuration;
        extract(CommonFunction::getImageDocConfig());
     ?>
    <script type="text/javascript">
        var base_url ='{{url()}}';

        //Image Configuration
        var IMAGE_MIN_SIZE = '{{ $IMAGE_MIN_SIZE }}';
        var IMAGE_MAX_SIZE = '{{ $IMAGE_MAX_SIZE }}';

        var IMAGE_MIN_WIDTH = Math.floor('{{ $IMAGE_WIDTH-(($IMAGE_WIDTH*$IMAGE_DIMENSION_PERCENT)/100) }}');
        var IMAGE_MAX_WIDTH = Math.floor('{{ $IMAGE_WIDTH+(($IMAGE_WIDTH*$IMAGE_DIMENSION_PERCENT)/100) }}');

        var IMAGE_MIN_HEIGHT = Math.floor('{{ $IMAGE_HEIGHT-(($IMAGE_HEIGHT*$IMAGE_DIMENSION_PERCENT)/100) }}');
        var IMAGE_MAX_HEIGHT = Math.floor('{{ $IMAGE_HEIGHT+(($IMAGE_HEIGHT*$IMAGE_DIMENSION_PERCENT)/100) }}');

        //Doc Configuration
        var DOC_MIN_SIZE = '{{ $DOC_MIN_SIZE }}';
        var DOC_MAX_SIZE = '{{ $DOC_MAX_SIZE }}';

        var DOC_MIN_WIDTH = Math.floor('{{ $DOC_WIDTH-(($DOC_WIDTH*$DOC_DIMENSION_PERCENT)/100) }}')
        var DOC_MAX_WIDTH = Math.floor('{{ $DOC_WIDTH+(($DOC_WIDTH*$DOC_DIMENSION_PERCENT)/100) }}');

        var DOC_MIN_HEIGHT = Math.floor('{{ $DOC_HEIGHT-(($DOC_HEIGHT*$DOC_DIMENSION_PERCENT)/100) }}');
        var DOC_MAX_HEIGHT = Math.floor('{{ $DOC_HEIGHT+(($DOC_HEIGHT*$DOC_DIMENSION_PERCENT)/100) }}');
    </script>