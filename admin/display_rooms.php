<?php
include '../config.php';

$sql = "SELECT * FROM room";
$re = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_array($re)) {
    $type = $row['type'];
    $bedding = $row['bedding'];
    $status = $row['status']; 
    $roombox_class = "";
    $price = 0;

    // --- 1. PRICING LOGIC ---
    if ($type == "Superior Room") {
        if ($bedding == "Single") $price = 1000;
        else if ($bedding == "Double") $price = 2000;
        else if ($bedding == "Triple") $price = 2500;
        else if ($bedding == "Quad") $price = 3000;
    } 
    else if ($type == "Deluxe Room") {
        if ($bedding == "Single") $price = 4000;
        else if ($bedding == "Double") $price = 4800;
        else if ($bedding == "Triple") $price = 4900;
        else if ($bedding == "Quad") $price = 5000;
    } 
    

    // --- 2. CSS CLASS LOGIC ---
    if ($type == "Superior Room") $roombox_class = "roomboxsuperior";
    else if ($type == "Deluxe Room") $roombox_class = "roomboxdelux";
    
    else if ($type == "Single Room") $roombox_class = "roomboxsingle";

    // Pagpili sa color badge base sa real-time status
    $badge_class = "bg-available"; 
    if($status == "Occupied") $badge_class = "bg-occupied"; 
    else if($status == "Maintenance") $badge_class = "bg-maintenance"; 

    echo "<div class='roombox $roombox_class'>
            <div class='text-center no-boder'>
                <i class='fa-solid fa-bed fa-4x mb-2'></i>
                <br>
                <span class='status-badge $badge_class'>$status</span>
                
                <h3>" . $row['room_no'] . "</h3> 

                <div style='font-size: 1.3rem; font-weight: 800; color: #198754; margin-bottom: 5px;'>
                    ₱ " . number_format($price, 2) . "
                </div>

                <div class='mb-1'><b>" . $row['type'] . "</b></div>
                <div class='mb-1'>" . $row['bedding'] . " Bed</div>
                
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