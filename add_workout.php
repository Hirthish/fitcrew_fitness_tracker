<?php
include "db.php";

$type = $_POST['type'] ?? '';
$duration = (int)($_POST['duration'] ?? 0);
$calories = (int)($_POST['calories'] ?? 0);

if (!$type || $duration <= 0 || $calories <= 0) {
  echo json_encode(["status" => "error"]);
  exit;
}

$today = date("Y-m-d");

$stmt = $conn->prepare("
  INSERT INTO workout_log 
  (workout_type, duration, calories_burned, workout_date)
  VALUES (?, ?, ?, ?)
");
$stmt->bind_param("siis", $type, $duration, $calories, $today);
$stmt->execute();

echo json_encode(["status" => "success"]);
