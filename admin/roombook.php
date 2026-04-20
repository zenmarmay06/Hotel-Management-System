<?php
session_start();
include '../config.php';

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
    <div id="guestdetailpanel">
        <form action="" method="POST" class="guestdetailpanelform">
            <div class="head">
                <h3>RESERVATION</h3>
                <i class="fa-solid fa-circle-xmark" onclick="adduserclose()"></i>
            </div>
            <div class="middle">
                <div class="guestinfo">
                    <h4>Guest information</h4>
                    <input type="text" name="Name" placeholder="Enter Full name" required>
                    <input type="email" name="Email" placeholder="Enter Email" required>

                    <?php
                    $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
                    ?>

                    <select name="Country" class="selectinput" required>
                        <option value selected>Select your country</option>
                        <?php
                        foreach ($countries as $key => $value):
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
                echo "<script>swal({title: 'Fill the proper details', icon: 'error'});</script>";
            } else {
                $sta = "NotConfirm";
                $sql = "INSERT INTO roombook(Name,Email,Country,Phone,RoomType,Bed,NoofRoom,Meal,cin,cout,stat,nodays) 
                        VALUES ('$Name','$Email','$Country','$Phone','$RoomType','$Bed','$NoofRoom','$Meal','$cin','$cout','$sta',datediff('$cout','$cin'))";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo "<script>swal({title: 'Reservation successful', icon: 'success'});</script>";
                } else {
                    echo "<script>swal({title: 'Something went wrong', icon: 'error'});</script>";
                }
            }
        }
        ?>
    </div>

    <div class="searchsection">
        <input type="text" name="search_bar" id="search_bar" placeholder="search..." onkeyup="searchFun()">
        <button class="adduser" id="adduser" onclick="adduseropen()"><i class="fa-solid fa-bookmark"></i> Add</button>
        <form action="./exportdata.php" method="post">
            <button class="exportexcel" id="exportexcel" name="exportexcel" type="submit"><i class="fa-solid fa-file-arrow-down"></i></button>
        </form>
    </div>

    <div class="roombooktable" class="table-responsive-xl">
        <?php
        $roombooktablesql = "SELECT * FROM roombook";
        $roombookresult = mysqli_query($conn, $roombooktablesql);
        ?>
        <table class="table table-bordered" id="table-data">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Country</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Type of Room</th>
                    <th scope="col">Type of Bed</th>
                    <th scope="col">Room No</th>
                    <th scope="col">Meal</th>
                    <th scope="col">Check-In</th>
                    <th scope="col">Check-Out</th>
                    <th scope="col">No of Day</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="action">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                while ($res = mysqli_fetch_array($roombookresult)) {
                    $status = $res['stat'];
                ?>
                    <tr>
                        <td><?php echo $res['id'] ?></td>
                        <td><?php echo $res['Name'] ?></td>
                        <td><?php echo $res['Email'] ?></td>
                        <td><?php echo $res['Country'] ?></td>
                        <td><?php echo $res['Phone'] ?></td>
                        <td><?php echo $res['RoomType'] ?></td>
                        <td><?php echo $res['Bed'] ?></td>
                        <td><?php echo $res['NoofRoom'] ?></td>
                        <td><?php echo $res['Meal'] ?></td>
                        <td><?php echo $res['cin'] ?></td>
                        <td><?php echo $res['cout'] ?></td>
                        <td><?php echo $res['nodays'] ?></td>
                        <td><?php echo $res['stat'] ?></td>
                        <td class="action">
    <?php
    $status = $res['stat']; 

    if ($status !== "Checked out") {
        // Confirm Button
        if ($status == "NotConfirm") {
            echo "<a href='roomconfirm.php?id=" . $res['id'] . "'><button class='btn btn-success btn-sm'>Confirm</button></a> ";
        }

        // EDIT Button
        echo "<a href='roombookedit.php?id=" . $res['id'] . "'><button class='btn btn-primary btn-sm'>Edit</button></a> ";

        // BAG-O: CHECKOUT Button (mo-diretso sa checkout process)
        echo "<a href='roombookcheckout.php?id=" . $res['id'] . "' onclick='return confirm(\"Finalize check-out for this guest?\")'>
                <button class='btn btn-warning btn-sm'>Checkout</button>
              </a> ";

        // Delete Button
        echo "<a href='roombookdelete.php?id=" . $res['id'] . "'><button class='btn btn-danger btn-sm'>Delete</button></a>";
    } else {
        echo "<span class='badge bg-secondary' style='padding: 8px;'>No Action Available</span>";
    }
    ?>
</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function fetchDetails(roomType) {
            if (roomType == "") {
                $('#Bed').html('<option value="">Select Bedding Type</option>');
                $('#NoofRoom').html('<option value="">Select Room No</option>');
                return;
            }

            $.ajax({
                url: 'fetch_room_details.php', // Kinahanglan buhaton ni nimo nga file
                method: 'POST',
                data: {
                    room_type: roomType
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#Bed').html(data.beds);
                    $('#NoofRoom').html(data.rooms);
                }
            });
        }
    </script>
</body>
<script src="./javascript/roombook.js"></script>

</html>