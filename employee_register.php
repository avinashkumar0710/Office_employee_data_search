<?php
session_start();

$serverName = "192.168.100.240";
$connectionOptions = [
    "Database" => "nspcl_it",
    "UID" => "sa",
    "PWD" => "Intranet@123"
];

// Connect to SQL Server
$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
    font-family: "Quicksand", sans-serif;
    background-color: #f0f0f0;
}


    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Employee Signup</h2>
        <form id="signupForm" action="process_signup.php" method="POST" enctype="multipart/form-data">
            <div class="row mt-6">
                <div class="col-md-4">
                    
                    <label class="form-label">Employee ID</label>
                    <input type="text" name="employee_id" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Title</label>
                    <select name="title" class="form-control" required>
                        <option value="">--Select--</option>
                        <option value="Shri">Shri</option>
                        <option value="Smt">Smt</option>
                        <option value="Ms">Ms</option>
                    </select>
                </div>
            </div>
            
            <div class="row mt-6">
                
                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control" required>
                        <option value="">--Select--</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="dob" class="form-control" required>
                </div>
                <div class="col-md-4">
                <label class="form-label">Location:</label>
                    
                        
                        <select class="form-select" id="locationSelect" onchange="updateInput('locationSelect', 'locationInput')">
                            <option value="">Update Location</option>
                            <?php
                            $locationQuery = "SELECT DISTINCT location FROM [nspcl_it].[dbo].[Backup_Directory_06012021$]";
                            $locationStmt = sqlsrv_query($conn, $locationQuery);
                            while ($location = sqlsrv_fetch_array($locationStmt, SQLSRV_FETCH_ASSOC)) {
                                echo "<option value='{$location['location']}'>{$location['location']}</option>";
                            }
                            ?>
                        </select>
                    
            </div>
            
            <div class="row mt-6">
                
            </div>
            
            <div class="row mt-6">
                <div class="col-md-4">
                    <label class="form-label">Designation</label>
                    <select name="designation" class="form-control" required>
                        <option value="">--Select Designation--</option>
                        <option value="Manager">Manager</option>
                        <option value="Staff">Staff</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Department</label>
                    <select name="department" class="form-control" required>
                        <option value="">--Select DeptName--</option>
                        <option value="HR">HR</option>
                        <option value="IT">IT</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Grade</label>
                    <select name="grade" class="form-control" required>
                        <option value="">--Select Grade--</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                    </select>
                </div>
            </div>
            
            <div class="row mt-6">
               

                <div class="col-md-4">
                    <label class="form-label">Mobile No</label>
                    <input type="text" name="mobile" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Mobile No(Alternative)</label>
                    <input type="text" name="mobile1" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Intercome No (Office)</label>
                    <input type="text" name="Intercome_Office" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>
            </div>
            

            <div class="row mt-6">
            <div class="col-md-4">
                    <label class="form-label">Intercome No (Residance)</label>
                    <input type="text" name="Intercome_Residance" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">STD Code (O)</label>
                    <input type="text" name="STD_O" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Office No.</label>
                    <input type="text" name="Office No." class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>
            </div>

            <div class="row mt-6">
            

                <div class="col-md-4">
                    <label class="form-label">STD Code (R)</label>
                    <input type="text" name="STD Code (R)" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Residance No.</label>
                    <input type="text" name="Residance No." class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Address</label>
                    <input type="text" name="Address" class="form-control" required>
                </div>
            </div>

           

            <div class="row mt-6">
            <div class="col-md-4">
                    <label class="form-label">Organisation</label>
                    <input type="text" name="Organisation" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Quarter Type</label>
                    <input type="text" name="Quarter Type" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email ID</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>

            <div class="row mt-6">
            <div class="col-md-4">
                    <label class="form-label">Block No</label>
                    <input type="text" name="Block No" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Quarter No</label>
                    <input type="text" name="Quarter No" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Employee Photo</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>
            
           
            
            <div class="row mt-6">

            <div class="col-md-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Upload Profile Picture</label>
                    <input type="file" name="profile_image" class="form-control">
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-primary">Register</button>
                <button type="button" class="btn btn-danger">Reset</button>
            </div>
        </form>
    </div>
</body>
</html>
