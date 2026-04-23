<?php
include '../config.php';

if (isset($_GET['id'])) {
    // 1. Secure the ID
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 2. Get booking details
    $get_info = mysqli_query($conn, "SELECT NoofRoom, RoomType FROM roombook WHERE id = '$id'");
    $row = mysqli_fetch_array($get_info);

    if ($row) {
        $room_no = $row['NoofRoom'];
        $room_type = $row['RoomType'];

        // 3. Update Booking status to 'Checked out'
        $update_booking = "UPDATE roombook SET stat = 'Checked out' WHERE id = '$id'";
        
        if (mysqli_query($conn, $update_booking)) {
            
            // 4. Update Room status to 'Maintenance'
            $update_room = "UPDATE room SET status = 'Maintenance' WHERE room_no = '$room_no'";
            mysqli_query($conn, $update_room);

            // 5. CREATE TASK in MySQL (bluebirdhotel.tasks)
            $task_title = "Checkout Cleaning: Room " . $room_no . " (" . $room_type . ")";
            $status = "Pending";

            // Updated SQL: Removed priority
            $sql_task = "INSERT INTO tasks (title, room_no, status) 
                         VALUES ('$task_title', '$room_no', '$status')";
            
            if (mysqli_query($conn, $sql_task)) {
                echo "<script>
                        alert('Checkout Successful! Room $room_no is now in Maintenance. Cleaning task added.');
                        window.location.href='roombook.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Guest checked out, but failed to create cleaning task: " . mysqli_error($conn) . "');
                        window.location.href='roombook.php';
                      </script>";
            }
        } else {
            echo "<script>alert('Error in Checkout process'); window.location.href='roombook.php';</script>";
        }
    } else {
        echo "<script>alert('Booking ID not found'); window.location.href='roombook.php';</script>";
    }
} else {
    header("Location: roombook.php");
    exit();
}
?>