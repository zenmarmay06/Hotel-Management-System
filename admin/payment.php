<?php
    session_start();
    include '../config.php';

    // --- KINI NGA PARTE PARA SA AUTO-FETCH (AJAX) ---
    if (isset($_GET['action']) && $_GET['action'] == 'fetch_data') {
        $paymanttablesql = "SELECT * FROM payment ORDER BY id DESC";
        $paymantresult = mysqli_query($conn, $paymanttablesql);

        while ($res = mysqli_fetch_array($paymantresult)) {
            $roomType = $res['RoomType'];
            $bedType  = $res['Bed'];
            $reservationPrice = 0;

            if (strcasecmp($roomType, "Superior Room") == 0) {
                if ($bedType == "Single")      $reservationPrice = 1000;
                elseif ($bedType == "Double")  $reservationPrice = 2000;
                elseif ($bedType == "Triple")  $reservationPrice = 2500;
                elseif ($bedType == "Quad")    $reservationPrice = 3000;
            } 
            elseif (strcasecmp($roomType, "Deluxe Room") == 0) {
                if ($bedType == "Single")      $reservationPrice = 4000;
                elseif ($bedType == "Double")  $reservationPrice = 4800;
                elseif ($bedType == "Triple")  $reservationPrice = 4900;
                elseif ($bedType == "Quad")    $reservationPrice = 5000;
            } 
           

            $days = ($res['noofdays'] <= 0) ? 1 : $res['noofdays'];
            $totalBill = $reservationPrice * $days;

            echo "<tr>
                    <td>{$res['id']}</td>
                    <td>{$res['Name']}</td>
                    <td>{$res['RoomType']}</td>
                    <td>{$res['Bed']}</td>
                    <td>{$res['cin']}</td>
                    <td>{$res['cout']}</td>
                    <td>{$res['noofdays']}</td>
                    <td>{$res['NoofRoom']}</td>
                    <td class='fw-bold'>₱ " . number_format($reservationPrice, 2) . "</td>
                    <td class='text-success fw-bold'>₱ " . number_format($totalBill, 2) . "</td>
                    <td class='action'>
                        <a href='invoiceprint.php?id={$res['id']}' class='btn btn-primary btn-sm'><i class='fa-solid fa-print'></i> Print</a>
                        <a href='paymantdelete.php?id={$res['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Delete?\")'><i class='fa-solid fa-trash'></i> Delete</a>
                    </td>
                  </tr>";
        }
        exit; // Hunongon ang execution para dili ma-load ang tibuok HTML
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./css/roombook.css">
    <title>BlueBird - Admin</title>
</head>
<body>

    <div class="searchsection">
        <input type="text" id="search_bar" placeholder="Search customer name..." onkeyup="searchFun()">
    </div>

    <div class="roombooktable table-responsive-xl">
        <table class="table table-bordered table-striped" id="table-data">
            <thead class="table-dark">
                <tr>
                    <th>Id</th><th>Name</th><th>Room Type</th><th>Bed Type</th>
                    <th>Check In</th><th>Check Out</th><th>No. of Days</th><th>No. of Rooms</th>
                    <th>Reservation Price</th><th>Total Bill</th><th>Action</th>
                </tr>
            </thead>
            <tbody id="show_data">
                </tbody>
        </table>
    </div>

    <script>
        // FUNCTION PARA I-FETCH ANG DATA
        function fetchData() {
            let filter = document.getElementById('search_bar').value;
            // Dili nato i-refresh kung naay sulod ang search bar para dili ma-interrupt ang admin
            if (filter === "") {
                $.ajax({
                    url: "payment.php?action=fetch_data",
                    type: "GET",
                    success: function(data) {
                        $('#show_data').html(data);
                    }
                });
            }
        }

        // LOAD DAYON INAG ABRI SA PAGE
        $(document).ready(function() {
            fetchData();
            // MAG-REFRESH MATAG 3 KA SEGUNDO (Polling)
            setInterval(fetchData, 3000);
        });

        // SEARCH FUNCTION
        function searchFun() {
            let filter = document.getElementById('search_bar').value.toUpperCase();
            let tr = document.getElementById("show_data").getElementsByTagName('tr');
            for (let i = 0; i < tr.length; i++) {
                let tdName = tr[i].getElementsByTagName('td')[1];
                if (tdName) {
                    let textvalue = tdName.textContent || tdName.innerText;
                    tr[i].style.display = (textvalue.toUpperCase().indexOf(filter) > -1) ? "" : "none";
                }
            }
        }
    </script>
</body>
</html>