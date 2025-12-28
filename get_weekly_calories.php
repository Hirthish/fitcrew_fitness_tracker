<?php
include "db.php";

$sql = "
SELECT 
  intake_date,
  SUM(calories) AS total_calories
FROM calorie_intake
WHERE intake_date >= CURDATE() - INTERVAL 6 DAY
GROUP BY intake_date
ORDER BY intake_date ASC
";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode($data);
