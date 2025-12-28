<?php
include "db.php";

$result = $conn->query(
  "SELECT DISTINCT food_name 
   FROM food_calories 
   ORDER BY food_name"
);

$foods = [];
while ($row = $result->fetch_assoc()) {
  $foods[] = $row;
}

echo json_encode($foods);
?>
