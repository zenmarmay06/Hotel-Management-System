<?php
include_once('../config.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? 0;
    $status = $_POST['status'] ?? '';

    if ($id > 0 && !empty($status)) {

        $sql = "UPDATE tasks SET Status='$status' WHERE Id='$id'";

        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }

    }
}
?>