<?php
include "db.php";

$today = date("Y-m-d");

$sql = "
  SELECT COALESCE(SUM(calories), 0) AS total
  FROM calorie_intake
  WHERE intake_date = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode([
  "total" => (int)$row["total"]
]);
