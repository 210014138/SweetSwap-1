<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>End</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php
include 'config.php';

session_start();

// Check if the username is set in the session
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    echo "<script>console.log('Username stored in session: " . $username . "');</script>";

    // Fetch the user's data based on the username
    $stmt = $db->prepare("SELECT * FROM users WHERE Username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $user_id = $user['Id'];
        $_SESSION['user_id'] = $user_id; 
    
        

    } else {
        echo "<p>User not found</p>"; 

    }
}


$challengeName = "Sweet Swap";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['score'])) {
        $username = $_POST['username'];
        $score = $_POST['score'];

        try {
            // Check if the username already exists in the database
            $checkQuery = $db->prepare("SELECT * FROM scores WHERE Username = :username");
            $checkQuery->bindParam(':username', $username, PDO::PARAM_STR);
            $checkQuery->execute();

            if ($checkQuery -> rowCount() >0){
                $errorMessage = "Username '{$username}' already exists. Please choose another username.";
            } else{
            // Insert username, score, and challenge name into the 'scores' table
            $sql = "INSERT INTO scores (Username, Score, Challenge) VALUES (:username, :score, :challengeName)";
            $query = $db->prepare($sql);
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->bindParam(':score', $score, PDO::PARAM_INT);
            $query->bindParam(':challengeName', $challengeName, PDO::PARAM_STR);
            $query->execute();


            // Redirect to the leaderboard page after inserting the score
            header("Location: sweet_swap_leaderboard.php");
            exit();
            }
        } catch (PDOException $e) {
            echo 'PDOException : '.  $e->getMessage();
        }
    }

    
}
?>


<body>
    <div class="quiz-new-container">
        <div id="end" class="flex-center flex-column">
            <h1 id="finalScore">0</h1>
            <div id="resultMessage"></div>
            <div class="end-section">
            <form id="scoreForm" action="sweet_swap_end.php" method="post">
                <p class="leaderboard-text">Want to appear in the leaderboard?</p> <p class="leaderboard-subtext">Enter your username below</p>
                <input type="text" class="username-field" id="username" name="username" placeholder="Username" required>
                <input type="hidden" id="scoreInput" name="score" class="leaderboard-username">
                <?php
                // Display the error message if it exists
                if(isset($errorMessage)) {
                    echo "<div style='color: red;'>{$errorMessage}</div>";
                }
                ?>
                <br>
                <input type="submit" class="leaderboard-button" value="Leaderboard">
            </form>
                <form id="pointsForm" action="assign_points.php" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="submit" class="back-button" value="Go Back To Challenges">
                </form>
            </div>
        </div>
    </div>
    <script>

        const finalScore = document.querySelector('#finalScore')
        const recentScore = localStorage.getItem('recentScore')

        document.querySelector('#scoreInput').value = recentScore;


        const SCORE_POINTS = 100 // if you get a question correct you get a score of 100 points
        const MAX_QUESTIONS = 4 

        endGame = () => {
            const resultMessage = document.getElementById('resultMessage');
            
            if(recentScore < 40 / 100 * (MAX_QUESTIONS * SCORE_POINTS)) {
                // If the user's score is less than 40% of the total possible score
                resultMessage.innerText = "Better luck next time!";
            } else {
                // Display a different message or perform other actions if needed
                resultMessage.innerText = "You're a Sugar Free Superstar. Keep it up! 👏";
            }
            localStorage.setItem('recentScore', recentScore);
            
        }



        const finalScoreNumber = recentScore * (400 / (MAX_QUESTIONS * SCORE_POINTS));
        finalScore.innerText = `${finalScoreNumber} / 400`;
        endGame();


    </script>
</body>
</html>