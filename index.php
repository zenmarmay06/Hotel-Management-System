<?php
include 'config.php';
session_start();

function prepareAndExecute($conn, $sql, $params)
{
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('mysqli error: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    return $stmt;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="./css/flash.css">
    <title>Hotel Blue Bird</title>
</head>

<body>
    <section id="carouselExampleControls" class="carousel slide carousel_section" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active"><img class="carousel-image" src="./image/hotel1.jpg"></div>
            <div class="carousel-item"><img class="carousel-image" src="./image/hotel2.jpg"></div>
            <div class="carousel-item"><img class="carousel-image" src="./image/hotel3.jpg"></div>
            <div class="carousel-item"><img class="carousel-image" src="./image/hotel4.jpg"></div>
        </div>
    </section>

    <section id="auth_section">
        <div class="logo">
            <img class="bluebirdlogo" src="./image/bluebirdlogo.png" alt="logo">
            <p>BLUEBIRD</p>
        </div>
        <div class="auth_container">
            <div id="Log_in">
                <h2>Log In</h2>
                <div class="role_btn">
                    <div class="btns active">User</div>
                    <div class="btns">Staff</div>
                </div>

                <?php
                if (isset($_POST['user_login_submit'])) {
                    $email = $_POST['Email'];
                    $password = $_POST['Password'];

                    // Pangitaon ang user base sa Email ra
                    $sql = "SELECT * FROM signup WHERE Email = ?";
                    $stmt = prepareAndExecute($conn, $sql, [$email]);
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $user = $result->fetch_assoc();
                        // I-verify ang gi-input nga password batok sa hash sa DB
                        if (password_verify($password, $user['Password'])) {
                            $_SESSION['usermail'] = $email;
                            header("Location: home.php");
                            exit();
                        } else {
                            echo "<script>swal({ title: 'Invalid Password', icon: 'error' });</script>";
                        }
                    } else {
                        echo "<script>swal({ title: 'Account not found', icon: 'error' });</script>";
                    }
                }
                ?>
                <form class="user_login authsection active" id="userlogin" action="" method="POST">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="Email" placeholder=" " required>
                        <label>Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="Password" placeholder=" " required>
                        <label>Password</label>
                    </div>
                    <button type="submit" name="user_login_submit" class="auth_btn">Log in</button>
                    <div class="footer_line">
                        <h6>Don't have an account? <span class="page_move_btn" onclick="signuppage()">sign up</span></h6>
                    </div>
                </form>

                <?php
                // Employee Login Logic
if (isset($_POST['Emp_login_submit'])) {
    $email = $_POST['Emp_Email'];
    $password = $_POST['Emp_Password']; // Ang gi-type nga "1234"

    // Kuhaon ang record base sa email ra
    $sql = "SELECT * FROM emp_login WHERE Emp_Email = ?";
    $stmt = prepareAndExecute($conn, $sql, [$email]);
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $emp = $result->fetch_assoc();
        
        // I-verify ang hash
        if (password_verify($password, $emp['Emp_Password'])) {
            $_SESSION['usermail'] = $email;
            header("Location: admin/admin.php");
            exit();
        } else {
            echo "<script>swal({ title: 'Invalid Password', icon: 'error' });</script>";
        }
    } else {
        echo "<script>swal({ title: 'Staff account not found', icon: 'error' });</script>";
    }
}
                ?>
                <form class="employee_login authsection" id="employeelogin" action="" method="POST">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="Emp_Email" placeholder=" " required>
                        <label>Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="Emp_Password" placeholder=" " required>
                        <label>Password</label>
                    </div>
                    <button type="submit" name="Emp_login_submit" class="auth_btn">Log in</button>
                </form>
            </div>

            <?php
            if (isset($_POST['user_signup_submit'])) {
                $username = $_POST['Username'];
                $email = $_POST['Email'];
                $password = $_POST['Password'];
                $cpassword = $_POST['CPassword'];

                if ($username == "" || $email == "" || $password == "") {
                    echo "<script>swal({ title: 'Fill the proper details', icon: 'error' });</script>";
                } else if ($password !== $cpassword) {
                    echo "<script>swal({ title: 'Password does not match', icon: 'error' });</script>";
                } else {
                    // Check if email exists
                    $sql_check = "SELECT * FROM signup WHERE Email = ?";
                    $stmt_check = prepareAndExecute($conn, $sql_check, [$email]);
                    if ($stmt_check->get_result()->num_rows > 0) {
                        echo "<script>swal({ title: 'Email already exists', icon: 'error' });</script>";
                    } else {
                        // I-HASH ANG PASSWORD
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        $sql_insert = "INSERT INTO signup (Username, Email, Password) VALUES (?, ?, ?)";
                        $stmt_insert = prepareAndExecute($conn, $sql_insert, [$username, $email, $hashed_password]);

                        if ($stmt_insert->affected_rows > 0) {
                            $_SESSION['usermail'] = $email;
                            header("Location: home.php");
                            exit();
                        }
                    }
                }
            }
            ?>
            <div id="sign_up" style="display:none;">
                <h2>Sign Up</h2>
                <form class="user_signup" id="usersignup" action="" method="POST">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" name="Username" placeholder=" " required>
                        <label>Username</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="email" class="form-control" name="Email" placeholder=" " required>
                        <label>Email</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="password" class="form-control" name="Password" placeholder=" " required>
                        <label>Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="CPassword" placeholder=" " required>
                        <label>Confirm Password</label>
                    </div>
                    <button type="submit" name="user_signup_submit" class="auth_btn">Sign up</button>
                    <div class="footer_line">
                        <h6>Already have an account? <span class="page_move_btn" onclick="loginpage()">Log in</span></h6>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="./javascript/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
        function signuppage() {
            document.getElementById('Log_in').style.display = 'none';
            document.getElementById('sign_up').style.display = 'block';
        }
        function loginpage() {
            document.getElementById('sign_up').style.display = 'none';
            document.getElementById('Log_in').style.display = 'block';
        }
    </script>
</body>
</html>