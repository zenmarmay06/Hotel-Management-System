<?php
include '../config.php';

if(isset($_POST['room_type'])) {
    $type = mysqli_real_escape_string($conn, $_POST['room_type']);
    
    // SQL Logic:
    // 1. Kinahanglan ang room status sa 'room' table kay 'Available' (dili Maintenance)
    // 2. Kinahanglan ang room_no wala sa 'roombook' table nga active (dili Occupied)
    $sql = "SELECT room_no, bedding FROM room 
            WHERE type = '$type' 
            AND status = 'Available' 
            AND room_no NOT IN (
                SELECT NoofRoom FROM roombook 
                WHERE stat = 'Confirm' OR stat = 'NotConfirm'
            )";
    
    $res = mysqli_query($conn, $sql);
    
    $beds_options = "<option value='' selected disabled>Select Bedding Type</option>";
    $rooms_options = "<option value='' selected disabled>Select Room No</option>";
    
    $unique_beds = array();
    $found = false;

    while($row = mysqli_fetch_array($res)) {
        $found = true;
        $rooms_options .= "<option value='".$row['room_no']."'>".$row['room_no']."</option>";
        
        if(!in_array($row['bedding'], $unique_beds)) {
            $unique_beds[] = $row['bedding'];
            $beds_options .= "<option value='".$row['bedding']."'>".$row['bedding']."</option>";
        }
    }

    if(!$found) {
        $rooms_options = "<option value='' selected disabled>No Rooms Available</option>";
        $beds_options = "<option value='' selected disabled>N/A</option>";
    }

    echo json_encode(['beds' => $beds_options, 'rooms' => $rooms_options]);
}
?>