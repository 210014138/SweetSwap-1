<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        try {
            // Points to assign when going back to challenges
            $earned_points = 20;

            // Check if the user already exists in the user_points table
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
                // If the user does not exist, insert their user id and points
                $sql_insert = "INSERT INTO user_points (user_id, points) VALUES (:user_id, :earned_points)";
                $stmt_insert = $db->prepare($sql_insert);
                $stmt_insert->bindParam(':user_id', $user_id);
                $stmt_insert->bindParam(':earned_points', $earned_points);
                $stmt_insert->execute();
            }

            header("Location: challenges.php");
            exit();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "User ID not provided.";
    }
} else {
    echo "Invalid request method.";
}
?>
