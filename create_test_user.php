<?php
// create_test_user.php - Create a test user with known password
require_once 'db_connect.php';

echo "<h2>Create Test User</h2>";

if ($conn->connect_error) {
    echo "<p style='color:red;'>Database connection failed: " . $conn->connect_error . "</p>";
    exit;
}

// Test credentials
$test_username = 'testuser';
$test_password = 'test123';
$test_email = 'test@example.com';
$test_role = 'admin';

// Hash the password
$hashed_password = password_hash($test_password, PASSWORD_DEFAULT);

// Check if user already exists
$check_sql = "SELECT id FROM users WHERE username = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $test_username);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    echo "<p style='color:orange;'>⚠️ User '$test_username' already exists. Updating password...</p>";
    
    // Update existing user
    $update_sql = "UPDATE users SET password = ?, email = ?, role = ? WHERE username = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssss", $hashed_password, $test_email, $test_role, $test_username);
    
    if ($update_stmt->execute()) {
        echo "<p style='color:green;'>✅ User '$test_username' password updated successfully!</p>";
    } else {
        echo "<p style='color:red;'>❌ Failed to update user password: " . $conn->error . "</p>";
    }
    $update_stmt->close();
} else {
    // Create new user
    $insert_sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ssss", $test_username, $test_email, $hashed_password, $test_role);
    
    if ($insert_stmt->execute()) {
        echo "<p style='color:green;'>✅ User '$test_username' created successfully!</p>";
    } else {
        echo "<p style='color:red;'>❌ Failed to create user: " . $conn->error . "</p>";
    }
    $insert_stmt->close();
}

echo "<h3>Test Login Credentials:</h3>";
echo "<p><strong>Username:</strong> $test_username</p>";
echo "<p><strong>Password:</strong> $test_password</p>";
echo "<p><strong>Email:</strong> $test_email</p>";
echo "<p><strong>Role:</strong> $test_role</p>";

echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Try logging in with the credentials above</li>";
echo "<li>If it works, the issue was with the existing user passwords</li>";
echo "<li>If it doesn't work, there might be a deeper issue with the login system</li>";
echo "</ol>";

$conn->close();
?>
