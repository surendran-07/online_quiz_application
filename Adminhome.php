<?php
// Database connection settings
$servername = "localhost"; // Change if the database is on a remote server
$username = "root";        // Database username
$password = "";            // Database password
$dbname = "your_database_name"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data from the users table
// Replace 'username' and 'email' with the actual column names in your table
$userQuery = "SELECT id, username AS name, email FROM users";
$userResult = $conn->query($userQuery);

// Check if the query was successful
if (!$userResult) {
    die("Error fetching users data: " . $conn->error);
}

// HTML structure for displaying data
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Users List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Users List</h1>";

// Check if data is available
if ($userResult->num_rows > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>";

    // Loop through the data and display it
    while ($row = $userResult->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No users found.</p>";
}

// Close the database connection
$conn->close();

echo "</body>
</html>";
?>
