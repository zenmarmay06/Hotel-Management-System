<?php
include_once('../config.php');
header('Content-Type: application/json');

$result = mysqli_query($conn, "SELECT * FROM tasks ORDER BY Id DESC");

$tasks = [];

while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row;
}

echo json_encode($tasks);
?>