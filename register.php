<?php
include("inc/connection.php");

$email = $_POST['email'];
$password = $_POST['password'];
$user_type =  $_POST['user_type'];;
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = $mysqli->prepare('INSERT INTO users(email,password,role_type_id,first_name,last_name) 
VALUES(?,?,?,?,?)');
$query->bind_param('ssiss', $email, $hashed_password, $user_type, $first_name, $last_name);
$query->execute();

$response = [];
$response["status"] = "Registration Successfull";

echo json_encode($response);
