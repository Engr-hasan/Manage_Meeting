@if(count($home_slider_image) > 0)

    <?php  $slider_interval = 4000; $i = 0; ?>
    @foreach($home_slider_image as $key => $sliderInterval)
        @if($key == 0)
           <?php  $slider_interval = $sliderInterval->slider_interval; ?>
        @endif
    @endforeach



    <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="{{ $slider_interval }}">
        <ol class="carousel-indicators">
            <?php for($j = 0; $j < count($home_slider_image); $j++){
            if($j == '0'){
            ?>
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <?php }else{  ?>
            <li data-target="#myCarousel" data-slide-to="<?php echo $j; ?>"></li>
            <?php } } ?>
        </ol>
        <div class="carousel-inner">
            <?php
            $i = 0;
            ?>
            @foreach($home_slider_image as $home_slider_image)
                @if($i == '0')
                    <div class="item active">
                        <img src="{{ $home_slider_image->slider_image }}" alt="{{ $home_slider_image->slider_title }}" style="width:100%; height: 270px;">
                        <div class="carousel-caption" style="margin-bottom: 155px">
                            <a target="_blank" href="{{ $home_slider_image->slider_url }}"> <h3 style="color: white">{{ $home_slider_image->slider_title }}</h3> </a>
                            <p>{!! $home_slider_image->description !!}</p>
                        </div>
                    </div>
                @else
                    <div class="item">
                        <img src="{{ $home_slider_image->slider_image }}" alt="{{ $home_slider_image->slider_title }}" style="width:100%; height: 270px;">
                        <div class="carousel-caption" style="margin-bottom: 155px ;">
                            <a target="_blank" href="{{ $home_slider_image->slider_url }}"> <h3 style="color: white">{{ $home_slider_image->slider_title }}</h3> </a>
                            <p>{!! $home_slider_image->description !!}  </p>
                        </div>
                    </div>
                @endif
                <?php $i++; ?>
            @endforeach
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="fa fa-arrow-left" style="margin-top: 120px;"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="fa fa-arrow-right" style="margin-top: 120px;"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

 @else

    <div id="myCarousel" class="carousel slide" data-ride="carousel">

        <div class="carousel-inner">

            <div class="item active">
                <img src="{{asset('/uploads/sliderImage/')}}/slider_not_found.jpg" alt="BEZA" style="width:100%; height: 270px;">
            </div>

        </div>

        <!-- Left and right controls -->

    </div>

    @endif


