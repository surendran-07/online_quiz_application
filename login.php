<?php
require 'vendor/autoload.php'; // Ensure you have installed the Google API Client Library using Composer

use Google\Client;

// Database connection details
$host = 'localhost';
$db_name = 'brain_buddies';
$db_user = 'root'; // Replace with your database username
$db_password = ''; // Replace with your database password

// Start session
session_start();

// Connect to the database
$conn = new mysqli($host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle traditional login
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $db_password);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $db_username;
            header("Location: userhome.php");
            exit();
        } else {
            $login_error = "Incorrect password. Please try again.";
        }
    } else {
        header("Location:register.html");
        exit();
    }

    $stmt->close();
}

// Handle Google Sign-In
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['credential'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id_token = $data['credential'] ?? null;

    if ($id_token) {
        $client = new Client(['client_id' => '175243861380-fn44prjkpgu9guocgtdt81jgc507iol2.apps.googleusercontent.com']);
        $payload = $client->verifyIdToken($id_token);

        if ($payload) {
            $google_id = $payload['sub'];
            $name = $payload['name'];
            $email = $payload['email'];

            $stmt = $conn->prepare("SELECT id FROM users WHERE google_id = ?");
            $stmt->bind_param("s", $google_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id);
                $stmt->fetch();
            } else {
                $stmt = $conn->prepare("INSERT INTO users (google_id, name, email) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $google_id, $name, $email);
                $stmt->execute();
                $user_id = $stmt->insert_id;
            }

            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $name;

            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid ID token']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No ID token provided']);
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', Arial, sans-serif;
            background-image: url('bg.avif');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 2rem;
        }
        .btn {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            background: #6e8efb;
            color: #fff;
            border: none;
            border-radius: 8px;
        }
        .g_id_signin {
            margin-top: 20px;
        }
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>User Login</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <?php if (!empty($login_error)) echo "<p class='error-message'>$login_error</p>"; ?>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <div id="g_id_onload"
             data-client_id="175243861380-fn44prjkpgu9guocgtdt81jgc507iol2.apps.googleusercontent.com"
             data-callback="handleCredentialResponse">
        </div>
        <div class="g_id_signin" data-type="standard"></div>
    </div>

    <script>
        function handleCredentialResponse(response) {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ credential: response.credential })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'userhome.html';
                } else {
                    alert('Google Sign-In failed: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
