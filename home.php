<?php
include 'config.php';
session_start();

// page redirect
$usermail = "";
if (isset($_SESSION['usermail'])) {
    $usermail = $_SESSION['usermail'];
} else {
    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/home.css">
    <title>Hotel Blue Bird</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./admin/css/roombook.css">
    <style>
        #guestdetailpanel {
            display: none;
        }
        #guestdetailpanel .middle {
            height: 450px;
            overflow-y: auto;
        }
        /* Gamay nga style para sa table badges */
        .badge { padding: 8px 12px; border-radius: 5px; }
    </style>
</head>

<body>
    <nav>
        <div class="logo">
            <img class="bluebirdlogo" src="./image/bluebirdlogo.png" alt="logo">
            <p>BLUEBIRD</p>
        </div>
        <ul>
            <li><a href="#firstsection">Home</a></li>
            <li><a href="#secondsection">Rooms</a></li>
            <li><a href="#thirdsection">View Booking</a></li>
            <li><a href="#contactus">Contact Us</a></li>
            <a href="./logout.php"><button class="btn btn-danger">Logout</button></a>
        </ul>
    </nav>

    <section id="firstsection" class="carousel slide carousel_section" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active"><img class="carousel-image" src="./image/hotel1.jpg"></div>
            <div class="carousel-item"><img class="carousel-image" src="./image/hotel2.jpg"></div>
            <div class="carousel-item"><img class="carousel-image" src="./image/hotel3.jpg"></div>
            <div class="carousel-item"><img class="carousel-image" src="./image/hotel4.jpg"></div>

            <div class="welcomeline">
                <h1 class="welcometag">Welcome to heaven on earth</h1>
            </div>

            <div id="guestdetailpanel">
                <form action="" method="POST" class="guestdetailpanelform">
                    <div class="head">
                        <h3>RESERVATION</h3>
                        <i class="fa-solid fa-circle-xmark" onclick="closebox()"></i>
                    </div>
                    <div class="middle">
                        <div class="guestinfo">
                            <h4>Guest information</h4>
                            <input type="text" name="Name" placeholder="Enter Full name" required>
                            <input type="email" name="Email" value="<?php echo $usermail; ?>" readonly required>

                            <?php
                            $countries = array("Philippines", "Afghanistan", "Albania", "Algeria", "United Kingdom", "United States", "Vietnam"); 
                            ?>

                            <select name="Country" class="selectinput" required>
                                <option value selected disabled>Select your country</option>
                                <?php
                                foreach ($countries as $value):
                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                endforeach;
                                ?>
                            </select>
                            <input type="text" name="Phone" placeholder="Enter Phoneno" required>
                        </div>

                        <div class="line"></div>

                        <div class="reservationinfo">
                            <h4>Reservation information</h4>
                            <select name="RoomType" id="RoomType" class="selectinput" onchange="fetchDetails(this.value)" required>
                                <option value selected disabled>Type Of Room</option>
                                <option value="Superior Room">SUPERIOR ROOM</option>
                                <option value="Deluxe Room">DELUXE ROOM</option>
                                <option value="Guest House">GUEST HOUSE</option>
                                <option value="Single Room">SINGLE ROOM</option>
                            </select>

                            <select name="Bed" id="Bed" class="selectinput" required>
                                <option value selected disabled>Bedding Type</option>
                            </select>

                            <select name="NoofRoom" id="NoofRoom" class="selectinput" required>
                                <option value selected disabled>Select Room No</option>
                            </select>

                            <select name="Meal" class="selectinput" required>
                                <option value selected disabled>Meal</option>
                                <option value="Room only">Room only</option>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Half Board">Half Board</option>
                                <option value="Full Board">Full Board</option>
                            </select>
                            <div class="datesection">
                                <span>
                                    <label for="cin"> Check-In</label>
                                    <input name="cin" type="date" required>
                                </span>
                                <span>
                                    <label for="cout"> Check-Out</label>
                                    <input name="cout" type="date" required>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <button class="btn btn-success" name="guestdetailsubmit">Submit</button>
                    </div>
                </form>

                <?php
                if (isset($_POST['guestdetailsubmit'])) {
                    $Name = $_POST['Name'];
                    $Email = $_POST['Email'];
                    $Country = $_POST['Country'];
                    $Phone = $_POST['Phone'];
                    $RoomType = $_POST['RoomType'];
                    $Bed = $_POST['Bed'];
                    $NoofRoom = $_POST['NoofRoom'];
                    $Meal = $_POST['Meal'];
                    $cin = $_POST['cin'];
                    $cout = $_POST['cout'];

                    if ($Name == "" || $Email == "" || $Country == "") {
                        echo "<script>swal({ title: 'Fill the proper details', icon: 'error' });</script>";
                    } else {
                        $sta = "NotConfirm";
                        $sql = "INSERT INTO roombook(Name,Email,Country,Phone,RoomType,Bed,NoofRoom,Meal,cin,cout,stat,nodays) 
                                VALUES ('$Name','$Email','$Country','$Phone','$RoomType','$Bed','$NoofRoom','$Meal','$cin','$cout','$sta',datediff('$cout','$cin'))";
                        $result = mysqli_query($conn, $sql);

                        if ($result) {
                            echo "<script>
                                    swal({ title: 'Reservation successful', icon: 'success' })
                                    .then(() => { window.location.href = 'home.php#thirdsection'; });
                                  </script>";
                        } else {
                            echo "<script>swal({ title: 'Something went wrong', icon: 'error' });</script>";
                        }
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <section id="secondsection">
        <img src="./image/homeanimatebg.svg">
        <div class="ourroom">
            <h1 class="head">≼ Our room ≽</h1>
            <div class="roomselect">
                <?php
                // Display lang ang types nga naay Available status ug wala pa na-book
                $sql_available_types = "SELECT DISTINCT type FROM room 
                                        WHERE status = 'Available' 
                                        AND room_no NOT IN (
                                            SELECT NoofRoom FROM roombook 
                                            WHERE stat = 'Confirm' OR stat = 'NotConfirm'
                                        )";
                $res_types = mysqli_query($conn, $sql_available_types);

                if (mysqli_num_rows($res_types) > 0) {
                    while ($row = mysqli_fetch_assoc($res_types)) {
                        $roomType = $row['type'];
                        $imageClass = "h1"; 
                        if (strpos($roomType, 'Deluxe') !== false) $imageClass = "h2";
                        if (strpos($roomType, 'Guest') !== false) $imageClass = "h3";
                        if (strpos($roomType, 'Single') !== false) $imageClass = "h4";
                ?>
                        <div class="roombox">
                            <div class="hotelphoto <?php echo $imageClass; ?>"></div>
                            <div class="roomdata">
                                <h2><?php echo $roomType; ?></h2>
                                <div class="services">
                                    <i class="fa-solid fa-wifi"></i><i class="fa-solid fa-burger"></i><i class="fa-solid fa-spa"></i>
                                </div>
                                <button class="btn btn-primary bookbtn" onclick="openAndSelect('<?php echo $roomType; ?>')">Book</button>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<h3 class='text-center w-100'>No rooms available for booking at the moment.</h3>";
                }
                ?>
            </div>
        </div>
    </section>

    <section id="thirdsection" style="display: flex; flex-direction: column; align-items: center; padding: 50px 0;">
        <h1 class="head">≼ View Booking ≽</h1>
        <div class="table-responsive" style="background-color: white; padding: 20px; border-radius: 10px; width: 90%; box-shadow: 0px 4px 8px rgba(0,0,0,0.1);">
            <table class="table table-bordered table-striped mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>Room Type</th>
                        <th>Room No</th>
                        <th>Bed</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mopakita sa tanang bookings (Confirm, NotConfirm, Checked out) para sa logged-in user
                    $booking_sql = "SELECT * FROM roombook WHERE Email = '$usermail' ORDER BY id DESC";
                    $booking_result = mysqli_query($conn, $booking_sql);
                    if (mysqli_num_rows($booking_result) > 0) {
                        while ($row = mysqli_fetch_assoc($booking_result)) {
                            $status = $row['stat'];
                            
                            // Badge color logic
                            if($status == 'Confirm') { $badgeClass = 'bg-success'; }
                            else if($status == 'Checked out') { $badgeClass = 'bg-secondary'; }
                            else { $badgeClass = 'bg-warning text-dark'; }

                            echo "<tr>
                                    <td>" . $row['RoomType'] . "</td>
                                    <td>" . $row['NoofRoom'] . "</td>
                                    <td>" . $row['Bed'] . "</td>
                                    <td>" . $row['cin'] . "</td>
                                    <td>" . $row['cout'] . "</td>
                                    <td><span class='badge $badgeClass'>$status</span></td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>You have no booking history.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section> <section id="contactus" style="clear: both; width: 100%;">

    <div class="social">

      <i class="fa-brands fa-instagram"></i>

      <i class="fa-brands fa-facebook"></i>

      <i class="fa-solid fa-envelope"></i>

    </div>

    <div class="createdby">

      <h5>Created by @tushar</h5>

    </div>

  </section>
    <script>
        var bookbox = document.getElementById("guestdetailpanel");

        function openbookbox() {
            bookbox.style.display = "flex";
        }

        function closebox() {
            bookbox.style.display = "none";
        }

        function openAndSelect(roomType) {
            openbookbox();
            var selectElement = document.getElementById("RoomType");
            selectElement.value = roomType;
            fetchDetails(roomType);
        }

        function fetchDetails(roomType) {
            if (roomType == "") {
                $('#Bed').html('<option value="">Select Bedding Type</option>');
                $('#NoofRoom').html('<option value="">Select Room No</option>');
                return;
            }

            $.ajax({
                url: './admin/fetch_room_details.php',
                method: 'POST',
                data: { room_type: roomType },
                dataType: 'JSON',
                success: function(data) {
                    $('#Bed').html(data.beds);
                    $('#NoofRoom').html(data.rooms);
                }
            });
        }
    </script>
</body>
</html>