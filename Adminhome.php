<?php
<<<<<<< HEAD
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
=======
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Your database password
$dbname = "brain_buddies"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
>>>>>>> 8cb96247e910f4f3b899b8e9efba61f123677b20

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

<<<<<<< HEAD
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
=======
// Fetch faculty data
$facultyQuery = "SELECT id, name, email FROM faculty";
$facultyResult = $conn->query($facultyQuery);

// Fetch user data
$userQuery = "SELECT id, name, email FROM users";
$userResult = $conn->query($userQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users and Faculty</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <!-- Faculty Management Section -->
    <section id="faculty-section" class="table-section mb-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Faculty Management</h2>
      </div>
      <div class="table-responsive shadow-sm rounded">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
>>>>>>> 8cb96247e910f4f3b899b8e9efba61f123677b20
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
<<<<<<< HEAD
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
=======
          </thead>
          <tbody id="facultyTable">
            <?php
            if ($facultyResult->num_rows > 0) {
                while ($row = $facultyResult->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>
                          <div class='dropdown'>
                            <button class='dropdown-toggle action-dots' type='button' id='dropdownMenuButton1' data-bs-toggle='dropdown' aria-expanded='false'>
                              <span>●●●</span>
                            </button>
                            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton1'>
                              <li><button class='dropdown-item edit-btn'>Edit</button></li>
                              <li><button class='dropdown-item text-danger remove-btn'>Remove</button></li>
                            </ul>
                          </div>
                        </td>
                      </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No Faculty Found</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- User Management Section -->
    <section id="user-section" class="table-section">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>User Management</h2>
      </div>
      <div class="table-responsive shadow-sm rounded">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
>>>>>>> 8cb96247e910f4f3b899b8e9efba61f123677b20
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
<<<<<<< HEAD
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
=======
          </thead>
          <tbody id="userTable">
            <?php
            if ($userResult->num_rows > 0) {
                while ($row = $userResult->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>
                          <div class='dropdown'>
                            <button class='dropdown-toggle action-dots' type='button' id='dropdownMenuButton1' data-bs-toggle='dropdown' aria-expanded='false'>
                              <span>●●●</span>
                            </button>
                            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuButton1'>
                              <li><button class='dropdown-item edit-btn'>Edit</button></li>
                              <li><button class='dropdown-item text-danger remove-btn'>Remove</button></li>
                            </ul>
                          </div>
                        </td>
                      </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No Users Found</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
>>>>>>> 8cb96247e910f4f3b899b8e9efba61f123677b20
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
