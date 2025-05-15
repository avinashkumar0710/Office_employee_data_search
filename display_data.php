<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Employee Data</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="display_data.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
     integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap JS (with Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>



    <style>
        body {
    font-family: "Quicksand", sans-serif;
    background-color: #f0f0f0;
}


    </style>
</head>

<body>

    <div class="container">


        <!-- Filters -->
        <div class="filter-container">
        <div class="overlay" id="overlay" onclick="closeLoginPopup()"></div>

<!-- Popup Login Form -->
<div class="popup" id="loginPopup">
    <span class="close-btn" onclick="closeLoginPopup()"><i class="fa fa-times" aria-hidden="true"></i></span>
    <h3>Login</h3><br>
    <form id="loginForm">
        <input type="text" id="username" placeholder="Username" required><br><br>
        <input type="password" id="password" placeholder="Password" required><br><br>
        <button type="submit" class="btn btn-success">Login</button>
    </form>
</div>
                <div class="auth-buttons">
    <!------------------------------------------------------Employee Login Code-------------------------------------------------------------------------------------------------------->
    <div class="login" onclick="openLoginPopup()">
            <span>Login</span>
        </div>
               
        
<script>
        function openLoginPopup() {
    document.getElementById('loginPopup').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}

function closeLoginPopup() {
    document.getElementById('loginPopup').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

// Handle Login (AJAX Request)
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    let username = document.getElementById('username').value.trim();
    let password = document.getElementById('password').value.trim();

    if (!username || !password) {
        alert('Please enter both Username and Password.');
        return;
    }

    fetch('login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
    })  
    .then(response => response.json())  // Expecting JSON response
    .then(data => {
        if (data.status === 'success') {
            alert('Login Successful!');

            // Store LoginId in sessionStorage (optional)
            sessionStorage.setItem('LoginId', data.LoginId);

            // Redirect with LoginId
            window.location.href = `employee_details.php?LoginId=${data.LoginId}`;
        } else {
            alert('Invalid Username or Password');
        }
    })
    .catch(error => console.error('Error:', error));
});


    </script>

    <!-----------------------------------------------------------End Employee Details Code---------------------------------------------------------------------------------------------------->
                <div class="admin-login"><span>Admin Login</span></div>
                <div class="signup" onclick="navigateToRegister()">
    <span>SignUp</span>
</div>

<script>
    function navigateToRegister() {
        window.location.href = "employee_register.php"; // Redirect to the desired page
    }
</script>

        </div>

                    <select id="locationFilter" class="form-select">
                        <option value="">Select Location</option>
                    </select>
                    <select id="departmentFilter" class="form-select">
                        <option value="">Select Department</option>
                    </select>
                    <select id="gradeFilter" class="form-select">
                        <option value="">Select Grade</option>
                    </select>
                    <input type="text" id="searchBar" class="form-control" placeholder="Search by all...">
        </div>

        <div id="employeeList"></div>

        <div id="pagination" class="pagination"></div>
    </div>
    <br><br>
    <footer class="fixed-bottom"
        style="background-color: #34495E; height: 30px; display: flex; align-items: center; justify-content: center;">
        <div class="text-center text-white">
            <small>Developed by IT Department, NSPCL BHILAI, ©
                <script>document.write(new Date().getFullYear());</script> All rights reserved by NSPCL, BHILAI
            </small>
        </div>
    </footer>


<!-- Bootstrap Modal -->
<div class="modal fade bd-example-modal-lg" id="employeeModal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" 
style="width:700px;height:650px; border-radius: 0px 0px 15px 15px; background-color: #d5c4aa;">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Employee Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" 
                        style="background-color: red; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                    <span style="color: white; font-size: 20px; font-weight: bold;">&times;</span>
                </button>

            </div>
            <div class="modal-body text-center">
                <!-- Employee Image -->               
                <img id="emp_photo" src="Files/noimage.jpg" class="img-fluid rounded-circle mb-3" 
                    style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #ccc;" 
                    onerror="this.onerror=null; this.src='Files/noimage.jpg'; console.log('Image not found, using default');">

                <!-- Employee Details -->
                <div class="row">
                    <div class="col-md-6 text-start">
                        <p><strong>Emp No:</strong> <span id="empno"></span></p>
                        <p><strong>Name:</strong> <span id="emp_name"></span></p>
                        <p><strong>Location:</strong> <span id="location"></span></p>
                        <p><strong>Department:</strong> <span id="dept"></span></p>
                        <p><strong>Designation:</strong> <span id="design"></span></p>
                    </div>
                    <div class="col-md-6 text-start">
                        <p><strong>Grade:</strong> <span id="grade"></span></p>
                        <p><strong>Mobile:</strong> <span id="mobile1"></span></p>
                        <p><strong>Intercom (Office):</strong> <span id="intercom_office"></span></p>
                        <p><strong>Intercom (Residence):</strong> <span id="intercom_res"></span></p>
                        <p><strong>Email:</strong> <span id="email"></span></p>
                        <p><strong>Address:</strong> <span id="address"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!----------------------------------------------------------------------------------------------------------------------->
    <script>
document.addEventListener("DOMContentLoaded", function () {
    // Attach event listeners to dynamically created elements
    document.body.addEventListener("click", function (event) {
        if (event.target.closest('.open-modal')) {
            event.preventDefault();

            let link = event.target.closest('.open-modal'); // Get the clicked <a> element
            let empno = link.getAttribute('data-empno'); // Get employee number
            
            console.log("Clicked Employee No:", empno); // Debugging
            

            if (!empno) {
                alert("Error: Employee number not found!");
                return;
            }

            // Fetch employee details
            fetch(`fetch_employee.php?empno=${empno}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Received Data:", data); // Debugging

                    if (data.error) {
                        alert("Error: " + data.error);
                        return;
                    }

                    // Populate modal with employee data
                    document.getElementById('empno').innerText = data.empno || "--";
                    document.getElementById('emp_name').innerText = data.emp_name || "--";
                    document.getElementById('location').innerText = data.location || "--";
                    document.getElementById('dept').innerText = data.dept || "--";
                    document.getElementById('design').innerText = data.design || "--";
                    document.getElementById('grade').innerText = data.grade || "--";
                    document.getElementById('mobile1').innerText = data.mobile1 || "--";
                    document.getElementById('intercom_office').innerText = data.Intercom_Office || "--";
                    document.getElementById('intercom_res').innerText = data.Intercom_Residance || "--";
                    document.getElementById('email').innerText = data.email || "--";
                    document.getElementById('address').innerText = data.address || "--";
                    // Set employee image
                    let empPhoto = document.getElementById('emp_photo');
                    empPhoto.src = data.photo;
                    empPhoto.onerror = function () {
                        this.src = "Files/noimage.jpg"; // Fallback image
                        console.log("Image not found, using default");
                    };

                    // Show Bootstrap Modal
                    let modal = new bootstrap.Modal(document.getElementById('employeeModal'));
                    modal.show();
                    console.log("Modal should now be open.");
                })
                .catch(error => {
                    console.error("Error fetching data:", error);
                    alert("Failed to fetch employee details.");
                });
        }
    });
});

   </script>

    <script>
        let employees = [];
        let filteredEmployees = [];
        let itemsPerPage = 10;
        let currentPage = 1;

        async function fetchEmployees() {
            const response = await fetch('http://localhost/employee_search/emp_fetch_api.php');
            employees = await response.json();
            filteredEmployees = [...employees];

            populateFilters();
            renderEmployees();
        }

        function populateFilters() {
            // Extract unique values from employee data
            let locations = [...new Set(employees.map(emp => emp.location))].sort();
            let departments = [...new Set(employees.map(emp => emp.dept))].sort();
            let grades = [...new Set(employees.map(emp => emp.grade))].sort();

            // Populate Location Dropdown
            let locationFilter = document.getElementById('locationFilter');
            locationFilter.innerHTML = `<option value="">All Locations</option>`;
            locations.forEach(location => {
                locationFilter.innerHTML += `<option value="${location}">${location}</option>`;
            });

            // Populate Department Dropdown
            let departmentFilter = document.getElementById('departmentFilter');
            departmentFilter.innerHTML = `<option value="">All Departments</option>`;
            departments.forEach(dept => {
                departmentFilter.innerHTML += `<option value="${dept}">${dept}</option>`;
            });

            // Populate Grade Dropdown
            let gradeFilter = document.getElementById('gradeFilter');
            gradeFilter.innerHTML = `<option value="">All Grades</option>`;
            grades.forEach(grade => {
                gradeFilter.innerHTML += `<option value="${grade}">${grade}</option>`;
            });
        }

        // Call this function after fetching employee data
        populateFilters();

        function updateFilterOptions(elementId, values) {
            const select = document.getElementById(elementId);
            values.forEach(value => {
                let option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                select.appendChild(option);
            });
        }

        function applyFilters() {
            let location = document.getElementById('locationFilter').value.toLowerCase();
            let department = document.getElementById('departmentFilter').value.toLowerCase();
            let grade = document.getElementById('gradeFilter').value.toLowerCase();
            let searchText = document.getElementById('searchBar').value.toLowerCase();

            filteredEmployees = employees.filter(emp => {
                return (
                    (location === "" || emp.location.toLowerCase() === location) &&
                    (department === "" || emp.dept.toLowerCase() === department) &&
                    (grade === "" || emp.grade.toLowerCase() === grade) &&
                    (searchText === "" ||
                        emp.emp_name.toLowerCase().includes(searchText) ||
                        emp.empno.toLowerCase().includes(searchText) ||
                        emp.location.toLowerCase().includes(searchText) ||
                        emp.dept.toLowerCase().includes(searchText) ||
                        emp.grade.toLowerCase().includes(searchText) ||
                        emp.mobile1.toLowerCase().includes(searchText) ||
                        emp.design.toLowerCase().includes(searchText) ||
                        emp.inter_o.toLowerCase().includes(searchText)
                    )
                );
            });

            currentPage = 1;
            renderEmployees();
            renderPagination();
        }

        function renderEmployees() {
            const employeeList = document.getElementById('employeeList');
            employeeList.innerHTML = '';

            let start = (currentPage - 1) * itemsPerPage;
            let end = start + itemsPerPage;
            let paginatedEmployees = filteredEmployees.slice(start, end);

            paginatedEmployees.forEach(emp => {
                let dob = emp.dob?.date ? new Date(emp.dob.date).toISOString().split('T')[0] : '---';
                let empno = emp.empno.replace(/^0+/, '');
                let photoPath = `Files/${empno}.jpg`;

                let employeeCard = `
                <div class='employee-card'>
                    <div class='employee-image'>
                        <img src="${photoPath}" onerror="this.src='Files/noimage.jpg'">
                        <h5>${emp.emp_name}</h5>
                    </div>
                    <div class='employee-info'>
                        <p><strong>EmpNo:</strong> ${emp.empno}</p>
                        <p><strong>Location:</strong> ${emp.location}</p>
                        <p><strong>Email:</strong> ${emp.email}</p>
                        <p><strong>Dept:</strong> ${emp.dept}</p>
                        <p><strong>Design:</strong> ${emp.design}</p>
                        <p><strong>Grade:</strong> ${emp.grade}</p>
                        <p><strong>Mobile:</strong> ${emp.mobile1}</p>
                        <p><strong>Inter O:</strong> ${emp.inter_o}</p>
                    </div>
                    <div class="arrow-container">
                        <a href="#" class="open-modal" data-empno="${emp.empno}" title="Employee No: ${emp.empno}">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    </div>


                </div>
            `;
                employeeList.innerHTML += employeeCard;
            });

            renderPagination();
        }

        function renderPagination() {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            let totalPages = Math.ceil(filteredEmployees.length / itemsPerPage);
            if (totalPages <= 1) return;

            // Previous Button
            pagination.innerHTML += `<a ${currentPage > 1 ? `href="#" onclick="changePage(${currentPage - 1})"` : 'class="disabled"'}>Previous</a>`;

            // Show first page and ellipsis if needed
            if (currentPage > 3) {
                pagination.innerHTML += `<a href="#" onclick="changePage(1)">1</a>`;
                if (currentPage > 4) pagination.innerHTML += `<a class="disabled">...</a>`;
            }

            // Display a range of pages around the current page
            let startPage = Math.max(1, currentPage - 1);
            let endPage = Math.min(totalPages, currentPage + 1);

            for (let i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    pagination.innerHTML += `<a class="disabled">${i}</a>`;
                } else {
                    pagination.innerHTML += `<a href="#" onclick="changePage(${i})">${i}</a>`;
                }
            }

            // Show ellipsis and last page if needed
            if (currentPage < totalPages - 2) {
                if (currentPage < totalPages - 3) pagination.innerHTML += `<a class="disabled">...</a>`;
                pagination.innerHTML += `<a href="#" onclick="changePage(${totalPages})">${totalPages}</a>`;
            }

            // Next Button
            pagination.innerHTML += `<a ${currentPage < totalPages ? `href="#" onclick="changePage(${currentPage + 1})"` : 'class="disabled"'}>Next</a>`;
        }


        function changePage(page) {
            currentPage = page;
            renderEmployees();
        }

        // Apply filters when any dropdown or search input changes
        document.querySelectorAll('.form-select, #searchBar').forEach(el => el.addEventListener('input', applyFilters));

        fetchEmployees();
    </script>

</body>

</html>