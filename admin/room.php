<?php
session_start();
include '../config.php';

// Logic para mabalik ang room sa Available status human malimpyuhan
if(isset($_GET['clean_id'])) {
    $id = $_GET['clean_id'];
    $sql = "UPDATE room SET status='Available' WHERE id='$id'";
    if(mysqli_query($conn, $sql)) {
        header("Location: room.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlueBird - Admin Room Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/room.css">
    <style>
    .room {
        display: flex;
        flex-wrap: wrap;
        gap: 30px; /* Mas dako nga spacing tali sa mga boxes */
        justify-content: center;
        padding: 40px;
    }

    .roombox { 
        transition: 0.3s; 
        width: 280px; /* Gidaku ang Width gikan sa 220px */
        min-height: 380px; /* Gidaku ang Height para taas og lugar */
        padding: 30px; 
        border-radius: 25px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        background-color: #fff; /* Siguradong naay background */
    }

    /* Icon size */
    .roombox i {
        font-size: 5rem; /* Mas dako nga icon */
        margin-bottom: 20px;
    }

    /* Status Badge Adjustment */
    .status-badge {
        display: inline-block;
        width: 85%;            /* Gipadaku ang gilapdon sa badge */
        padding: 10px 15px;    /* Gidugangan ang padding para mas dako tan-awon */
        border-radius: 50px;   /* Pill-shaped design */
        font-size: 0.95rem;    /* Mas klaro nga font size */
        font-weight: 800;      /* Mas bagal nga text */
        text-align: center;
        text-transform: uppercase; /* Himoon nga ALL CAPS para professional */
        letter-spacing: 1.5px;  /* Hatagan og space ang matag letra */
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Gamay nga shadow para mo-pop */
    }

    /* Room Number & Text Details */
    .roombox h3 {
        font-size: 2.2rem; /* Dako nga Room Number */
        font-weight: 800;
        margin-bottom: 10px;
    }

    .roombox b {
        font-size: 1.3rem; /* Dako nga Room Type */
        margin-bottom: 5px;
        display: block;
    }

    .room-bedding {
        font-size: 1.1rem;
        margin-bottom: 25px;
        color: #555;
    }

    /* Container para sa Buttons sa pinaka-ubos */
    .room-actions {
        margin-top: auto; 
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-custom {
        width: 100%;
        padding: 12px;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 12px;
        border: none;
        transition: 0.2s;
    }

    .btn-custom:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }

    /* Hover effects para sa tibuok box */
    .roombox:hover { 
        transform: translateY(-10px); 
        box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
    }

    /* Colors for Status */
    .bg-available { 
        background-color: #28a745 !important; 
        color: white !important; 
    }
    .bg-occupied { 
        background-color: #dc3545 !important; 
        color: white !important; 
    }
    .bg-maintenance { 
        background-color: #ffc107 !important; 
        color: #000 !important; 
    }

    /* Siguraduhon nga ang text sa sulod sa box dili mag-overlap */
    .roombox {
        padding-top: 25px;
        padding-bottom: 25px;
    }
</style>
</head>

<body>
    <div class="addroomsection">
        <form id="addRoomForm">
            <label for="room_no">Room Number :</label>
            <input type="text" name="room_no" id="room_no" class="form-control" placeholder="e.g. 101" required>

            <label for="troom">Type of Room :</label>
            <select name="troom" id="troom" class="form-control" required>
                <option value selected disabled>Select Room Type</option>
                <option value="Superior Room">SUPERIOR ROOM</option>
                <option value="Deluxe Room">DELUXE ROOM</option>
                <option value="Guest House">GUEST HOUSE</option>
                <option value="Single Room">SINGLE ROOM</option>
            </select>

            <label for="bed">Type of Bed :</label>
            <select name="bed" id="bed" class="form-control" required>
                <option value selected disabled>Select Bedding</option>
                <option value="Single">Single</option>
                <option value="Double">Double</option>
                <option value="Triple">Triple</option>
                <option value="Quad">Quad</option>
                <option value="None">None</option>
            </select>
            <br>
            <button type="submit" class="btn btn-success">Add Room</button>
        </form>
    </div>

    <div class="room" id="room_data">
        </div>

    <script>
        // 1. Function para i-load ang data (Auto-refresh)
        function fetchRoomData() {
            $.ajax({
                url: "display_rooms.php",
                type: "GET",
                success: function(data) {
                    $('#room_data').html(data);
                }
            });
        }

        $(document).ready(function() {
            fetchRoomData(); 
            setInterval(fetchRoomData, 2000); 

            // 2. AJAX para sa pag-Add og Room (Para dili mawala ang display)
            $('#addRoomForm').on('submit', function(e) {
                e.preventDefault(); // Pugngan ang page reload

                $.ajax({
                    url: 'add_room_backend.php', // Maghimo ta ani nga file sa ubos
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if(response == "success") {
                            alert('Room Added Successfully');
                            $('#addRoomForm')[0].reset(); // Limpyohan ang form fields
                            fetchRoomData(); // I-update ang listahan dayon
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>