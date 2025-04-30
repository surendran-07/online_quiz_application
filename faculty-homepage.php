
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "brain_buddies";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch general quiz results
$general_quiz_sql = "SELECT username, score FROM quiz_results ORDER BY score DESC";
$general_quiz_result = $conn->query($general_quiz_sql);

// Fetch maths quiz results
$maths_quiz_sql = "SELECT username, score FROM maths_quiz_results ORDER BY score DESC";
$maths_quiz_result = $conn->query($maths_quiz_sql);

// Fetch physics quiz results
$physics_quiz_sql = "SELECT username, score FROM physics_quiz_results ORDER BY score DESC";
$physics_quiz_result = $conn->query($physics_quiz_sql);

// Fetch chemistry quiz results
$chemistry_quiz_sql = "SELECT username, score FROM chemistry_quiz_results ORDER BY score DESC";
$chemistry_quiz_result = $conn->query($chemistry_quiz_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            padding: 20px;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .dashboard-header {
            background-color: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .dashboard-header h1 {
            font-size: 32px;
            margin-bottom: 5px;
        }
        
        .leaderboard-section {
            padding: 30px;
        }
        
        .section-title {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
            display: inline-block;
        }
        
        .leaderboard-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        
        .leaderboard-table th {
            background-color: #3498db;
            color: white;
            padding: 15px;
            text-align: left;
        }
        
        .leaderboard-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e1e5eb;
        }
        
        .leaderboard-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .leaderboard-table tr:hover {
            background-color: #e8f4fc;
        }
        
        .rank {
            font-weight: bold;
            color: #2c3e50;
            width: 60px;
        }
        
        .top-1 {
            background-color: #fff8e1 !important;
        }
        
        .top-1 .rank {
            color: #ff9800;
        }
        
        .top-2 {
            background-color: #f5f5f5 !important;
        }
        
        .top-3 {
            background-color: #fff3e0 !important;
        }
        
        .score {
            font-weight: bold;
            color: #27ae60;
        }
        
        .no-results {
            text-align: center;
            padding: 30px;
            color: #7f8c8d;
            font-style: italic;
        }
        
        .table-actions {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-name-input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            width: 300px;
        }
        
        .update-name-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .update-name-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Faculty Dashboard</h1>
        </div>
        
        <div class="leaderboard-section">
            <h2 class="section-title">General Quiz Leaderboard</h2>
            
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Username</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($general_quiz_result && $general_quiz_result->num_rows > 0): ?>
                        <?php 
                        $rank = 1;
                        while ($row = $general_quiz_result->fetch_assoc()): 
                            $row_class = '';
                            if ($rank == 1) $row_class = 'top-1';
                            elseif ($rank == 2) $row_class = 'top-2';
                            elseif ($rank == 3) $row_class = 'top-3';
                        ?>
                            <tr class="<?php echo $row_class; ?>">
                                <td class="rank"><?php echo $rank; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td class="score"><?php echo $row['score']; ?>/10</td>
                            </tr>
                        <?php 
                            $rank++;
                        endwhile; 
                        ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="no-results">No quiz results available yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- Maths Quiz Leaderboard Table -->
            <div class="table-actions">
                <h2 class="section-title">Maths Quiz Leaderboard</h2>
                <div>
                    <input type="text" class="table-name-input" placeholder="Enter quiz name" value="Maths Quiz">
                    <button class="update-name-btn">Update Name</button>
                </div>
            </div>
            
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Username</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($maths_quiz_result && $maths_quiz_result->num_rows > 0): ?>
                        <?php 
                        $rank = 1;
                        while ($row = $maths_quiz_result->fetch_assoc()): 
                            $row_class = '';
                            if ($rank == 1) $row_class = 'top-1';
                            elseif ($rank == 2) $row_class = 'top-2';
                            elseif ($rank == 3) $row_class = 'top-3';
                        ?>
                            <tr class="<?php echo $row_class; ?>">
                                <td class="rank"><?php echo $rank; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td class="score"><?php echo $row['score']; ?>/10</td>
                            </tr>
                        <?php 
                            $rank++;
                        endwhile; 
                        ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="no-results">No maths quiz results available yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- Physics Quiz Leaderboard Table -->
            <div class="table-actions">
                <h2 class="section-title">Physics Quiz Leaderboard</h2>
                <div>
                    <input type="text" class="table-name-input" placeholder="Enter quiz name" value="Physics Quiz">
                    <button class="update-name-btn">Update Name</button>
                </div>
            </div>
            
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Username</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($physics_quiz_result && $physics_quiz_result->num_rows > 0): ?>
                        <?php 
                        $rank = 1;
                        while ($row = $physics_quiz_result->fetch_assoc()): 
                            $row_class = '';
                            if ($rank == 1) $row_class = 'top-1';
                            elseif ($rank == 2) $row_class = 'top-2';
                            elseif ($rank == 3) $row_class = 'top-3';
                        ?>
                            <tr class="<?php echo $row_class; ?>">
                                <td class="rank"><?php echo $rank; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td class="score"><?php echo $row['score']; ?>/10</td>
                            </tr>
                        <?php 
                            $rank++;
                        endwhile; 
                        ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="no-results">No physics quiz results available yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- Chemistry Quiz Leaderboard Table -->
            <div class="table-actions">
                <h2 class="section-title">Chemistry Quiz Leaderboard</h2>
                <div>
                    <input type="text" class="table-name-input" placeholder="Enter quiz name" value="Chemistry Quiz">
                    <button class="update-name-btn">Update Name</button>
                </div>
            </div>
            
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Username</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($chemistry_quiz_result && $chemistry_quiz_result->num_rows > 0): ?>
                        <?php 
                        $rank = 1;
                        while ($row = $chemistry_quiz_result->fetch_assoc()): 
                            $row_class = '';
                            if ($rank == 1) $row_class = 'top-1';
                            elseif ($rank == 2) $row_class = 'top-2';
                            elseif ($rank == 3) $row_class = 'top-3';
                        ?>
                            <tr class="<?php echo $row_class; ?>">
                                <td class="rank"><?php echo $rank; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td class="score"><?php echo $row['score']; ?>/10</td>
                            </tr>
                        <?php 
                            $rank++;
                        endwhile; 
                        ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="no-results">No chemistry quiz results available yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Simple JavaScript to handle table name updates
        document.querySelectorAll('.update-name-btn').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const sectionTitle = this.closest('.table-actions').querySelector('.section-title');
                sectionTitle.textContent = input.value + ' Leaderboard';
            });
        });
    </script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>