<?php
require 'pdo.php';

/* =========================
   CORS + JSON SETUP
========================= */
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

/* =========================
   REQUEST PARSING
========================= */
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', trim($uri, '/'));
$apiIndex = array_search('api', $segments);

$resource = ($apiIndex !== false && isset($segments[$apiIndex + 1])) ? $segments[$apiIndex + 1] : null;
$id = ($apiIndex !== false && isset($segments[$apiIndex + 2])) ? $segments[$apiIndex + 2] : null;
$sub = ($apiIndex !== false && isset($segments[$apiIndex + 3])) ? $segments[$apiIndex + 3] : null;

/* =========================
   ROUTER
========================= */
switch ($resource) {

    case "login":
        if ($method == "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $username = $data['username'] ?? '';
            $password = $data['password'] ?? '';

            $stmt = $pdo->prepare("SELECT * FROM users WHERE Username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['Password'])) {
                echo json_encode([
                    "id" => $user['Id'],
                    "name" => $user['Name'],
                    "username" => $user['Username'],
                    "role" => $user['Role']
                ]);
            } else {
                http_response_code(401);
                echo json_encode(["error" => "Invalid credentials"]);
            }
        }
        break;

    case "tasks":
        /* Kritikal: Ang GET tasks mokuha LANG sa data gikan sa tasks table.
           Dili kini mag-display og rooms nga wala pa na-create as task.
        */
        if ($method == "GET" && !$id) {
            // Gi-order by ID DESC para ang bag-ong add naa sa taas
            $stmt = $pdo->query("SELECT * FROM tasks ORDER BY Id DESC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }

        if ($method == "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $pdo->prepare("
                INSERT INTO tasks (RoomNo, Priority, AssignedTo, DueDate, Status,Note)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['roomNo'], $data['priority'], $data['assignedTo'],
                $data['dueDate'], $data['status'],  $data['note']
            ]);
            echo json_encode(["message" => "Task created"]);
        }

        if ($method == "PUT" && $id && !$sub) {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $pdo->prepare("
                UPDATE tasks
                SET RoomNo=?, Priority=?, AssignedTo=?, DueDate=?, Status=?, Note=?
                WHERE Id=?
            ");
            $stmt->execute([
                $data['roomNo'], $data['priority'], $data['assignedTo'],
                $data['dueDate'], $data['status'], $data['note'], $id
            ]);
            echo json_encode(["message" => "Task updated"]);
        }

        if ($method == "DELETE" && $id && !$sub) {
            $stmt = $pdo->prepare("DELETE FROM tasks WHERE Id=?");
            $stmt->execute([$id]);
            echo json_encode(["message" => "Task deleted"]);
        }

        if ($method == "PATCH" && $id && $sub == "status") {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $pdo->prepare("UPDATE tasks SET Status=? WHERE Id=?");
            $stmt->execute([$data['status'], $id]);
            echo json_encode(["message" => "Status updated"]);
        }
        break;

    case "room":
        if ($method == "GET") {
            $status = $_GET['status'] ?? '';
            $stmt = $pdo->prepare("SELECT room_no FROM room WHERE status=?");
            $stmt->execute([$status]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
        }

        if ($method == "PUT" && $id) {
            $data = json_decode(file_get_contents("php://input"), true);
            $new_status = $data['status']; // e.g., "Available"
            $assigned_staff = $data['assignedTo'] ?? ''; 

            // 1. UPDATE ROOM TABLE (Status ra gyud, walay bag-ong column)
            $stmtRoom = $pdo->prepare("UPDATE room SET status=? WHERE room_no=?");
            $stmtRoom->execute([$new_status, $id]);

            // 2. UPDATE TASKS TABLE (Diri i-save kinsa ang staff ug i-complete ang task)
            // Pangitaon nato ang task nga para sa maong room nga wala pa ma-complete
            if ($new_status == "Available") {
                $stmtTask = $pdo->prepare("
                    UPDATE tasks 
                    SET Status = 'Complete', AssignedTo = ? 
                    WHERE RoomNo = ? AND Status != 'Complete'
                ");
                $stmtTask->execute([$assigned_staff, $id]);
            }

            echo json_encode([
                "message" => "Sync successful: Room is now $new_status and Task is Complete.",
                "updated_room" => $id,
                "staff_assigned_in_tasks" => $assigned_staff
            ]);
        }
        break;

    case "staff":
        if ($method == "GET") {
            $work = $_GET['work'] ?? '';
            $stmt = $pdo->prepare("SELECT name FROM staff WHERE work=?");
            $stmt->execute([$work]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Route not found"]);
        break;
}