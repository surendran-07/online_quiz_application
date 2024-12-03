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
            header("Location: userhome.html");
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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <script src="https://apis.google.com/js/platform.js" async defer></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', Arial, sans-serif;
            background-image: url(bg.avif); /* Replace with your image URL */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .login-container {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            animation: slideIn 0.5s ease-in-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2.2rem;
            color: #4a4a4a;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            position: relative;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
            position: relative;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            font-size: 1rem;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            background: #f9f9f9;
            transition: all 0.3s ease-in-out;
        }

        input:focus {
            border-color: #6e8efb;
            background: #ffffff;
            outline: none;
            box-shadow: 0 4px 8px rgba(110, 142, 251, 0.1);
        }

        .btn {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            background: #6e8efb;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            margin-top: 10px;
            text-transform: uppercase;
        }

        .btn:hover {
            background: #5b75e4;
            box-shadow: 0 6px 12px rgba(94, 117, 251, 0.2);
            transform: scale(1.02);
        }

        .google-login {
            margin-top: 30px;
        }

        .google-login p {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 10px;
        }

        .g-signin2 {
            display: inline-block;
            width: 240px;
            transition: transform 0.2s ease-in-out;
        }

        .g-signin2:hover {
            transform: scale(1.05);
        }

        .error-message {
            color: red;
            text-align: center;
            font-size: 1rem;
            margin-top: 10px;
        }

    </style>
</head>
<body>
    <div class="login-container">
        <h1>User Login</h1>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>

        <?php
        // Display error message if any
        if (isset($login_error)) {
            echo "<div class='error-message'>$login_error</div>";
        }
        ?>
        
        <p class="redirect">
            Don't have an account? <a href="user-register.html">Register here</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
