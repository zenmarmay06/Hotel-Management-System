<?php
include '../config.php';

// Kuhaon ang ID gikan sa URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Kuhaon una ang Room Number sa maong booking
    $get_room = mysqli_query($conn, "SELECT NoofRoom FROM roombook WHERE id = '$id'");
    $row = mysqli_fetch_array($get_room);
    $room_no = $row['NoofRoom'];

    // 2. I-update ang status sa Booking ngadto sa 'Checked out'
    $update_booking = "UPDATE roombook SET stat = 'Checked out' WHERE id = '$id'";
    
    if (mysqli_query($conn, $update_booking)) {
        
        // 3. AUTOMATIC: I-update ang status sa Room ngadto sa 'Maintenance'
        $update_room = "UPDATE room SET status = 'Maintenance' WHERE room_no = '$room_no'";
        mysqli_query($conn, $update_room);

        // Human sa tanan, balik sa roombook.php
        header("Location: roombook.php");
    } else {
        echo "<script>alert('Error in Checkout process'); window.location.href='roombook.php';</script>";
    }
}
?>