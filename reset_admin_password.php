<?php
// reset_admin_password.php - Reset admin password to a known value
require_once 'db_connect.php';

$new_password = 'admin123'; // Change this to your desired password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

echo "<h2>Admin Password Reset</h2>";

if ($conn->connect_error) {
    echo "<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color:green;'>Database connection successful!</p>";
    
    // Update admin password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $stmt->bind_param("s", $hashed_password);
    
    if ($stmt->execute()) {
        echo "<p style='color:green;'>Admin password updated successfully!</p>";
        echo "<p><strong>New password:</strong> $new_password</p>";
        echo "<p><strong>Hashed password:</strong> " . substr($hashed_password, 0, 30) . "...</p>";
    } else {
        echo "<p style='color:red;'>Failed to update password: " . $stmt->error . "</p>";
    }
    
    $stmt->close();
    
    // Also create a test user if it doesn't exist
    $test_username = 'test';
    $test_email = 'test@example.com';
    $test_password = 'test123';
    $test_hashed = password_hash($test_password, PASSWORD_DEFAULT);
    
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $test_username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows == 0) {
        $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'staff')");
        $insert_stmt->bind_param("sss", $test_username, $test_email, $test_hashed);
        
        if ($insert_stmt->execute()) {
            echo "<p style='color:green;'>Test user created successfully!</p>";
            echo "<p><strong>Test username:</strong> $test_username</p>";
            echo "<p><strong>Test password:</strong> $test_password</p>";
        }
        $insert_stmt->close();
    } else {
        echo "<p>Test user already exists.</p>";
    }
    
    $check_stmt->close();
}

$conn->close();
?>



