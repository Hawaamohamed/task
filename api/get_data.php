<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: *');


include_once("../include/connect.php");
include_once("../include/function.php");

$data = getElements("users","");
$json_data = json_encode($data);
 
print_r($json_data);
