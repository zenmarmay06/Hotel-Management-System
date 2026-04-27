<?php
include_once('../config.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $roomNo = $_POST['roomNo'] ?? '';
    $priority = $_POST['priority'] ?? 'Medium';
    $assignedTo = $_POST['assignedTo'] ?? '';
    $dueDate = $_POST['dueDate'] ?? date('Y-m-d');
    $status = $_POST['status'] ?? 'Pending';
    $userId = $_POST['userId'] ?? 0;
    $note = $_POST['note'] ?? '';

    if (!empty($roomNo)) {

        $sql = "INSERT INTO tasks 
        (RoomNo, Priority, AssignedTo, DueDate, Status, UserId, Note)
        VALUES 
        ('$roomNo', '$priority', '$assignedTo', '$dueDate', '$status', '$userId', '$note')";

        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
        }

    } else {
        echo json_encode(["status" => "error", "message" => "RoomNo required"]);
    }
}
?>