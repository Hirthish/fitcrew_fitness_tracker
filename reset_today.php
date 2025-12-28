<?php
include "db.php";
$today = date("Y-m-d");

$conn->query("DELETE FROM calorie_intake WHERE intake_date='$today'");
$conn->query("DELETE FROM workout_log WHERE workout_date='$today'");

echo json_encode(["success" => true]);
