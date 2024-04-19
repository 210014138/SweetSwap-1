<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Dashboard</title>
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
                        <a href="index.php" class="active">
                            
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="challenges.php" >
                            
                            <span class="text nav-text" >Challenges</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="quests.php" >
                            
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
                     
                            <span class="text nav-text" >Healthy Eating Tips</span>
                        </a>
                    </li>
                    

                </ul>

               
            </div>

        </div>

    </nav>

    <section class="home">
        <div class="text">Dashboard</div>
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
            // Set user points to 0 and level to 1 if the user record is not found
            $user_points = 0;
            $user_level = 1;
        }

        // Points needed to reach the next level
        $threshold = 100; 
       
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

    <div class="welcome-message">
        <div class="welcome-heading">
        <img src="images/profile-pic.png" width="130px" height="130px"><h2 class="welcome-message-text"> Hi <?php echo $user['Username']; ?> !</h2>
        </div>
    </div>
    
    <div class="grid-container">
    <div class="item item1">
     
        <div class="item1-heading">
            <h3 class="item-heading-text">Your plan for today</h3><img src="images/pointing-down.png" width="60px" height="60px"> 
        </div>
 
    <div class="plan-container">
    <?php
   
        $user_id = $_SESSION['user_id'];
        $activity_id = 1;

        // Fetch activity details for the current activity
        $stmt_activity = $db->prepare("SELECT activity_name FROM activity WHERE activity_id = ?");
        $stmt_activity->execute([$activity_id]);
        $activity_row = $stmt_activity->fetch(PDO::FETCH_ASSOC);

        // Check if the quiz has been completed by the user for the current activity
        $stmt = $db->prepare("SELECT * FROM user_activity_completion WHERE user_id = ? AND activity_id = ?");
        $stmt->execute([$user_id, $activity_id]);
        $completion_row = $stmt->fetch(PDO::FETCH_ASSOC);

        // If completion row exists and the activity is completed, set completion flag
        $is_completed = ($completion_row && $completion_row['is_completed']);

        // Fetch activity details for the next activity
        $next_activity_id = $activity_id + 1;
        $stmt_next_activity = $db->prepare("SELECT activity_name FROM activity WHERE activity_id = ?");
        $stmt_next_activity->execute([$next_activity_id]);
        $next_activity_row = $stmt_next_activity->fetch(PDO::FETCH_ASSOC);
    
        // Check if the next activity's completion status
        $stmt_next_completion = $db->prepare("SELECT * FROM user_activity_completion WHERE user_id = ? AND activity_id = ?");
        $stmt_next_completion->execute([$user_id, $next_activity_id]);
        $next_completion_row = $stmt_next_completion->fetch(PDO::FETCH_ASSOC);
        $is_next_completed = ($next_completion_row && $next_completion_row['is_completed']);

     

    ?>

    <!-- 2 daily activities -->

    <div class="box1 <?php echo ($is_completed ? 'completed' : ''); ?>">
        <div class="box1-heading">
            <img src="images/task.png" width="50px" height="50px">
            <h4 class="box-heading">Activity #<?php echo $activity_id; ?></h4>
        </div>
        <h2 class="box1-subheading"><?php echo $activity_row['activity_name']; ?></h2>
        <a href="activity.php?activity_id=<?php echo $activity_id; ?>">
            <button class="task-start" id="start-task-btn">Click to Start</button>
        </a>
    </div>

    <div class="box2 <?php echo ($is_next_completed ? 'completed' : ''); ?>">
        <div class="box2-heading">
            <img src="images/task.png" width="50px" height="50px">
            <h4 class="box-heading">Activity #<?php echo $next_activity_id; ?></h4>
        </div>

        <h2 class="box1-subheading"><?php echo $next_activity_row['activity_name'] ?></h2>
        <a href="activity.php?activity_id=<?php echo $next_activity_id; ?>">
            <button class="task-start" id="start-task-btn">Click to Start</button>
        </a>
    </div>

    
</div>

    </div>


    <div class="item">
        <div class="item2-heading">
            <h3 class="item-heading-text">Daily Goals</h3><img src="images/goal.png" width="50px" height="50px"> 
        </div>
    
    <div class="goal-container">
        <div class="goal-section">
            <h2 class="goal-heading">Select your goals for today:</h2>
            <ul id="allGoals" class="goal-list"></ul>
        </div>
    </div>
    </div>

    <div class="item item3">
        <div class="item3-heading">
            <h3 class="item-heading-text">Badges</h3><img src="images/trophy-star.png" width="50px" height="50px"> 
        </div>
        <div class="badge-list">
            <ul class="badges">
                <li><img src="images/badge.png" width="80px" height="70px"></li>
                <li><img src="images/badge-outline.png" width="80px" height="70px"></li>
                <li><img src="images/badge-outline.png" width="80px" height="70px"></li>
                <li><img src="images/badge-outline.png" width="80px" height="70px"></li>
                <li><img src="images/badge-outline.png" width="80px" height="70px"></li>
                <li><img src="images/badge-outline.png" width="80px" height="70px"></li>
                <li><img src="images/badge-outline.png" width="80px" height="70px"></li>
                <li><img src="images/badge-outline.png" width="80px" height="70px"></li>
            </ul>
        </div>
    </div>
</div>


<script src="script.js"></script>
<script src="goals.js"></script>

</body>
</html>