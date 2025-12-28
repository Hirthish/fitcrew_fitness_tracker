<?php
include "db.php";

$today = date("Y-m-d");

$sql = "SELECT food_name, grams, calories 
        FROM calorie_intake 
        WHERE intake_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode($data);
