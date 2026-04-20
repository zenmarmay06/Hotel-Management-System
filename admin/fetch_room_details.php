<?php
include '../config.php';

if(isset($_POST['room_type'])) {
    $type = mysqli_real_escape_string($conn, $_POST['room_type']);
    
    // Kuhaon ang mga Bedding Types nga naa ra sa maong Room Type
    $bed_sql = "SELECT DISTINCT bedding FROM room WHERE type = '$type'";
    $bed_res = mysqli_query($conn, $bed_sql);
    $beds = "<option value='' selected disabled>Select Bedding Type</option>";
    while($row = mysqli_fetch_array($bed_res)) {
        $beds .= "<option value='".$row['bedding']."'>".$row['bedding']."</option>";
    }

    // Kuhaon ang mga Room Number nga naa ra sa maong Room Type
    $room_sql = "SELECT room_no FROM room WHERE type = '$type'";
    $room_res = mysqli_query($conn, $room_sql);
    $rooms = "<option value='' selected disabled>Select Room No</option>";
    while($row = mysqli_fetch_array($room_res)) {
        $rooms .= "<option value='".$row['room_no']."'>".$row['room_no']."</option>";
    }

    echo json_encode(['beds' => $beds, 'rooms' => $rooms]);
}
?>