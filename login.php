<?php
session_start();
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$q = $conn->prepare("SELECT id, password FROM users WHERE email=?");
$q->bind_param("s", $email);
$q->execute();
$user = $q->get_result()->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
  $_SESSION['user_id'] = $user['id'];
  echo json_encode(["status" => "success"]);
} else {
  echo json_encode(["status" => "error"]);
}
