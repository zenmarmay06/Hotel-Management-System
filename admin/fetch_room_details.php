<?php
include '../config.php';

if(isset($_POST['room_type'])) {
    $type = mysqli_real_escape_string($conn, $_POST['room_type']);
    
    // STEP 1: Kung wala pa'y bed_type, i-fetch ang unique beds para sa maong Room Type
    if(!isset($_POST['bed_type'])) {
        $sql = "SELECT DISTINCT bedding FROM room 
                WHERE type = '$type' 
                AND status = 'Available' 
                AND room_no NOT IN (
                    SELECT NoofRoom FROM roombook 
                    WHERE stat = 'Confirm' OR stat = 'NotConfirm'
                )";
        
        $res = mysqli_query($conn, $sql);
        $beds_options = "<option value='' selected disabled>Select Bedding Type</option>";
        $found = false;

        while($row = mysqli_fetch_array($res)) {
            $found = true;
            $beds_options .= "<option value='".$row['bedding']."'>".$row['bedding']."</option>";
        }

        if(!$found) {
            $beds_options = "<option value='' selected disabled>No Bedding Available</option>";
        }
        echo json_encode(['beds' => $beds_options]);
        exit();
    }

    // STEP 2: Kung naa na'y bed_type, i-fetch ra ang mga Room No nga match sa Room Type UG Bedding
    if(isset($_POST['bed_type'])) {
        $bed = mysqli_real_escape_string($conn, $_POST['bed_type']);
        
        $sql = "SELECT room_no FROM room 
                WHERE type = '$type' 
                AND bedding = '$bed'
                AND status = 'Available' 
                AND room_no NOT IN (
                    SELECT NoofRoom FROM roombook 
                    WHERE stat = 'Confirm' OR stat = 'NotConfirm'
                )";
        
        $res = mysqli_query($conn, $sql);
        $rooms_options = "<option value='' selected disabled>Select Room No</option>";
        $found = false;

        while($row = mysqli_fetch_array($res)) {
            $found = true;
            $rooms_options .= "<option value='".$row['room_no']."'>".$row['room_no']."</option>";
        }

        if(!$found) {
            $rooms_options = "<option value='' selected disabled>No Rooms Available</option>";
        }
        echo json_encode(['rooms' => $rooms_options]);
        exit();
    }
}
?>