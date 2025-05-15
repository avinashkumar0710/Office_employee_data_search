<?php
session_start();
header("Content-Type: application/json");

// Check session
if (!isset($_SESSION['LoginId'])) {
    echo json_encode(["status" => "error", "message" => "Session expired! Please log in again."]);
    exit();
}

// Database connection
$serverName = "192.168.100.240";
$connectionInfo = [
    "Database" => "nspcl_it",
    "UID" => "sa",
    "PWD" => "Intranet@123"
];
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed", "errors" => sqlsrv_errors()]);
    exit();
}

// Get Employee ID
$empno = isset($_POST['LoginId']) && is_numeric($_POST['LoginId']) ? (int)$_POST['LoginId'] : null;
if (!$empno) {
    echo json_encode(["status" => "error", "message" => "Employee ID is missing or invalid"]);
    exit();
}

// Validate and sanitize input fields
$name       = $_POST['name'] ?? null;
$location   = $_POST['location'] ?? null;
$department = isset($_POST['department']) && is_numeric($_POST['department']) ? (int)$_POST['department'] : null;
$designation = isset($_POST['designation']) && is_numeric($_POST['designation']) ? (int)$_POST['designation'] : null;
//$grade      = isset($_POST['grade']) && is_numeric($_POST['grade']) ? (int)$_POST['grade'] : null;
$grade      = $_POST['grade'] ?? null;
$gender     = isset($_POST['gender']) && is_numeric($_POST['gender']) ? (int)$_POST['gender'] : null;
$mobile     = $_POST['mobile'] ?? null;
$dob        = !empty($_POST['dob']) ? date("Y-m-d H:i:s", strtotime($_POST['dob'])) : null;
$email      = $_POST['email'] ?? null;
$inter_o    = isset($_POST['inter_o']) && is_numeric($_POST['inter_o']) ? (int)$_POST['inter_o'] : null;
$inter_r    = isset($_POST['inter_r']) && is_numeric($_POST['inter_r']) ? (int)$_POST['inter_r'] : null;
$std_code   = isset($_POST['std_code']) && is_numeric($_POST['std_code']) ? (int)$_POST['std_code'] : null;
$office_no  = isset($_POST['office_no']) && is_numeric($_POST['office_no']) ? (int)$_POST['office_no'] : null;
$std_res    = isset($_POST['std_res']) && is_numeric($_POST['std_res']) ? (int)$_POST['std_res'] : null;
$res_no     = isset($_POST['res_no']) && is_numeric($_POST['res_no']) ? (int)$_POST['res_no'] : null;

// Debugging: Print types of parameters
error_log(print_r([
    "empno" => $empno, "name" => $name, "location" => $location,
    "department" => $department, "designation" => $designation, "grade" => $grade, 
    "gender" => $gender, "mobile" => $mobile, "dob" => $dob, "email" => $email,
    "inter_o" => $inter_o, "inter_r" => $inter_r, "std_code" => $std_code,
    "office_no" => $office_no, "std_res" => $std_res, "res_no" => $res_no
], true));

// SQL Update Query
$sql = "UPDATE [nspcl_it].[dbo].[Backup_Directory_06012021$] 
        SET emp_name = ?, location = ?, dept = ?, design = ?, grade = ?, gender = ?, 
            mobile1 = ?, dob = ?, email_id = ?, inter_o = ?, inter_r = ?, 
            std_code = ?, office_no = ?, std_res = ?, res_no = ? 
        WHERE empno = ?";

// Parameters for SQL Query
$params = [
    (string) $name, (string) $location, $department, $designation, $grade, $gender, 
    (string) $mobile, (string) $dob, (string) $email, $inter_o, $inter_r, 
    $std_code, $office_no, $std_res, $res_no, $empno
];

// Execute the SQL Query
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode([
        "status" => "error",
        "message" => "Error updating employee details",
        "sql" => $sql,
        "params" => $params,
        "errors" => sqlsrv_errors()
    ]);
    exit();
}

// Success response
echo json_encode(["status" => "success", "message" => "Employee details updated successfully"]);
?>
