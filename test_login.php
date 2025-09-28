<?php
// test_login.php - Test script to check database connection and users
require_once 'db_connect.php';

echo "<h2>Database Connection Test</h2>";

if ($conn->connect_error) {
    echo "<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color:green;'>Database connection successful!</p>";
    
    // Check if users table exists and get user data
    $sql = "SELECT id, username, email, role FROM users";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        echo "<h3>Users in database:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr>";
        
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['role'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Test password verification
        echo "<h3>Password Test:</h3>";
        $test_username = 'admin';
        $test_password = 'password123'; // Try common passwords
        
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $test_username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $hashed_password = $row['password'];
            echo "<p>Testing password for user 'admin':</p>";
            echo "<p>Stored hash: " . substr($hashed_password, 0, 20) . "...</p>";
            
            if (password_verify($test_password, $hashed_password)) {
                echo "<p style='color:green;'>Password 'password123' is CORRECT for admin user!</p>";
            } else {
                echo "<p style='color:red;'>Password 'password123' is INCORRECT for admin user.</p>";
                
                // Try other common passwords
                $common_passwords = ['admin', '123456', 'password', 'supotako', 'poginaman'];
                foreach ($common_passwords as $pwd) {
                    if (password_verify($pwd, $hashed_password)) {
                        echo "<p style='color:green;'>Password '$pwd' is CORRECT for admin user!</p>";
                        break;
                    }
                }
            }
        }
        $stmt->close();
        
    } else {
        echo "<p style='color:red;'>No users found in database or table doesn't exist.</p>";
    }
}

$conn->close();
?>



