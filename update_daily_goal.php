<?php
include "db.php";

if (!isset($_POST['goal'])) {
  echo json_encode(["status" => "error", "msg" => "Goal missing"]);
  exit;
}

$goal = (int)$_POST['goal'];

// single-user system (id = 1)
$stmt = $conn->prepare("
  UPDATE user_settings 
  SET daily_goal = ? 
  WHERE id = 1
");
$stmt->bind_param("i", $goal);
$stmt->execute();

echo json_encode([
  "status" => "success",
  "goal" => $goal
]);
