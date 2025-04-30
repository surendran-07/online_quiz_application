<?php
session_start();

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
    $faculty_password = $_POST['password'];

    // Prepare and execute SQL to fetch user data
    $sql = "SELECT * FROM faculty WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $faculty_username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists and password matches
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($faculty_password, $row['password'])) {
            // Set session variable and redirect to the dashboard
            $_SESSION['faculty_id'] = $row['id'];
            $_SESSION['faculty_username'] = $row['username'];
            header("Location: facultyhome.html"); // Redirect to faculty dashboard
            exit();
        } else {
            // Incorrect password
            echo "<script>alert('Incorrect password!');</script>";
        }
    } else {
        // User not found
        echo "<script>alert('Username not found!');</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Login</title>
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
            text-align: center;
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
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            font-size: 1rem;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            background: #f9f9f9;
            transition: all 0.3s ease-in-out;
        }

        .form-group input:focus {
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
            transform: scale(1);
            transition: transform 0.2s ease-in-out;
        }

        .g-signin2:hover {
            transform: scale(1.05);
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Faculty Login</h1>
        <form method="POST" action="faculty-login.php" id="faculty-login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your faculty username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <div class="google-login">
            <p>Or login with:</p>
            <div class="g-signin2" data-onsuccess="onSignIn"></div>
        </div>

        <p class="redirect">
            Don't have an account? <a href="faculty-register.php">Register here</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        // google login script (optional)
        // function onSignIn(googleUser) {
        //     const profile = googleUser.getBasicProfile();
        //     const idToken = googleUser.getAuthResponse().id_token;

        //     fetch('/api/faculty/google-login', {
        //         method: 'POST',
        //         headers: { 'Content-Type': 'application/json' },
        //         body: JSON.stringify({ token: idToken })
        //     })
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.success) {
        //                 alert('Login successful!');
        //                 window.location.href = '/faculty-dashboard.php';
        //             } else {
        //                 alert('Login failed!');
        //             }
        //         })
        //         .catch(error => console.error('Error:', error));
        // }
    </script>
</body>
</html>
