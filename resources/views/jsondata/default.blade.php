<?php
//                                echo $applicationInfo->json_object;
$data = json_decode($appInfo->json_object);
$image = '';
if(isset($data->image))
{
    $image = $data->image;
    unset($data->image);
}
?>
<img src="{{$image}}" alt="no image found">
{!! print_r($data) !!}