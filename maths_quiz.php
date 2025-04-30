<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "brain_buddies";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$show_quiz = false;
$quiz_username = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['check_username'])) {
    $user_name = $_POST['username'];
    $user_check_sql = "SELECT * FROM users WHERE username = '$user_name'";
    $user_check_result = $conn->query($user_check_sql);

    if ($user_check_result->num_rows > 0) {
        $show_quiz = true;
        $quiz_username = $user_name;
        $message = "<div class='alert success'>Welcome to the Mathematics Quiz, $user_name!</div>";
    } else {
        $message = "<div class='alert error'>User not registered. Please register first.</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_quiz'])) {
    $user_name = $_POST['username'];
    $score = 0;

    // Calculate score based on correct answers
    if (isset($_POST['q1']) && $_POST['q1'] == "1") $score += 1;
    if (isset($_POST['q2']) && $_POST['q2'] == "1") $score += 1;
    if (isset($_POST['q3']) && $_POST['q3'] == "1") $score += 1;
    if (isset($_POST['q4']) && $_POST['q4'] == "1") $score += 1;
    if (isset($_POST['q5']) && $_POST['q5'] == "1") $score += 1;
    if (isset($_POST['q6']) && $_POST['q6'] == "1") $score += 1;
    if (isset($_POST['q7']) && $_POST['q7'] == "1") $score += 1;
    if (isset($_POST['q8']) && $_POST['q8'] == "1") $score += 1;
    if (isset($_POST['q9']) && $_POST['q9'] == "1") $score += 1;
    if (isset($_POST['q10']) && $_POST['q10'] == "1") $score += 1;

    // Save to maths_quiz_results table
    $sql = "INSERT INTO maths_quiz_results (username, score) VALUES ('$user_name', $score)";
    $message = $conn->query($sql) === TRUE 
        ? "<div class='alert success'>Your mathematics quiz score of $score/10 has been saved!</div>" 
        : "<div class='alert error'>Error: " . $conn->error . "</div>";
    
    $show_quiz = false;
}

// Get top 5 maths quiz scores
$sql = "SELECT username, score FROM maths_quiz_results ORDER BY score DESC LIMIT 5";
$result = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mathematics Quiz</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #1e3c72, #2a5298);
            color: #fff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            width: 100%;
            background: #333;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }

        .navbar h1 {
            margin: 0;
            font-size: 24px;
        }

        .navbar .links {
            display: flex;
            gap: 20px;
        }

        .navbar .links a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
        }

        .profile {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .dropdown {
            position: absolute;
            top: 50px;
            right: 0;
            background: #fff;
            color: #444;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            min-width: 150px;
            z-index: 1000;
        }

        .dropdown a {
            text-decoration: none;
            color: #444;
            padding: 10px 15px;
            display: block;
            transition: background 0.2s ease-in-out;
        }

        .dropdown a:hover {
            background: #f2f2f2;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            color: #444;
            margin: 20px;
            border-radius: 10px;
        }

        .form-section {
            width: 100%;
            max-width: 400px;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-section input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #1e3c72;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-section input:focus {
            border-color: #2a5298;
        }

        .form-section button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background: #1e3c72;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .form-section button:hover {
            background: #2a5298;
        }

        /* Quiz Container */
        .quiz-container {
            display: <?php echo $show_quiz ? 'flex' : 'none'; ?>;
            flex-direction: column;
            width: 100%;
            height: calc(100vh - 60px);
            position: fixed;
            top: 60px;
            left: 0;
            background: #fff;
            z-index: 100;
            padding: 20px;
            overflow-y: auto;
            justify-content: center;
            align-items: center;
        }

        .quiz {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
        }

        .question {
            margin-bottom: 30px;
            display: none;
            text-align: left;
            padding: 0 20px;
        }

        .question.active {
            display: block;
        }

        .question p {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 25px;
            color: #333;
            text-align: center;
        }

        .question label {
            display: block;
            margin-bottom: 15px;
            font-size: 20px;
            color: #555;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-align: left;
            margin-left: auto;
            margin-right: auto;
            max-width: 600px;
        }

        .question label:hover {
            background: #f0f0f0;
        }

        .question input[type="radio"] {
            transform: scale(1.3);
            margin-right: 12px;
            vertical-align: middle;
        }

        .quiz-nav {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            position: sticky;
            bottom: 20px;
            background: #fff;
            padding: 15px 0;
            border-top: 1px solid #eee;
            max-width: 700px;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }

        .quiz-btn {
            background: #1e3c72;
            color: #fff;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .quiz-btn:hover {
            background: #2a5298;
        }

        .quiz-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }

        .progress {
            width: 100%;
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
            color: #1e3c72;
            font-size: 20px;
            max-width: 700px;
        }

        .leaderboard {
            width: 100%;
            max-width: 800px;
            margin-top: 20px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            display: <?php echo $show_quiz ? 'none' : 'block'; ?>;
        }

        .leaderboard table {
            width: 100%;
            border-collapse: collapse;
        }

        .leaderboard th,
        .leaderboard td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .leaderboard th {
            background: #1e3c72;
            color: #fff;
        }

        .footer {
            width: 100%;
            text-align: center;
            background: #333;
            color: #fff;
            padding: 10px;
            display: <?php echo $show_quiz ? 'none' : 'block'; ?>;
        }

        .welcome-message {
            font-size: 36px;
            margin-bottom: 20px;
            color: #1e3c72;
            text-align: center;
            display: <?php echo $show_quiz ? 'none' : 'block'; ?>;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-weight: bold;
        }

        .alert.success {
            background-color: #4CAF50;
            color: white;
        }

        .alert.error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Mathematics Quiz</h1>
        <div class="links">
            <!-- <a href="#leaderboard">Leaderboard</a>
            <a href="#contact">Contact</a> -->
        </div>
        <div class="profile" id="profile-container">
            <img src="admin-avator.jpg" alt="Profile Picture">
            <div class="dropdown" id="dropdown-menu">
                <a href="#profile">Profile</a>
                <a href="#settings">Settings</a>
                <a href="#logout">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h1 class="welcome-message">Welcome to the Mathematics Quiz</h1>
        <?php echo $message; ?>

        <?php if (!$show_quiz): ?>
        <div class="form-section">
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Enter Username" required>
                <button type="submit" name="check_username">Start Mathematics Quiz</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Full-page Quiz Container -->
        <?php if ($show_quiz): ?>
        <div class="quiz-container">
            <div class="quiz">
                <form method="POST" action="" id="quizForm">
                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($quiz_username); ?>">
                    
                    <div class="progress">Question <span id="currentQuestion">1</span> of 10</div>
                    
                    <!-- Question 1 -->
                    <div class="question active" id="question-1">
                        <p>1. What is the value of π (pi) rounded to two decimal places?</p>
                        <label><input type="radio" name="q1" value="1"> 3.14</label>
                        <label><input type="radio" name="q1" value="0"> 3.16</label>
                        <label><input type="radio" name="q1" value="0"> 3.12</label>
                    </div>

                    <!-- Question 2 -->
                    <div class="question" id="question-2">
                        <p>2. What is the square root of 144?</p>
                        <label><input type="radio" name="q2" value="1"> 12</label>
                        <label><input type="radio" name="q2" value="0"> 14</label>
                        <label><input type="radio" name="q2" value="0"> 10</label>
                    </div>

                    <!-- Question 3 -->
                    <div class="question" id="question-3">
                        <p>3. What is the sum of the angles in a triangle?</p>
                        <label><input type="radio" name="q3" value="1"> 180°</label>
                        <label><input type="radio" name="q3" value="0"> 90°</label>
                        <label><input type="radio" name="q3" value="0"> 360°</label>
                    </div>

                    <!-- Question 4 -->
                    <div class="question" id="question-4">
                        <p>4. What is the next number in the sequence: 2, 4, 8, 16, ...?</p>
                        <label><input type="radio" name="q4" value="1"> 32</label>
                        <label><input type="radio" name="q4" value="0"> 24</label>
                        <label><input type="radio" name="q4" value="0"> 64</label>
                    </div>

                    <!-- Question 5 -->
                    <div class="question" id="question-5">
                        <p>5. What is the area of a rectangle with length 8 and width 5?</p>
                        <label><input type="radio" name="q5" value="1"> 40</label>
                        <label><input type="radio" name="q5" value="0"> 13</label>
                        <label><input type="radio" name="q5" value="0"> 26</label>
                    </div>

                    <!-- Question 6 -->
                    <div class="question" id="question-6">
                        <p>6. Solve for x: 2x + 5 = 15</p>
                        <label><input type="radio" name="q6" value="1"> 5</label>
                        <label><input type="radio" name="q6" value="0"> 10</label>
                        <label><input type="radio" name="q6" value="0"> 7.5</label>
                    </div>

                    <!-- Question 7 -->
                    <div class="question" id="question-7">
                        <p>7. What is 3/4 expressed as a percentage?</p>
                        <label><input type="radio" name="q7" value="1"> 75%</label>
                        <label><input type="radio" name="q7" value="0"> 25%</label>
                        <label><input type="radio" name="q7" value="0"> 50%</label>
                    </div>

                    <!-- Question 8 -->
                    <div class="question" id="question-8">
                        <p>8. What is the value of 5! (5 factorial)?</p>
                        <label><input type="radio" name="q8" value="1"> 120</label>
                        <label><input type="radio" name="q8" value="0"> 25</label>
                        <label><input type="radio" name="q8" value="0"> 15</label>
                    </div>

                    <!-- Question 9 -->
                    <div class="question" id="question-9">
                        <p>9. What is the Pythagorean theorem for a right-angled triangle?</p>
                        <label><input type="radio" name="q9" value="1"> a² + b² = c²</label>
                        <label><input type="radio" name="q9" value="0"> a + b = c</label>
                        <label><input type="radio" name="q9" value="0"> a × b = c</label>
                    </div>

                    <!-- Question 10 -->
                    <div class="question" id="question-10">
                        <p>10. What is the prime factorization of 36?</p>
                        <label><input type="radio" name="q10" value="1"> 2² × 3²</label>
                        <label><input type="radio" name="q10" value="0"> 2 × 3 × 6</label>
                        <label><input type="radio" name="q10" value="0"> 4 × 9</label>
                    </div>

                    <div class="quiz-nav">
                        <button type="button" class="quiz-btn" id="prevBtn" disabled>Previous</button>
                        <button type="button" class="quiz-btn" id="nextBtn">Next</button>
                        <button type="submit" class="quiz-btn" id="submitBtn" name="submit_quiz" style="display:none;">Submit Quiz</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="leaderboard" id="leaderboard">
            <h2>Mathematics Quiz Leaderboard</h2>
            <table>
                <tr>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>Score</th>
                </tr>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php $rank = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $rank++; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo $row['score']; ?>/10</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No mathematics quiz scores yet!</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>Mathematics Quiz © 2025</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile dropdown functionality
            const profileContainer = document.getElementById('profile-container');
            const dropdownMenu = document.getElementById('dropdown-menu');

            profileContainer.addEventListener('click', function(event) {
                event.stopPropagation();
                dropdownMenu.style.display = dropdownMenu.style.display === 'flex' ? 'none' : 'flex';
            });

            document.addEventListener('click', function(event) {
                if (!profileContainer.contains(event.target)) {
                    dropdownMenu.style.display = 'none';
                }
            });

            // Quiz navigation functionality
            if (document.querySelector('.quiz')) {
                const questions = document.querySelectorAll('.question');
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const submitBtn = document.getElementById('submitBtn');
                const currentQuestionDisplay = document.getElementById('currentQuestion');
                let currentQuestion = 0;

                // Show first question
                showQuestion(currentQuestion);

                // Next button click handler
                nextBtn.addEventListener('click', function() {
                    if (currentQuestion < questions.length - 1) {
                        currentQuestion++;
                        showQuestion(currentQuestion);
                    }
                });

                // Previous button click handler
                prevBtn.addEventListener('click', function() {
                    if (currentQuestion > 0) {
                        currentQuestion--;
                        showQuestion(currentQuestion);
                    }
                });

                // Submit button click handler
                document.getElementById('quizForm').addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to submit your answers? You cannot change them after submitting.')) {
                        e.preventDefault();
                    }
                });

                function showQuestion(index) {
                    // Hide all questions
                    questions.forEach(question => {
                        question.classList.remove('active');
                    });

                    // Show current question
                    questions[index].classList.add('active');
                    currentQuestionDisplay.textContent = index + 1;

                    // Update button states
                    prevBtn.disabled = index === 0;
                    
                    if (index === questions.length - 1) {
                        nextBtn.style.display = 'none';
                        submitBtn.style.display = 'block';
                    } else {
                        nextBtn.style.display = 'block';
                        submitBtn.style.display = 'none';
                    }

                    // Scroll to top of question
                    window.scrollTo(0, 0);
                }
            }
        });
    </script>
</body>
</html>