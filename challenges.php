<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenges</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://kit.fontawesome.com/71d3188217.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">   
</head>
<?php
session_start();
include 'config.php';

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

?>


<body>
<nav class="sidebar">
        <header class="header">
            <a href="#" class="logo"> <i class="fa-solid fa-cubes-stacked"></i> SweetSwap</a>
        </header>
        <div class="menu-bar">
            <div class="menu" id="menu">

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="profile.php">
                           
                            <span class="text nav-text">Profile</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="index.php">
                            
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="challenges.php" class="active">
                            
                            <span class="text nav-text">Challenges</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="quests.php">
                            
                            <span class="text nav-text">Quests</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="water_tracker.php">
                           
                            <span class="text nav-text">Water Tracker</span>
                        </a>
                    </li>
                    <li class="nav-link">
                    <a href="food_chart.php">
                        <span class="text nav-text">Food Chart</span>
                    </a>
                    </li>
                    <li class="nav-link">
                        <a href="knights_menu.php">
                            <span class="text nav-text">Knight's Menu</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="recipes.php">
                     
                            <span class="text nav-text">Recipes</span>
                        </a>
                    </li>                    

                    <li class="nav-link">
                        <a href="healthy_eating_tips.php">
                     
                            <span class="text nav-text">Healthy Eating Tips</span>
                        </a>
                    </li>
                    

                </ul>

               
            </div>

        </div>

    </nav>

    <section class="home">
        <div class="text">Challenges</div>
        <div class="text-right">
               
    <?php
 
    try {
        // Fetch user points and level from the database
        $stmt = $db->prepare("SELECT points, level FROM user_points WHERE user_id = ?");
        $stmt->execute([$user_id]); 
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $user_points = $row['points'];
            $user_level = $row['level'];
        } else {
            // Set user points to 0 and level to 1 if record is not found
            $user_points = 0;
            $user_level = 1;
        }

        // Points needed to reach the next level
        $threshold = 100; 
        

        // Check if the user's score is equal to the threshold
        if ($user_points == $threshold) {

            
            // Calculate the new level
            $new_level = $user_level +1;
            
            // Update the user's level in the database and reset their points to 0
            $stmt = $db->prepare("UPDATE user_points SET level = ? , points = 0 WHERE user_id = ?");
            $stmt->execute([$new_level, $user_id]);
            
            // Trigger confetti effect immediately after level up
            echo '<script>
                // Display alert message for level up
                alert("Congratulations! You have leveled up to level ' . $new_level . '");
                confetti();
            </script>';

            $user_points = 0;
        }

   
    } catch (PDOException $e) {
        
        exit("Database error: " . $e->getMessage());
    }

    ?>

         <div class="level-box">
            <div class="level">
                Level: <?php echo $user_level; ?>
            </div>
        </div>
        <div class="points">
            <img src="images/diamond.png" width="30px" height="30px">
            <?php echo ($user_points !== false) ? $user_points : "0"; ?> / <?php echo $threshold; ?> 
        </div>
        <div class="dropdown">
            <button class="dropbtn">User <span class="dropdown-arrow">&#9660;</span></button>
            <div class="dropdown-content">
            <a href="profile.php">View Profile</a>
              <a href="logout.php">Logout</a>

              
            </div>
        </div>
        
    </section>

<body>
        
<div class="quiz-container">
       
        <ul class="quiz-list">
            
            <div class="quiz">
                <div class="points-badge">+20</div>
                <h2>Sweet Tooth Trivia</h2>
                <p>Embark on a journey of sweet trivia! Answer questions and discover fascinating facts about sugar.</p>
                <img src="images/tooth.png" alt="Tooth Image" class="tooth-image">
                <button class="start-button"><a href="sweet_tooth_trivia.php">Start Quiz</button></a>
            </div>
            <div class="quiz">
                <div class="points-badge">+20</div> 
                <h2>Sweetness Showdown</h2>
                <p>It's a battle between sweet treats and healthy habits! Answer questions to learn how to enjoy sweets in a smart way.</p>
                <img src="images/boxing-glove.png" alt="Boxing gloves image" class="glove-image">
                <button class="start-button"><a href="sweetness_showdown.php">Start Quiz</button></a>
            </div>
            <div class="quiz">
                <div class="points-badge">+20</div> 
                <h2>Sugar Rush Riddles</h2>
                <p>Test your brain with sweet riddles! Fill in the blanks and learn fun facts about sugar.</p>
                <img src="images/riddles.png" alt="Riddles image" class="riddles-image">
                <button class="start-button"><a href="sugar_rush_riddles.php">Start Quiz</button></a>
            </div>
            <div class="quiz">
                <div class="points-badge">+20</div> 
                <h2>Sugar-Free Superstar Challenge</h2>
                <p> Become a sugar-free superstar! Answer questions and learn how to make healthy choices to help you become a sugar-free superstar!.</p>
                <img src="images/star.png" alt="Star image" class="star-image">
                <button class="start-button"><a href="sugar_free_challenge.php">Start Quiz</button></a>
            </div>
            <div class="quiz">
                <div class="points-badge">+20</div> 
                <h2>Sweet Swap Challenge</h2>
                <p>Join the challenge to swap sugary treats for healthier alternatives! Learn about nutritious snacks and make smart choices.</p>
                <img src="images/swap.png" alt="Swap image" class="swap-image">
                <button class="start-button"><a href="sweet_swap_challenge.php">Start Quiz</button></a>
            </div>
            <div class="quiz">
                <h2>Sugar Detox Challenge</h2>
                <p>Detoxify your body from excess sugar! Keep a track of your sugar-detox journey here.</p>
                <img src="images/detox.png" alt="Detox image" class="detox-image">
                <button class="start-button"><a href="detox.php">Start Challenge</button></a>
            </div>
                
        </ul>
</body>
</html>
