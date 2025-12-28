<?php
header("Content-Type: application/json");

// DB file is in SAME folder
require_once "db.php";

$result = $conn->query("DELETE FROM calorie_intake");

if ($result) {
  echo json_encode(["status" => "success"]);
} else {
  echo json_encode([
    "status" => "error",
    "msg" => $conn->error
  ]);
}
exit;
