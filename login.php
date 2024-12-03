<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$host = 'localhost';
$db_name = 'brain_buddies';
$db_user = 'root'; // Replace with your database username
$db_password = ''; // Replace with your database password

// Start session to store login state
session_start();

// Connect to the database
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get username and password from form
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Query to check if the user exists with the provided username
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $db_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $db_password)) {
            // Set session variable to indicate user is logged in
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $db_username;
            
            // Redirect to user home page
            header("Location: user home.html");
            exit();
        } else {
            // Incorrect password
            $login_error = "Incorrect password. Please try again.";
        }
    } else {
        // User not found
        $login_error = "Username not found. Please register.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
