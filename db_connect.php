<?php
// db_connect.php (INAYOS NA VERSION)

$servername = "localhost";

// --- AYUSIN DITO ---

// 1. Pinalitan ang "logi admin" (may space) ng "logi_admin" (may underscore)
$username = "logi_admin";
$database = "logi_admin"; // 2. Pinalitan ko na rin ang variable name from $name to $database para mas malinaw

// 3. Ilagay mo dito ang TOTOONG password galing sa iyong hosting
// Kung hindi mo alam, i-reset mo sa cPanel gamit ang 'Change' button
$password = "YUNG_TUNAY_NA_PASSWORD_MO_DITO"; 

// --- WAKAS NG PAG-AYOS ---


// Create connection
// Ginamit na natin yung tamang variable na $database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    // Itong error na ito ang lalabas kung mali pa rin ang credentials mo
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character set to utf8mb4 for full unicode support
$conn->set_charset("utf8mb4");

?>