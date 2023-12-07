<?php
include("inc/connection.php");
require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;

$headers = getallheaders();
if (!isset($headers['Authorization']) || empty($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["error" => "unauthorized"]);
    exit();
}

$authorizationHeader = $headers['Authorization'];
$token = null;

$token = trim(str_replace("Bearer", '', $authorizationHeader));
if (!$token) {
    http_response_code(401);
    echo json_encode(["error" => "unauthorized"]);
    exit();
}
try {
    $key = "972gtrp92ugbfebf208r20rh2ofbnwep9fugf23ibf3oufb3phfef";
    $decoded = JWT::decode($token, new Key($key, 'HS256'));
 
   $user_id=$decoded->user_id;
   $producT_id=$_POST['product_id'];
   $quantity=$_POST['quantity'];

   $cart_id=time();
 
    // print_r($decoded);
        // print_r($_POST);

    // exit();
    if ($decoded->usertype=="customer" ) {
        $query = $mysqli->prepare('INSERT INTO cart(`user_id`, `quantity`, `product_id`, `cart_id`) 
        VALUES (?,?,?,?)');
        $query->bind_param('ssii',$user_id,$quantity,$producT_id,$cart_id);

        $query->execute();
        // // $array = $query->get_result();
        $response = [];
        // $response["permissions"] = true;
        // while ($products = $array->fetch_assoc()) {
            $response["message"] = "success";
        // }
    } 

  else {

        $response = [];
        $response["permissions"] = false;
    }
    echo json_encode($response);
} catch (ExpiredException $e) {
    http_response_code(401);
    echo json_encode(["error" => "expired"]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token"]);
}
