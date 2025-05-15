<?php
session_start();
header("Content-Type: application/json");

// Database connection
$serverName = "192.168.100.240"; 
$connectionInfo = array(
    "Database" => "nspcl_it",
    "UID" => "sa",
    "PWD" => "Intranet@123"
);
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

// Get login data and remove extra spaces
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Missing username or password"]);
    exit();
}

// Check if user exists
$sql = "SELECT LoginId, Password FROM nspcl_it.dbo.tbl_employeeLogin WHERE LoginId = ?";
$params = array($username);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(json_encode(["status" => "error", "message" => print_r(sqlsrv_errors(), true)])); // Debug SQL Errors
}

if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Check if password matches
    if ($password === $row['Password']) { // ✅ Use password_verify() if passwords are hashed
        $_SESSION['LoginId'] = $row['LoginId']; // ✅ Store session
        echo json_encode(["status" => "success", "LoginId" => $row['LoginId']]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid username"]);
}

sqlsrv_close($conn);
?>
