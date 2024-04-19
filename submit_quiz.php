<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php

include 'config.php';
session_start();
// Check if the username is set in the session
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
   

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {
  
        $user_id = $_SESSION['user_id']; 
        $activity_id = $_GET['activity_id'] ?? 1; // Default to 1 if activity ID is not provided
      
        
        // Points earned for each correct answer
        $points_per_correct_answer = 10; 
        // Fetch the submitted quiz responses from the form
        $submitted_responses = $_POST['response']; 
    
        // Count the number of correct answers
        $correct_answers = 0;
        foreach ($submitted_responses as $quiz_id => $response) {
            // Retrieve the correct answer for the current quiz from the quizzes table
            $stmt = $db->prepare("SELECT correct_option FROM quizzes WHERE quiz_id = ?");
            $stmt->execute([$quiz_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $correct_answer = $row['correct_option'];
        
            // Compare the user's submitted response with the correct answer
            if ($response == $correct_answer) {
                $correct_answers++;
            }
        }
           
    
        // Points earned for the correct answer
        $earned_points = $points_per_correct_answer * $correct_answers;
    
        // Check if the user ID exists in the user_points table
        $sql = "SELECT * FROM user_points WHERE user_id = :user_id";
        $check_user = $db->prepare($sql);
        $check_user->bindParam(':user_id', $user_id);
        $check_user->execute();
        $user_exists = $check_user->fetch(PDO::FETCH_ASSOC);
    
        if ($user_exists) {
            // If the user exists, update their points
            $sql = "UPDATE user_points SET points = points + :earned_points WHERE user_id = :user_id";
            $stmt_update = $db->prepare($sql);
            $stmt_update->bindParam(':earned_points', $earned_points);
            $stmt_update->bindParam(':user_id', $user_id);
            $stmt_update->execute();
        } else {
            //If the user does not exist, insert their user id and points
            $sql_insert = "INSERT INTO user_points (user_id, points) VALUES (:user_id, :earned_points)";
            $stmt_insert = $db->prepare($sql_insert);
            $stmt_insert->bindParam(':user_id', $user_id);
            $stmt_insert->bindParam(':earned_points', $earned_points);
            $stmt_insert->execute();
        }

        // After the points are updated or inserted, the activity is marked as complete
        $mark_activity_complete = $db->prepare("INSERT INTO user_activity_completion (user_id, activity_id, is_completed) VALUES (:user_id, :activity_id, :is_completed)");
        $mark_activity_complete->bindParam(':user_id', $user_id);
        $mark_activity_complete->bindParam(':activity_id', $activity_id);
        $is_completed = true; 
        $mark_activity_complete->bindParam(':is_completed', $is_completed);
        $mark_activity_complete->execute();

        
        
        echo "<div class='congratulations-box'>";
        echo "Congratulations! You got $earned_points/70 and have completed the activity. Hooray!";
        echo "<button class='complete-button' id='complete-button'><a href='index.php'>Return to Dashboard</a></button>";
        echo "</div>";
    
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>
<body>
    
</body>
</html>



