<?php
header("Content-Type: application/json");

$uploadDir = "Files/"; // Change this to your desired upload location

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!isset($_FILES["file"]) || !isset($_POST["empno"])) {
    echo json_encode(["status" => "error", "message" => "Missing file or employee number!"]);
    exit();
}

$empno = preg_replace("/[^a-zA-Z0-9]/", "", $_POST["empno"]); // Sanitize empno
$filePath = $uploadDir . $empno . ".jpg"; // Save as empno.jpg

if (move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
    echo json_encode(["status" => "success", "message" => "Image uploaded successfully!", "image" => $filePath]);
} else {
    echo json_encode(["status" => "error", "message" => "Image upload failed!"]);
}
?>
