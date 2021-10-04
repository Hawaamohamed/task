<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: *');

$data = file_get_contents('php://input');
$json_data = json_decode($data);

print_r($json_data);
 
