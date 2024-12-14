<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Your database password
$dbname = "brain_buddies"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
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
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
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
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
