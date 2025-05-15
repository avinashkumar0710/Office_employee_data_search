<?php
session_start();
if (!isset($_SESSION['LoginId'])) {
    echo "<script>
        alert('Session expired! Please log in again.');
        window.location.href = 'display_data.php'; 
    </script>";
    exit();
}

$LoginId = $_SESSION['LoginId']; // ✅ Get LoginId from session
//echo $LoginId;
// Database Connection
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

// Fetch Employee Details
$sql = "SELECT 
            tbl1.empno, tbl1.emp_name, tbl1.location, tbl1.[Department Text], tbl1.[Position Text],  
            tbl1.dob, tbl1.mobile1, tbl1.email_id, tbl1.status, tbl1.grade, tbl1.dept, tbl1.design, 
            tbl1.gender, tbl1.address, tbl1.org, tbl1.qtr_type, tbl1.block_no, tbl1.qtr_no, 
            tbl1.cr_date, tbl2.name, tbl3.GradeName, tbl1.[inter_o], tbl1.[inter_r], tbl1.[std_code], 
            tbl1.[office_no], tbl1.[std_res], tbl1.[res_no],
            tbl4.Designation, tbl5.DeptName
        FROM [nspcl_it].[dbo].[Backup_Directory_06012021$] AS tbl1
        JOIN [nspcl_it].[dbo].[tbl_EmpPhotoMaster] AS tbl2 ON tbl1.empno = tbl2.empno
        JOIN [nspcl_it].[dbo].[tbl_GradeMaster] AS tbl3 ON tbl1.grade = tbl3.GradeId
        JOIN [nspcl_it].[dbo].[tbl_DesignationMaster] AS tbl4 ON tbl1.design = tbl4.DesigId
        JOIN [nspcl_it].[dbo].[tbl_DepartmentMaster] AS tbl5 ON tbl1.dept = tbl5.DeptCode
        WHERE tbl1.empno = ?";

$params = [$LoginId];
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if (!$row) {
    die("No employee found with ID: " . htmlspecialchars($LoginId));
}

// Construct Image Path
$imagePath = "Files/" . $row['empno'] . ".jpg";

// Check if file exists, then encode to Base64
$base64Image = "";
if (file_exists($imagePath)) {
    $imageData = file_get_contents($imagePath);
    $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageData);
} else {
    $base64Image = "data:image/png;base64," . base64_encode(file_get_contents("Files/default.png")); // Default image
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta Tags -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Profile</title>

<!-- Google Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<!-- Font Awesome (For Pencil Icon) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- jQuery (Required for Bootstrap Datepicker) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Bootstrap Datepicker CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<!-- Bootstrap JS (with Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


    <style>
         body {
            font-family: "Quicksand", sans-serif;
            background-color: #f0f0f0;
        }
        /* Ensure image is centered properly */
        .profile-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;            
        }
        .profile-image {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }      
        .form-label{
            font-weight: bold;
        }
        .form-select{
            background-color: aquamarine;
        }
        .custom-height {
    height: 90vh; /* 80% of the viewport height */
}

    </style>
</head>
<body>
<header class="bg-dark text-white py-3 px-4 d-flex align-items-center justify-content-between">
    <h2 class="mb-0">Employee Details</h2>
    <button class="btn btn-outline-light" onclick="logout()">Logout</button>
</header>
<script>
    function logout() {
        window.location.href = 'logout.php'; // Redirect to logout.php
    }
</script>
<form id="updateForm" enctype="multipart/form-data">
<div class="container-fluid custom-height">

    
    <div class="row h-100">
        <!-- Section 1: Profile Image (20%) -->
        <div class="col-md-2 d-flex flex-column align-items-center justify-content-center"
    style="background-color: #f3f4f9; box-shadow: 0px 10px 14px 2px var(--bs-teal); padding: 20px;">
    
    <!-- Profile Image Wrapper -->
    <div class="profile-image rounded-circle position-relative d-flex align-items-center justify-content-center"
        style="overflow: hidden; border: 3px solid white; box-shadow: 0px 16px 16px 0px rgba(0, 0, 0, 0.1);">
        
        <!-- Profile Image -->
        <img id="profileImage" src="<?= $base64Image ?>" alt="Employee Image" class="w-100 h-100" style="object-fit: cover;">
        
        <!-- Hidden File Input -->
        <input type="file" id="fileUpload" accept="image/*" style="display: none;" onchange="previewImage(event)">
        
        <!-- Label Button -->
        <label for="fileUpload" class="edit-button position-absolute"
            style="bottom: 10px; right: 10px; background-color: rgba(0,0,0,0.7); 
            color: white; border: none; border-radius: 50%; width: 30px; height: 30px; 
            display: flex; align-items: center; justify-content: center; cursor: pointer;">
            <i class="fas fa-pencil-alt"></i>
        </label>
    </div>

    <!-- Profile Picture Label -->
    <div class="mt-2">
        <span class="fw-bold text-secondary">Profile Picture</span>
    </div>

    <button type="button" id="uploadButton" class="mt-2">Upload</button>

</div>
<script>
document.getElementById("uploadButton").addEventListener("click", function() {
    let fileInput = document.getElementById("fileUpload");
    let empno = "<?= $LoginId ?>"; // Replace this with the actual empno (dynamically set)

    if (fileInput.files.length === 0) {
        alert("Please select an image first.");
        return;
    }

    let formData = new FormData();
    formData.append("file", fileInput.files[0]);
    formData.append("empno", empno); // Send employee number to the server

    fetch("upload_image.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            document.getElementById("profileImage").src = data.image + "?" + new Date().getTime(); // Refresh image
        }
    })
    .catch(error => console.error("Error:", error));
});
</script>
<!-- JavaScript for Image Preview -->
<script>
    function previewImage(event) {
        let file = event.target.files[0]; // Get the selected file
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileImage').src = e.target.result; // Change image source
            }
            reader.readAsDataURL(file); // Read file as a data URL
        }
    }
</script>

        <!-- Section 2: Employee Information (80%) -->
     <div class="col-md-10 h-70">
            
        <div class="row p-6 h-100 d-flex align-items-center justify-content-center">
                <div class="col-md-4">
                <input type="hidden" name="LoginId" value="<?= htmlspecialchars($row['empno']) ?>">
                    <label class="form-label">Employee No:</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($row['empno']) ?>" readonly>

                    <label class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($row['emp_name']) ?>">

                   

                    <label class="form-label">Location:</label>
                    <div class="d-flex">
                        <input type="text" class="form-control me-2" id="locationInput" name="location"
                            value="<?= htmlspecialchars($row['location']) ?>" readonly>
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

                    

                    <label class="form-label">Department:</label>
                    <div class="d-flex">
                    <!-- Display Department Name -->
                    <input type="text" class="form-control me-2" id="departmentInput" name="department_name"
                        value="<?= htmlspecialchars($row['DeptName'] ?? 'NULL') ?>" readonly>

                    <!-- Hidden Input to Store Department Code -->
                    <input type="hidden" id="departmentHidden" name="department" value="<?= htmlspecialchars($row['dept'] ?? '') ?>">

                    <!-- Department Dropdown -->
                    <select class="form-select" id="departmentSelect" onchange="updateInput('departmentSelect', 'departmentInput', 'departmentHidden')">
                        <option value="">Update Department</option>
                        <?php
                        $departmentQuery = "SELECT DeptCode, DeptName FROM [nspcl_it].[dbo].[tbl_DepartmentMaster]";
                        $departmentStmt = sqlsrv_query($conn, $departmentQuery);
                        while ($department = sqlsrv_fetch_array($departmentStmt, SQLSRV_FETCH_ASSOC)) {
                            echo "<option value='{$department['DeptCode']}'>{$department['DeptName']}</option>";
                        }
                        ?>
                    </select>
                </div>


                    <label class="form-label">Designation:</label>
                    <div class="d-flex">
                        <input type="text" class="form-control me-2" id="designationInput" name="designation_name"
                            value="<?= htmlspecialchars($row['Designation']) ?>" readonly>

                        <!-- Hidden Input to Store Department Code -->
                        <input type="hidden" id="designationHidden" name="designation" value="<?= htmlspecialchars($row['design'] ?? '') ?>">

                        <select class="form-select" id="designationSelect" onchange="updateInput('designationSelect', 'designationInput', 'designationHidden')">
                            <option value="">Update Designation</option>
                            <?php
                            $designationQuery = "SELECT DesigId, Designation FROM [nspcl_it].[dbo].[tbl_DesignationMaster]";
                            $designationStmt = sqlsrv_query($conn, $designationQuery);
                            while ($designation = sqlsrv_fetch_array($designationStmt, SQLSRV_FETCH_ASSOC)) {
                                echo "<option value='{$designation['DesigId']}'>{$designation['Designation']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <script>
                    function updateInput(selectId, inputId, hiddenId) {
                        var selectElement = document.getElementById(selectId);
                        var inputElement = document.getElementById(inputId);
                        var hiddenElement = document.getElementById(hiddenId);

                        var selectedOption = selectElement.options[selectElement.selectedIndex];
                        
                        if (selectedOption.value !== "") {
                            inputElement.value = selectedOption.text; // Display name in text field
                            hiddenElement.value = selectedOption.value; // Store ID in hidden input
                        } else {
                            inputElement.value = ""; 
                            hiddenElement.value = "";
                        }
                    }
                    </script>



                    <label class="form-label">Grade:</label>
                    <input type="hidden" class="form-control" name="grade" value="<?= htmlspecialchars($row['grade']) ?>" readonly>
                    <input type="text" class="form-control" name="grade1" value="<?= htmlspecialchars($row['GradeName']) ?>" readonly>

                    <label class="form-label">Gender:</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="1" <?= ($row['gender'] == 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" name="gender" for="male">Male</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="0" <?= ($row['gender'] == 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                    </div>
                    <label class="form-label">Mobile:</label>
                    <input type="number" class="form-control" name="mobile" value="<?= htmlspecialchars($row['mobile1']) ?>">                    
                </div>

                <!-- Right Side Column -->
                <div class="col-md-4">                              
                <!-- Date of Birth Field --> 
                <label class="form-label">Date of Birth:</label>
                <div class="input-group date">
                    <input type="text" class="form-control" name="dob" value="<?= htmlspecialchars($row['dob']->format('Y-m-d')) ?>" id="dob">
                    <span class="input-group-text" id="dobPicker"><i class="fas fa-calendar-alt"></i></span>
                </div>

                <!-- JavaScript for Date Picker -->
                <script>
                    $(document).ready(function() {
                        // Initialize the datepicker but do NOT auto-show it
                        $('#dob').datepicker({
                            format: 'yyyy-mm-dd',
                            autoclose: true,
                            todayHighlight: true
                        });

                        // Open the datepicker when the icon is clicked
                        $('#dobPicker').click(function() {
                            $('#dob').datepicker('show');
                        });
                    });
                </script>


                    <label class="form-label">Email:</label>
                    <input type="text" name="email" class="form-control" value="<?= htmlspecialchars($row['email_id']) ?>">

                    <label class="form-label">Intercom Office:</label>
                    <input type="number" name="inter_o" class="form-control" value="<?= htmlspecialchars($row['inter_o']) ?>">

                    <label class="form-label">Intercom Residence:</label>
                    <input type="number" name="inter_r" class="form-control" value="<?= htmlspecialchars($row['inter_r']) ?>">

                    <label class="form-label">STD Code:</label>
                    <input type="number" name="std_code" class="form-control" value="<?= htmlspecialchars($row['std_code']) ?>">

                    <label class="form-label">Office No:</label>
                    <input type="number" name="office_no" class="form-control" value="<?= htmlspecialchars($row['office_no']) ?>">

                    <label class="form-label">STD Residence:</label>
                    <input type="number" name="std_res" class="form-control" value="<?= htmlspecialchars($row['std_res']) ?>">

                    <label class="form-label">Residence No:</label>
                    <input type="number" name="res_no" class="form-control" value="<?= htmlspecialchars($row['res_no']) ?>">

                 </div>
                                  
            <div class="container-fluid py-3">    
            <div class="col-md-12 d-flex justify-content-center gap-3">
                <button type="submit" class="btn btn-success px-4" id="updateBtn">Update</button>
                <button type="reset" class="btn btn-danger px-4">Reset</button>
            </div>    
            </div>
        </div>
    </div>
</div>
</div>
</form>
<script>
   document.getElementById("updateForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent normal form submission

    let formData = new FormData(this);
    let filteredData = new FormData();

    // Filter out empty fields
    for (let [key, value] of formData.entries()) {
        if (value.trim() !== "") { // Exclude empty values
            filteredData.append(key, value);
        }
    }

    // 🔹 Debugging: Log each form field being sent
    console.log("🔹 Form Data Being Sent:");
    for (let pair of filteredData.entries()) {
        console.log(pair[0] + ":", pair[1]);

    }

    fetch("update_employee.php", {
        method: "POST",
        body: filteredData
    })
    .then(response => response.json())
    .then(data => {
        console.log("🔹 Server Response:", data); // Log response from PHP
        alert(data.message);
        if (data.status === "success") {
            location.reload(); // Reload on success
        }
    })
    .catch(error => console.error("❌ Error:", error));
});

</script>
<footer class="fixed-bottom"
        style="background-color: #34495E; height: 30px; display: flex; align-items: center; justify-content: center;">
        <div class="text-center text-white">
            <small>Developed by IT Department, NSPCL BHILAI, ©
                <script>document.write(new Date().getFullYear());</script> All rights reserved by NSPCL, BHILAI
            </small>
        </div>
    </footer>
</body>
</html>

