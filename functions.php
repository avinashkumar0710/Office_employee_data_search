<?php
function getProfileImagePath($empno) {
    $filePath = "Files/" . $empno . ".jpg";
    return file_exists($filePath) ? $filePath : "default.jpg"; // Show default image if none exists
}
?>
