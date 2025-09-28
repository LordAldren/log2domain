<?php
// debug_login.php - Debug script to check login issues
require_once 'db_connect.php';

echo "<h2>Login Debug Information</h2>";

// Test database connection
if ($conn->connect_error) {
    echo "<p style='color:red;'>❌ Database connection failed: " . $conn->connect_error . "</p>";
    exit;
} else {
    echo "<p style='color:green;'>✅ Database connection successful!</p>";
}

// Check if users table exists
$sql = "SHOW TABLES LIKE 'users'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "<p style='color:red;'>❌ Users table does not exist!</p>";
    exit;
} else {
    echo "<p style='color:green;'>✅ Users table exists</p>";
}

// Get all users
$sql = "SELECT id, username, email, role, password FROM users";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<h3>Users in database:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Password Hash (first 20 chars)</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['role'] . "</td>";
        echo "<td>" . substr($row['password'], 0, 20) . "...</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test specific user login
    echo "<h3>Testing Login for 'admin' user:</h3>";
    $test_username = 'admin';
    $test_passwords = ['password123', 'admin', '123456', 'password', 'supotako', 'poginaman', 'reneldriver'];
    
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $test_username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $hashed_password = $row['password'];
        echo "<p>Testing passwords for user 'admin':</p>";
        
        $found = false;
        foreach ($test_passwords as $pwd) {
            if (password_verify($pwd, $hashed_password)) {
                echo "<p style='color:green;'>✅ Password '$pwd' is CORRECT for admin user!</p>";
                $found = true;
                break;
            } else {
                echo "<p style='color:red;'>❌ Password '$pwd' is incorrect</p>";
            }
        }
        
        if (!$found) {
            echo "<p style='color:red;'>❌ None of the test passwords worked!</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ User 'admin' not found in database!</p>";
    }
    $stmt->close();
    
} else {
    echo "<p style='color:red;'>❌ No users found in database!</p>";
}

$conn->close();
?>
