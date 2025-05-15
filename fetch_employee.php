
<?php
$serverName = "192.168.100.240";

// Connect to `Complaint` Database
$connectionOptionsComplaint = array(
    "Database" => "Complaint",
    "Uid" => "sa",
    "PWD" => "Intranet@123"
);
$connComplaint = sqlsrv_connect($serverName, $connectionOptionsComplaint);
if ($connComplaint === false) {
    die(json_encode(["error" => "Complaint Connection failed: " . print_r(sqlsrv_errors(), true)]));
}

// Connect to `nspcl_it` Database
$connectionOptionsNSPCL = array(
    "Database" => "nspcl_it",
    "Uid" => "sa",
    "PWD" => "Intranet@123"
);
$connNSPCL = sqlsrv_connect($serverName, $connectionOptionsNSPCL);
if ($connNSPCL === false) {
    die(json_encode(["error" => "NSPCL Connection failed: " . print_r(sqlsrv_errors(), true)]));
}

// Check if empno is provided
if (isset($_GET['empno'])) {
    $empno = $_GET['empno'];

    // 🔍 Debug: Print the received `empno`
    error_log("Received Emp No: " . $empno);
    
    // Fetch employee details from `Complaint`
    $queryComplaint = "SELECT empno, name AS emp_name, loc_desc AS location, dept, design, grade, 
                              ISNULL(mob1, '---') AS mobile1, email
                       FROM Complaint.dbo.emp_mas_sap
                       WHERE empno = ?";
    
    $stmtComplaint = sqlsrv_query($connComplaint, $queryComplaint, [$empno]);
    
    if ($stmtComplaint === false) {
        die(json_encode(["error" => "Complaint Query execution failed", "sqlsrv_errors" => print_r(sqlsrv_errors(), true)]));
    }

    $employee = sqlsrv_fetch_array($stmtComplaint, SQLSRV_FETCH_ASSOC);
    
    // 🔍 Debug: Print employee details
    error_log("Employee Data from Complaint: " . json_encode($employee));

    // Fetch `inter_o` and `inter_r` from `nspcl_it`
    $queryNSPCL = "SELECT ISNULL(inter_o, '--') AS Intercom_Office, ISNULL(inter_r, '--') AS Intercom_Residance, ISNULL(address, '---') AS address
                   FROM nspcl_it.dbo.Backup_Directory_06012021$
                   WHERE empno = ?";
    
    $stmtNSPCL = sqlsrv_query($connNSPCL, $queryNSPCL, [$empno]);
    
    if ($stmtNSPCL === false) {
        die(json_encode(["error" => "NSPCL Query execution failed", "sqlsrv_errors" => print_r(sqlsrv_errors(), true)]));
    }

    $intercomData = sqlsrv_fetch_array($stmtNSPCL, SQLSRV_FETCH_ASSOC);

    // 🔍 Debug: Print Intercom details
    error_log("Intercom Data from NSPCL: " . json_encode($intercomData));

    // Merge both query results
    if ($employee) {
        $employee = array_merge($employee, $intercomData);
    
        // Remove leading zeros from empno
        $empnoWithoutZeros = ltrim($empno, '0');
    
        // Check if employee image exists
        $imagePath = "Files/" . $empnoWithoutZeros . ".jpg";
        if (!file_exists($imagePath)) {
            $imagePath = "Files/noimage.jpg"; // Default image
        }
    
        // Add image URL to response
        $employee['photo'] = $imagePath;
    
        echo json_encode($employee);
    } else {
        echo json_encode(["error" => "No data found"]);
    }
} else {
    echo json_encode(["error" => "Empno parameter missing"]);
}
?>
