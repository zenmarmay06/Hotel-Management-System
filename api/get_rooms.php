<?php
include '../config.php'; // Gigamit nimo ang config.php base sa imong screenshot
header('Content-Type: application/json');

$status = $_GET['status'] ?? 'Maintenance';
// Gamita ang saktong variable gikan sa config.php (pananglitan $con o $db)
$sql = "SELECT room_no FROM room WHERE status = '$status'"; 
$result = mysqli_query($conn, $sql);

$rooms = array();
if($result){
    while($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row['room_no'];
    }
}
echo json_encode($rooms);
?>