<?php
include "db.php";

$result = $conn->query("
  SELECT workout_date, SUM(calories) AS total_calories
  FROM workout_log
  WHERE workout_date >= CURDATE() - INTERVAL 6 DAY
  GROUP BY workout_date
  ORDER BY workout_date DESC
");

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode($data);
