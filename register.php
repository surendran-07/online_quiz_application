<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$host = 'localhost';
$db_name = 'brain_buddies';
$db_user = 'root'; // Replace with your database username
$db_password = ''; // Replace with your database password

// Connect to the database
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message variable
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match. Please try again.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            $error_message = "Database error. Please try again later.";
        } else {
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            if ($stmt->execute()) {
                // Get the new user's ID
                $user_id = $conn->insert_id;

                // Create a personalized table for the user
                $user_table_name = "user_" . $user_id;
                $create_table_sql = "CREATE TABLE $user_table_name (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    data_key VARCHAR(100) NOT NULL,
                    data_value TEXT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";

                if ($conn->query($create_table_sql) === TRUE) {
                    // Redirect to login page
                    header("Location: login.php");
                    exit();
                } else {
                    $error_message = "Error creating user-specific table: " . $conn->error;
                }
            } else {
                $error_message = "Registration failed: " . $stmt->error; // Provide error feedback
            }

            // Close the statement
            $stmt->close();
        }
    }
}

// Close the database connection
$conn->close();
?>
