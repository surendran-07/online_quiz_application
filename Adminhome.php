<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "brain_buddies"; // Replace with your actual database name
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$edit_state_user = false;
$user_to_edit = null;
$edit_state_faculty = false;
$faculty_to_edit = null;

// Create or Update User
if (isset($_POST['save_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (isset($_POST['user_id']) && $_POST['user_id'] != '') {
        // Update existing user
        $user_id = $_POST['user_id'];
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $email, $password, $user_id);
    } else {
        // Add new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: adminhome.php"); // Redirect to avoid form resubmission
    exit();
}

// Edit User: Fetch the user data to populate the form
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_state_user = true;
    $result = $conn->query("SELECT * FROM users WHERE id=$id");
    $user_to_edit = $result->fetch_assoc();
}

// Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: adminhome.php"); // Redirect to avoid form resubmission
    exit();
}

// Create or Update Faculty
if (isset($_POST['save_faculty'])) {
    $faculty_username = $_POST['faculty_username'];
    $faculty_email = $_POST['faculty_email'];
    $faculty_password = $_POST['faculty_password'];
    
    if (isset($_POST['faculty_id']) && $_POST['faculty_id'] != '') {
        // Update existing faculty
        $faculty_id = $_POST['faculty_id'];
        $stmt = $conn->prepare("UPDATE faculty SET username=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("sssi", $faculty_username, $faculty_email, $faculty_password, $faculty_id);
    } else {
        // Add new faculty
        $stmt = $conn->prepare("INSERT INTO faculty (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $faculty_username, $faculty_email, $faculty_password);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: adminhome.php"); // Redirect to avoid form resubmission
    exit();
}

// Edit Faculty: Fetch the faculty data to populate the form
if (isset($_GET['edit_faculty'])) {
    $id = $_GET['edit_faculty'];
    $edit_state_faculty = true;
    $result = $conn->query("SELECT * FROM faculty WHERE id=$id");
    if ($result) {
        $faculty_to_edit = $result->fetch_assoc();
    }
}

// Delete Faculty
if (isset($_GET['delete_faculty'])) {
    $id = $_GET['delete_faculty'];
    $conn->query("DELETE FROM faculty WHERE id=$id");
    header("Location: adminhome.php"); // Redirect to avoid form resubmission
    exit();
}

// Fetch Users
$userQuery = "SELECT * FROM users";
$userResult = $conn->query($userQuery);

// Fetch Faculty
$facultyQuery = "SELECT * FROM faculty";
$facultyResult = $conn->query($facultyQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Home - Brain Buddies</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  font-family: Arial, sans-serif;
  background-color: #e6e6e6;
  margin: 0;
  padding: 0;
  color: #4b2c5e;
}
.admin-header {
  background-color: #673ab7;
  color: white;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.table-section {
  padding: 20px;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
</style>
</head>
<body>
<header class="admin-header py-3 px-4 d-flex justify-content-between align-items-center shadow">
<h1 class="h5 mb-0">Welcome Admin</h1>
<button class="btn btn-outline-light btn-sm">Logout</button>
</header>
<main class="container my-5">
<section id="faculty-section" class="table-section">
<h2>Faculty Management</h2>
<form method="POST" action="">
<input type="hidden" name="faculty_id" value="<?php echo isset($faculty_to_edit) ? htmlspecialchars($faculty_to_edit['id']) : ''; ?>">
<div class="mb-3">
<label for="faculty_username" class="form-label">Username</label>
<input type="text" class="form-control" name="faculty_username" value="<?php echo isset($faculty_to_edit) ? htmlspecialchars($faculty_to_edit['username']) : ''; ?>" required>
</div>
<div class="mb-3">
<label for="faculty_email" class="form-label">Email</label>
<input type="email" class="form-control" name="faculty_email" value="<?php echo isset($faculty_to_edit) ? htmlspecialchars($faculty_to_edit['email']) : ''; ?>" required>
</div>
<div class="mb-3">
<label for="faculty_password" class="form-label">Password</label>
<input type="password" class="form-control" name="faculty_password" required>
</div>
<button type="submit" name="save_faculty" class="btn btn-primary"><?php echo isset($faculty_to_edit) ? 'Update Faculty' : 'Add Faculty'; ?></button>
</form>
<div class="table-responsive mt-4">
<table class="table table-striped table-hover align-middle">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Username</th>
<th>Email</th>
<th>Password</th> <!-- Added Password Column -->
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php if ($facultyResult && $facultyResult->num_rows > 0) { 
while ($row = $facultyResult->fetch_assoc()) { ?>
<tr>
<td><?php echo htmlspecialchars($row['id']); ?></td>
<td><?php echo htmlspecialchars($row['username']); ?></td>
<td><?php echo htmlspecialchars($row['email']); ?></td>
<td><?php echo htmlspecialchars($row['password']); ?></td> <!-- Display Password -->
<td>
<a href='adminhome.php?edit_faculty=<?php echo htmlspecialchars($row['id']); ?>' class='btn btn-info'>Edit</a> 
<a href='adminhome.php?delete_faculty=<?php echo htmlspecialchars($row['id']); ?>' class='btn btn-danger' onclick='return confirm("Are you sure you want to delete this faculty member?")'>Delete</a> 
</td>
</tr>
<?php } } else { ?>
<tr><td colspan='5' class='text-center'>No faculty found</td></tr> <!-- Adjusted colspan -->
<?php } ?>
</tbody>
</table>
</div>
</section>

<section id="user-section" class="table-section">
<h2>User Management</h2>
<form method="POST" action="">
<input type="hidden" name="user_id" value="<?php echo isset($user_to_edit) ? htmlspecialchars($user_to_edit['id']) : ''; ?>">
<div class="mb-3">
<label for="username" class="form-label">Username</label>
<input type="text" class="form-control" name="username" value="<?php echo isset($user_to_edit) ? htmlspecialchars($user_to_edit['username']) : ''; ?>" required>
</div>
<div class="mb-3">
<label for="email" class="form-label">Email</label>
<input type="email" class="form-control" name="email" value="<?php echo isset($user_to_edit) ? htmlspecialchars($user_to_edit['email']) : ''; ?>" required>
</div>
<div class="mb-3">
<label for="password" class="form-label">Password</label>
<input type="password" class="form-control" name="password" required>
</div>

<button type="submit" name="save_user" class="btn btn-primary"><?php echo isset($user_to_edit) ? 'Update User' : 'Add User'; ?></button>

</form>

<div class="table-responsive mt-4">
<table class="table table-striped table-hover align-middle">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Username</th>
<th>Email</th>
<th>Password</th> <!-- Added Password Column -->
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php if ($userResult && $userResult->num_rows > 0) { 
while ($row = $userResult->fetch_assoc()) { ?>
<tr>

<td><?php echo htmlspecialchars($row['id']); ?></td>

<td><?php echo htmlspecialchars($row['username']); ?></td>

<td><?php echo htmlspecialchars($row['email']); ?></td>

<td><?php echo htmlspecialchars($row['password']); ?></td> <!-- Display Password -->

<td><a href='adminhome.php?edit=<?php echo htmlspecialchars($row['id']); ?>' class='btn btn-info'>Edit</a> 
<a href='adminhome.php?delete=<?php echo htmlspecialchars($row['id']); ?>' class='btn btn-danger' onclick='return confirm("Are you sure you want to delete this user?")'>Delete</a></td>

</tr>

<?php } } else { ?>
<tr><td colspan='5' class='text-center'>No users found</td></tr> <!-- Adjusted colspan -->
<?php } ?>

</tbody>

</table>

</div>

</section>

</main>

<?php 
$conn->close(); 
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

