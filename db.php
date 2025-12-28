<?php
$conn = new mysqli("127.0.0.1", "root", "", "food_db");

if ($conn->connect_error) {
  die(json_encode([
    "status" => "error",
    "msg" => "DB connection failed"
  ]));
}
