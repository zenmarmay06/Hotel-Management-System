<?php
include_once('../config.php'); // Naggamit og $conn variable
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomNo = $_POST['roomNo'] ?? '';
    $status = $_POST['status'] ?? '';

    if (!empty($roomNo) && !empty($status)) {
        // I-update ang MySQL table nga 'room'
        $sql = "UPDATE room SET status = '$status' WHERE room_no = '$roomNo'";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
        }
    }
}
?>