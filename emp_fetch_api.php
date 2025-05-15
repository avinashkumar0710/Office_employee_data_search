<?php
// Database connection details for Complaint
$serverName = "192.168.100.240";
$connectionOptionsComplaint = array(
    "Database" => "Complaint",
    "Uid" => "sa",
    "PWD" => "Intranet@123"
);

// Database connection details for NSPCL_IT
$connectionOptionsNSPCL = array(
    "Database" => "nspcl_it",
    "Uid" => "sa",
    "PWD" => "Intranet@123"
);

// Establish the connection to Complaint database
$connComplaint = sqlsrv_connect($serverName, $connectionOptionsComplaint);
if ($connComplaint === false) {
    http_response_code(500);
    die(json_encode(array("error" => "Complaint DB Connection failed: " . print_r(sqlsrv_errors(), true))));
}

// Establish the connection to NSPCL_IT database
$connNSPCL = sqlsrv_connect($serverName, $connectionOptionsNSPCL);
if ($connNSPCL === false) {
    http_response_code(500);
    die(json_encode(array("error" => "NSPCL_IT DB Connection failed: " . print_r(sqlsrv_errors(), true))));
}

// SQL query using both databases
$sql = "SELECT t1.empno, t1.name AS emp_name, t1.loc_desc AS location, t1.email,
               t2.dob, t1.dept, t1.design, t1.grade, 
               ISNULL(t1.mob1, '---') AS mobile1, 
               ISNULL(t2.inter_o, '--') AS inter_o, 
               t3.name AS photo_name 
        FROM Complaint.dbo.emp_mas_sap AS t1 
        LEFT JOIN nspcl_it.dbo.Backup_Directory_06012021$ AS t2 ON t1.empno = t2.empno 
        JOIN nspcl_it.dbo.tbl_EmpPhotoMaster AS t3 ON t3.empno = t1.empno
        WHERE t2.update_flag = 'A' AND t2.status = 'A' AND t1.status = 'A'
        ORDER BY t2.grade DESC, t2.design - 1 DESC, t1.loc_desc";

// Execute query using Complaint DB connection
$stmt = sqlsrv_query($connComplaint, $sql);

if ($stmt === false) {
    http_response_code(500);
    die(json_encode(array("error" => "Error fetching data: " . print_r(sqlsrv_errors(), true))));
}

// Fetch data
$results = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Trim whitespace from all string data
    foreach ($row as $key => $value) {
        if (is_string($value)) {
            $row[$key] = trim($value);
        }
    }
    $results[] = $row;
}

// Close connections
sqlsrv_close($connComplaint);
sqlsrv_close($connNSPCL);

// Return results as JSON
header('Content-Type: application/json');
echo json_encode($results);
?>
