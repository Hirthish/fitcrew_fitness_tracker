<?php
include "db.php";

header("Content-Type: application/json");

// ✅ Allow only POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  echo json_encode(["status" => "error", "msg" => "Invalid request method"]);
  exit;
}

// ✅ Read & validate inputs
$food = trim($_POST['food'] ?? '');
$grams = (int) ($_POST['grams'] ?? 0);
$calories = (int) ($_POST['calories'] ?? 0);
$date = date("Y-m-d");

if ($food === "" || $grams <= 0 || $calories <= 0) {
  echo json_encode(["status" => "error", "msg" => "Invalid input data"]);
  exit;
}

// ✅ Insert into DB
$stmt = $conn->prepare(
  "INSERT INTO calorie_intake (food_name, grams, calories, intake_date)
   VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("siis", $food, $grams, $calories, $date);

if ($stmt->execute()) {
  echo json_encode(["status" => "success"]);
} else {
  echo json_encode([
    "status" => "error",
    "msg" => $stmt->error
  ]);
}
