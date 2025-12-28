<?php
include "db.php";
$today = date("Y-m-d");

/* 1️⃣ Get daily goal */
$goal = 2200;
$g = $conn->query("SELECT daily_goal FROM user_settings WHERE id=1");
if ($row = $g->fetch_assoc()) {
  $goal = (int)$row['daily_goal'];
}

/* 2️⃣ Calories consumed today */
$q1 = $conn->prepare("
  SELECT SUM(calories) AS total 
  FROM calorie_intake 
  WHERE intake_date = ?
");
$q1->bind_param("s", $today);
$q1->execute();
$consumed = (int)($q1->get_result()->fetch_assoc()['total'] ?? 0);

/* 3️⃣ Calories burned today */
$q2 = $conn->prepare("
  SELECT SUM(calories_burned) AS burned 
  FROM workout_log 
  WHERE workout_date = ?
");
$q2->bind_param("s", $today);
$q2->execute();
$burned = (int)($q2->get_result()->fetch_assoc()['burned'] ?? 0);

/* 4️⃣ Net calories */
$net = $consumed - $burned;

/* 5️⃣ Status */
if ($net < 0) {
  $status = "Deficit 🟢";
} elseif ($net <= 200) {
  $status = "Balanced ⚖️";
} else {
  $status = "Surplus 🔴";
}

echo json_encode([
  "goal" => $goal,
  "consumed" => $consumed,
  "burned" => $burned,
  "net" => $net,
  "status" => $status
]);
