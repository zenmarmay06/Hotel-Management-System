<?php
include '../config.php';

$id = $_GET['id'];

// --- MESSAGE FOR CONFIRMATION ---
if (!isset($_GET['confirm'])) {
    echo "<script>
            if (confirm('Are you sure you want to CONFIRM this booking?')) {
                window.location.href = 'roomconfirm.php?id=$id&confirm=true';
            } else {
                window.location.href = 'roombook.php';
            }
          </script>";
    exit();
}

$sql ="Select * from roombook where id = '$id'";
$re = mysqli_query($conn,$sql);
while($row=mysqli_fetch_array($re))
{
    $Name = $row['Name'];
    $Email = $row['Email'];
    $Country = $row['Country'];
    $Phone = $row['Phone'];
    $RoomType = $row['RoomType'];
    $Bed = $row['Bed'];
    $NoofRoom = $row['NoofRoom']; 
    $cin = $row['cin'];
    $cout = $row['cout'];
    $noofday = $row['nodays'];
    $stat = $row['stat'];
}

if($stat == "NotConfirm")
{
    $st = "Confirm";

    // 1. I-update ang status sa booking
    $sql = "UPDATE roombook SET stat = '$st' WHERE id = '$id'";
    $result = mysqli_query($conn,$sql);

    if($result){
        
        // 2. AUTOMATIC OCCUPIED LOGIC
        $sql_room = "UPDATE room SET status = 'Occupied' WHERE room_no = '$NoofRoom'";
        mysqli_query($conn, $sql_room);

        // --- PRICING LOGIC (ROOM & BED ONLY) ---
        $price = 0;
        if ($RoomType == "Superior Room") {
            if ($Bed == "Single") $price = 1000;
            else if ($Bed == "Double") $price = 2000;
            else if ($Bed == "Triple") $price = 2500;
            else if ($Bed == "Quad") $price = 3000;
        } 
        else if ($RoomType == "Deluxe Room") {
            if ($Bed == "Single") $price = 4000;
            else if ($Bed == "Double") $price = 4800;
            else if ($Bed == "Triple") $price = 4900;
            else if ($Bed == "Quad") $price = 5000;
        } 

        // --- CALCULATION: (Price x No of Days) ---
        $ttot = $price * $noofday; // Total Room Charge
        
        $fintot = $ttot;           // Final total is just the room total

        // 3. INSERT INTO PAYMENT
        // Note: Gi-maintain nako ang columns para dili mag-error ang database structure nimo
        $psql = "INSERT INTO payment(id,Name,Email,RoomType,Bed,NoofRoom,cin,cout,noofdays,roomtotal,bedtotal,meal,mealtotal,finaltotal) 
                 VALUES ('$id', '$Name', '$Email', '$RoomType', '$Bed', '$NoofRoom', '$cin', '$cout', '$noofday', '$ttot', '$btot', '$meal_type', '$mepr', '$fintot')";

        if(mysqli_query($conn, $psql)){
             header("Location:roombook.php");
        } else {
             echo "Error: " . mysqli_error($conn);
        }
    }
}
?>