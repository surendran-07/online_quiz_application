<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.html');
    exit();
}

// Database connection
$host = '127.0.0.1';
$db = 'brain_buddies';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete and edit actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_user'])) {
        $userIdToDelete = (int) $_POST['delete_user'];
        $conn->query("DELETE FROM users WHERE id = $userIdToDelete");
    }

    if (isset($_POST['delete_faculty'])) {
        $facultyIdToDelete = (int) $_POST['delete_faculty'];
        $conn->query("DELETE FROM faculty WHERE id = $facultyIdToDelete");
    }
}

// Fetch users and faculty lists
$usersResult = $conn->query("SELECT * FROM users");
$users = $usersResult->fetch_all(MYSQLI_ASSOC);

$facultyResult = $conn->query("SELECT * FROM faculty");
$faculty = $facultyResult->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .logout-btn {
            margin-bottom: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="logout-btn">
        <a href="?logout=true" class="btn btn-danger">Logout</a>
    </div>

    <h1>Admin Dashboard</h1>

    <!-- User List -->
    <h3>Users</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="delete_user" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Faculty List -->
    <h3>Faculty</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($faculty as $fac): ?>
                <tr>
                    <td><?php echo $fac['id']; ?></td>
                    <td><?php echo $fac['username']; ?></td>
                    <td><?php echo $fac['email']; ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="delete_faculty" value="<?php echo $fac['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
