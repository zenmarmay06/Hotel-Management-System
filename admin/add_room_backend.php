<?php
include '../config.php';

if (isset($_POST['room_no'])) {
    $room_no = $_POST['room_no'];
    $typeofroom = $_POST['troom'];
    $typeofbed = $_POST['bed'];

    $sql = "INSERT INTO room(room_no, type, bedding, status) VALUES ('$room_no', '$typeofroom', '$typeofbed', 'Available')";
    
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>