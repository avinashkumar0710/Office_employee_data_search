<?php
session_start();
session_destroy(); // Destroy all session data
header("Location: display_data.php"); // Redirect to display_data.php
exit();
?>
