<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page - Quiz Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* General Styles */
body {
  font-family: Arial, sans-serif;
  background-color: #e6e0ff; /* Light purple background */
  margin: 0;
  padding: 0;
  color: #4b2c5e; /* Darker purple for text */
}

/* Header */
.admin-header {
  background-color: #673ab7; /* Deep purple */
  color: white;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.admin-header h1 {
  font-size: 1.5rem;
  font-weight: bold;
}

.admin-header img {
  border: 2px solid white;
}

/* Navigation */
.navigation {
  background-color: #f3e5f5; /* Soft lavender */
}

.navigation button {
  color: #673ab7;
  border-color: #673ab7;
}

.navigation button:hover {
  background-color: #673ab7;
  color: white;
}

/* Main Content */
h2 {
  color: #4b2c5e; /* Dark purple */
}

.table-section {
  padding: 20px;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f3e5f5; /* Lavender stripes */
}

.table-dark {
  background-color: #673ab7;
  color: white;
}

/* Buttons */
.btn-primary {
  background-color: #673ab7;
  border-color: #673ab7;
}

.btn-primary:hover {
  background-color: #512da8;
  border-color: #512da8;
}

.btn-outline-dark {
  color: #673ab7;
  border-color: #673ab7;
}

.btn-outline-dark:hover {
  background-color: #673ab7;
  color: white;
}

/* Modals */
.modal-content {
  border-radius: 8px;
  background-color: #f3e5f5; /* Lavender */
  color: #4b2c5e;
}

.modal-header {
  background-color: #673ab7;
  color: white;
}

.modal-footer .btn-primary {
  background-color: #512da8;
  border-color: #512da8;
}

.modal-footer .btn-secondary {
  background-color: #d1c4e9; /* Soft grayish lavender */
}

/* Dropdown */
.dropdown-menu {
  background-color: #f3e5f5;
  color: #4b2c5e;
}

.dropdown-item:hover {
  background-color: #673ab7;
  color: white;
}

  </style>
</head>
<body>
  <header class="admin-header bg-dark text-white py-3 px-4 d-flex justify-content-between align-items-center shadow">
    <div class="d-flex align-items-center">
      <img src="admin-avator.jpg" alt="Admin Avatar" class="rounded-circle me-2" width="50" height="50">
      <h1 class="h5 mb-0">Welcome Admin</h1>
    </div>
    <button class="btn btn-outline-light btn-sm">Logout</button>
  </header>

  <nav class="navigation bg-light py-3 shadow-sm d-flex justify-content-center">
    <button class="btn btn-outline-dark mx-2" id="faculty-btn">Manage Faculty</button>
    <button class="btn btn-outline-dark mx-2" id="user-btn">Manage Users</button>
  </nav>

  <main class="container my-5">
    <!-- Faculty Section -->
    <section id="faculty-section" class="table-section">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Faculty Management</h2>
        <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFacultyModal">Add Faculty</button> -->
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
            <!-- Faculty rows will be added here -->
          </tbody>
        </table>
      </div>
    </section>

    <!-- User Section -->
    <section id="user-section" class="table-section d-none">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>User Management</h2>
        <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button> -->
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
            <!-- User rows will be added here -->
          </tbody>
        </table>
      </div>
    </section>
  </main>

  

  <!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          <div class="mb-3">
            <label for="editName" class="form-label">Name</label>
            <input type="text" class="form-control" id="editName" required>
          </div>
          <div class="mb-3">
            <label for="editEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="editEmail" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="editForm">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Remove Confirmation Modal -->
<div class="modal fade" id="removeModal" tabindex="-1" aria-labelledby="removeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="removeModalLabel">Confirm Removal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to remove this record?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmRemove">Remove</button>
      </div>
    </div>
  </div>
</div>


<script> 
// edit script
  let currentRow; // To track which row is being edited

  // Add click event listener to dynamically created edit buttons
  document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('edit-btn')) {
      const row = e.target.closest('tr');
      currentRow = row; // Store the current row being edited
      const name = row.children[1].textContent;
      const email = row.children[2].textContent;

      // Populate the modal with the current values
      document.getElementById('editName').value = name;
      document.getElementById('editEmail').value = email;

      // Show the edit modal
      const editModal = new bootstrap.Modal(document.getElementById('editModal'));
      editModal.show();
    }
  });

  // Handle form submission for editing
  document.getElementById('editForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Get updated values
    const updatedName = document.getElementById('editName').value;
    const updatedEmail = document.getElementById('editEmail').value;

    // Update the table row
    currentRow.children[1].textContent = updatedName;
    currentRow.children[2].textContent = updatedEmail;

    // Close the modal
    const editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
    editModal.hide();
  });
</script>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Add Faculty Logic
    document.getElementById('facultyForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const facultyName = document.getElementById('facultyName').value;
      const facultyEmail = document.getElementById('facultyEmail').value;

      const facultyRow = `<tr>
                            <td>1</td>
                            <td>${facultyName}</td>
                            <td>${facultyEmail}</td>
                            <td>
                              <div class="dropdown">
                                <button class="dropdown-toggle action-dots" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  <span>●●●</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                  <li><button class="dropdown-item edit-btn">Edit</button></li>
                                  <li><button class="dropdown-item text-danger remove-btn">Remove</button></li>
                                </ul>
                              </div>
                            </td>
                          </tr>`;
      document.getElementById('facultyTable').innerHTML += facultyRow;

      // Manually close the modal using Bootstrap's Modal API
      const facultyModal = new bootstrap.Modal(document.getElementById('addFacultyModal'));
      facultyModal.hide(); // Close modal after saving data

      // Clear the form fields after closing
      document.getElementById('facultyForm').reset();
    });

    // Add User Logic
    document.getElementById('userForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const userName = document.getElementById('userName').value;
      const userEmail = document.getElementById('userEmail').value;

      const userRow = `<tr>
                         <td>1</td>
                         <td>${userName}</td>
                         <td>${userEmail}</td>
                         <td>
                           <div class="dropdown">
                             <button class="dropdown-toggle action-dots" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                               <span>●●●</span>
                             </button>
                             <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                               <li><button class="dropdown-item edit-btn">Edit</button></li>
                               <li><button class="dropdown-item text-danger remove-btn">Remove</button></li>
                             </ul>
                           </div>
                         </td>
                       </tr>`;
      document.getElementById('userTable').innerHTML += userRow;

      // Manually close the modal using Bootstrap's Modal API
      const userModal = new bootstrap.Modal(document.getElementById('addUserModal'));
      userModal.hide(); // Close modal after saving data

      // Clear the form fields after closing
      document.getElementById('userForm').reset();
    });
  </script>





<script>
  // remove script
  let rowToRemove = null; // To track which row is being removed

  // Show the modal when Remove button is clicked
  document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('remove-btn')) {
      rowToRemove = e.target.closest('tr'); // Store the current row
      const removeModal = new bootstrap.Modal(document.getElementById('removeModal'));
      removeModal.show(); // Show the modal
    }
  });

  // Confirm removal
  document.getElementById('confirmRemove').addEventListener('click', function () {
    if (rowToRemove) {
      rowToRemove.remove(); // Remove the row
      rowToRemove = null; // Reset the variable

      // Close the modal
      const removeModal = bootstrap.Modal.getInstance(document.getElementById('removeModal'));
      removeModal.hide();

      // Optional: Show a success message
      alert("Record removed successfully!");
    }
  });
</script>


  
  <script src="admin-homepage.js"></script>
</body>
</html>
