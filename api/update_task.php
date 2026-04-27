<?php
include_once('../config.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? 0;
    $roomNo = $_POST['roomNo'] ?? '';
    $priority = $_POST['priority'] ?? '';
    $assignedTo = $_POST['assignedTo'] ?? '';
    $dueDate = $_POST['dueDate'] ?? '';
    $status = $_POST['status'] ?? '';
    $note = $_POST['note'] ?? '';

    if ($id > 0) {

        $sql = "UPDATE tasks SET 
            RoomNo='$roomNo',
            Priority='$priority',
            AssignedTo='$assignedTo',
            DueDate='$dueDate',
            Status='$status',
            Note='$note'
            WHERE Id='$id'";

        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
        }

    } else {
        echo json_encode(["status" => "error", "message" => "Invalid ID"]);
    }
}
?>