<?php
include_once('../config.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? 0;

    if ($id > 0) {

        $sql = "DELETE FROM tasks WHERE Id='$id'";

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