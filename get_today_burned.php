<?php
include "db.php";

$today = date("Y-m-d");

$result = $conn->query(
  "SELECT SUM(calories) AS total FROM workout_log WHERE workout_date='$today'"
);

$row = $result->fetch_assoc();
echo json_encode(["total" => $row["total"] ?? 0]);
