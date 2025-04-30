<?php
// Database connection
$servername = "localhost";
$username = "root";  // Your database username
$password = "";      // Your database password
$dbname = "brain_buddies";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $faculty_username = $_POST['username'];
    $faculty_email = $_POST['email'];
    $faculty_password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Check if passwords match
    if ($faculty_password != $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($faculty_password, PASSWORD_DEFAULT);

        // Insert into faculty table
        $sql = "INSERT INTO faculty (username, email, password) VALUES ('$faculty_username', '$faculty_email', '$hashed_password')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration successful!'); window.location.href = 'faculty-login.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Register</title>
    
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

        .container {
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

    </style>
</head>
<body>
    <div class="container">
        <h1>Faculty Register</h1>
        <form method="POST" action="faculty-register.php" id="faculty-register-form">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter a username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter a password" required>
            </div>
            <div class="mb-3">
                <label for="confirm-password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        
        <div class="google-login">
            <p>Or register with:</p>
            <div class="g-signin2" data-onsuccess="onRegister"></div>
        </div>

        <p class="redirect">
            Already have an account? <a href="faculty-login.php">Login here</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
