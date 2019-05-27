<h1>hello world this is Agency Request info</h1>

<?php
//                                echo $applicationInfo->json_object;
$data = json_decode($applicationInfo->json_object);
$image = '';
if(isset($data->image))
{
    $image = $data->image;
    unset($data->image);
}
?>
<img src="{{$image}}" alt="no image found">
{!! print_r($data) !!}
