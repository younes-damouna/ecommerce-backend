<?php
include("inc/connection.php");
require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;

$email = $_POST['email'];
$password = $_POST['password'];
$query = $mysqli->prepare('SELECT user_id,CONCAT(first_name," ",last_name) AS full_name, role_name AS user_type,password 
FROM users 
JOIN role_types AS rt ON users.role_type_id =rt.role_id
 WHERE email=?');
$query->bind_param('s', $email);
$query->execute();
$query->store_result();
$num_rows = $query->num_rows;
$query->bind_result($user_id, $full_name, $user_type, $hashed_password);
$query->fetch();



$response = [];
if ($num_rows == 0) {
    $response['status'] = 'user not found';
    echo json_encode($response);
} else {
    if (password_verify($password, $hashed_password)) {
        $key = "972gtrp92ugbfebf208r20rh2ofbnwep9fugf23ibf3oufb3phfef";
        $payload_array = [];
        $payload_array["user_id"] = $user_id;
        $payload_array["name"] = $full_name;
        $payload_array["usertype"] = $user_type;
        $payload_array["exp"] = time() + 3600;
        $payload = $payload_array;
        $response['status'] = 'logged in';
        $jwt = JWT::encode($payload, $key, 'HS256');
        $response['jwt'] = $jwt;
        echo json_encode($response);
    } else {
        $response['status'] = 'wrong credentials';
        echo json_encode($response);
    }
};
