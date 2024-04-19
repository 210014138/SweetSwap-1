<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['score'])) {
        $username = $_POST['username'];
        $score = $_POST['score'];

        try {
            $sql = "INSERT INTO scores (Username, Score) VALUES (:username, :score)";
            $query = $db->prepare($sql);
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->bindParam(':score', $score, PDO::PARAM_INT);
            $query->execute();

            // Redirect to the leaderboard page after inserting the score
            header("Location: leaderboard.php");
            exit();
        } catch (PDOException $e) {
            echo 'PDOException : '.  $e->getMessage();
        }
    }
}
?>
