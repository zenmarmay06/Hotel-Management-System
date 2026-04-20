<?php
include '../config.php';

$sql = "SELECT * FROM room";
$re = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_array($re)) {
    $type = $row['type'];
    $status = $row['status']; 
    $roombox_class = "";

    // Pagpili og CSS class base sa Room Type
    if ($type == "Superior Room") $roombox_class = "roomboxsuperior";
    else if ($type == "Deluxe Room") $roombox_class = "roomboxdelux";
    else if ($type == "Guest House") $roombox_class = "roomboguest";
    else if ($type == "Single Room") $roombox_class = "roomboxsingle";

    // Pagpili sa color badge base sa real-time status
    $badge_class = "bg-available"; // Green default
    if($status == "Occupied") $badge_class = "bg-occupied"; // Red
    else if($status == "Maintenance") $badge_class = "bg-maintenance"; // Yellow

    echo "<div class='roombox $roombox_class'>
            <div class='text-center no-boder'>
                <i class='fa-solid fa-bed fa-4x mb-2'></i>
                <br>
                <span class='status-badge $badge_class'>$status</span>
                <h3>" . $row['room_no'] . "</h3> 
                <div class='mb-1'><b>" . $row['type'] . "</b></div>
                <div class='mb-1'>" . $row['bedding'] . "</div>
                
                <div class='mt-2'>";
                
    // Button para sa paglimpyo (mopakita ra kung Maintenance status)
    if($status == "Maintenance") {
        echo "<a href='room.php?clean_id=". $row['id'] ."'><button class='btn btn-warning btn-sm me-1'>Mark Cleaned</button></a>";
    }

    echo "      <a href='roomdelete.php?id=". $row['id'] ."'><button class='btn btn-danger btn-sm'>Delete</button></a>
                </div>
            </div>
        </div>";
}
?>