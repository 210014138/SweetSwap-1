<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="style.css">
</head>

<style>
    body{
        background-color: #E4E9F7;
    }
</style>

<body>
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
        $_SESSION['user_id'] = $user_id; // Set the user's ID in session
       
         // Fetch user's level from the database
         $stmt = $db->prepare("SELECT level FROM user_points WHERE user_id = ?");
         $stmt->execute([$user_id]);
         $user_level = $stmt->fetchColumn();
 
         // Set previous level in session
         $_SESSION['prev_level'] = $user_level;

    } else {
        echo "<p>User not found</p>"; 
        
    }
}

$activity_id = $_GET['activity_id'] ?? 1; // Default to 1 if activity ID is not provided

try {
    // Fetch quiz title based on activity ID
    $sql = "SELECT q.title FROM quizzes q
            INNER JOIN activity_quizzes aq ON q.quiz_id = aq.quiz_id
            WHERE aq.activity_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$activity_id]);
    $quiz_title = $stmt->fetchColumn();

    // Display the title
    echo '<div class="daily-quiz-container">';
    echo "<h2 class='daily-quiz-heading'>$quiz_title</h2>";

    // Fetch quiz questions and options with quiz IDs
    $sql = "SELECT q.quiz_id, q.question_text, q.option1, q.option2, q.option3, q.option4 FROM quizzes q
            INNER JOIN activity_quizzes aq ON q.quiz_id = aq.quiz_id
            WHERE aq.activity_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$activity_id]);
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display quiz questions and options
    echo '<form action="submit_quiz.php?activity_id=' . $activity_id . '" method="post" class="quiz1-form">'; // Activity id is in the url so that the correct activity can be marked as complete when the form is submitted

    foreach ($quizzes as $quiz) {
        echo '<div class="question">';
        echo "<p>{$quiz['question_text']}</p>";
        echo '<div class="options">';
        echo "<label><input type='radio' name='response[{$quiz['quiz_id']}]' value='1' required><span>{$quiz['option1']}</span></label>";
        echo "<label><input type='radio' name='response[{$quiz['quiz_id']}]' value='2'><span>{$quiz['option2']}</span></label>";
        echo "<label><input type='radio' name='response[{$quiz['quiz_id']}]' value='3'><span>{$quiz['option3']}</span></label>";
        echo "<label><input type='radio' name='response[{$quiz['quiz_id']}]' value='4'><span>{$quiz['option4']}</span></label>";
        echo '</div>';
    }
    echo '<button type="submit" class="submit-btn">Submit</button>';
    echo '</form>';
    echo '</div>';
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
</body>
</html>
