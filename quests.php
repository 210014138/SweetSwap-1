<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quests</title>
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
                        <a href="challenges.php">
                            
                            <span class="text nav-text">Challenges</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="quests.php" class="active">
                            
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
        <div class="text">Quests</div>
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

        
<div class="quest-container">
    
        <ul class="quest-list">
            <div class="quest">
                <h2>Sugarland Adventures</h2>
                <p>Join the young explorer Ozzy on an exciting journey through Sugarland, where he learns about the sweet dangers of too much sugar.</p>
                <img src="images/ozzy.png" alt="Image of boy with binoculars" class="ozzy">
                <button class="start-button"><a href="sugarland-adventures.html">Start Quest</button></a>
            </div>
            <div class="quest">
                <h2>SweetEscape: The Sugar-Free Quest</h2>
                <p>Help the brave knight Leo navigate through the Sweet Kingdom, where he must make healthy choices to save the kingdom from sugary chaos.</p>
                <img src="images/knight.png" alt="Knight" class="knight">
                <button class="start-button"><a href="sweet-escape.html">Start Quest</button></a>
            </div>
            <div class="quest">
                <h2>The Sugar-Free Treasure Hunt</h2>
                <p>Set sail on an exciting adventure with Captain Charlie and his crew as they search for the legendary Sugar-Free Treasure, hidden on a remote island guarded by sugar-loving pirates.</p>
                <img src="images/treasure.png" alt="Treasure" class="treasure-image">
                <button class="start-button"><a href="treasure-hunt.html">Start Quest</button></a>
            </div>
            <div class="quest">
                <h2>Sugar-Free Safari Adventure</h2>
                <p>Explore the wild jungles of Sugar-Free Island with Emily and learn about the importance of maintaining a balanced diet without excessive sugar.</p>
                <img src="images/girl-with-backpack.png" alt="Girl with backpack" class="girl-with-backpack-image">
                <button class="start-button"><a href="sugar-safari.html">Start Quest</button></a>
            </div>
            <div class="quest">
                <h2>Sweet Dreams Quest</h2>
                <p>Dive into a magical dream world with Jack. Navigate through dream land and help Jack make healthy choices to ensure sweet dreams and a restful sleep.</p>
                <img src="images/dreams.png" alt="Boy dreaming image" class="boy-dreaming-image">
                <button class="start-button"><a href="sweet-dreams.html">Start Quest</button></a>
            </div>
        
            <div class="quest">
                <h2> The Sweet Tooth Detective</h2>
                <p>Become a detective and help Maya and Elliot solve the mystery of disappearing energy in Candytown.</p>
                <img src="images/detectives.png" alt="Two child detective" class="detectives-image">
                <button class="start-button"><a href="sweet-tooth-detective.html">Start Quest</button></a>
            </div>
                    
        </ul>
 
</body>
</html>