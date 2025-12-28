<?php
include "db.php";

$food = $_GET['food'] ?? '';

$stmt = $conn->prepare(
  "SELECT calories_per_100g 
   FROM food_calories 
   WHERE LOWER(food_name) = LOWER(?) 
   LIMIT 1"
);

$stmt->bind_param("s", $food);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  echo json_encode([
    "calories" => (float)$row["calories_per_100g"]
  ]);
} else {
  echo json_encode([
    "calories" => 0
  ]);
}
