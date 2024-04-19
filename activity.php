<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity</title>
    <link rel="stylesheet" href="style.css">

<style>
    body{
        background-color:#E4E9F7;
    }
</style>
</head>

<body>
   
    <div id="activity_details">
    <?php
        include 'config.php';

        // Check if the activity ID is set in the URL
        if(isset($_GET['activity_id'])) {
            $activity_id = $_GET['activity_id'];

            try {
                // Fetch activity details based on the provided activity ID
                $stmt = $db->prepare("SELECT activity_name, activity_description FROM activity WHERE activity_id = ?");
                $stmt->execute([$activity_id]);
                $activity = $stmt->fetch(PDO::FETCH_ASSOC);

                if($activity) {
                    // Display activity details if found
                    echo "<h2 class='activity-heading'>{$activity['activity_name']}</h2>";
                    echo "<div class='activity-text'>";
                    
                    // Split the activity description into an array of sentences
                    $sentences = explode(".", $activity['activity_description']);

                    // Each sentence is displayed with a line break
                    foreach ($sentences as $sentence) {
                        echo "<p>{$sentence}</p>";
                        echo "<br>";
                    }

                    echo "</div>";
                } else {
                    echo "Activity not found.";
                }
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Activity ID not provided.";
        }
        ?>
        <form action="quiz.php?activity_id=<?php echo $activity_id; ?>" method="post"> <!--Activity id is in the url so that the corresponding quiz questions can be retrieved from the database-->
            <button type="submit">Next</button>
        </form>

    </div>

</body>
</html>
