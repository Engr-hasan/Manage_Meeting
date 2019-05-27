<?php
function getDataFromJson($json){
    $jsonDecoded = json_decode($json);
    $string = '';
    foreach ($jsonDecoded as $key=>$data) {
        $string .= $key .":".$data.', ';
    }
    return $string;
}