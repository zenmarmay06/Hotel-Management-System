<?php
include '../config.php';
header('Content-Type: application/json');

$work = $_GET['work'] ?? 'Cleaner';
$sql = "SELECT name FROM staff WHERE work = '$work'";
$result = mysqli_query($conn, $sql);

$staff = array();
if($result){
    while($row = mysqli_fetch_assoc($result)) {
        $staff[] = $row['name'];
    }
}
echo json_encode($staff);
?>