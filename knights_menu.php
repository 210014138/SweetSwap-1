<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/71d3188217.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>Knight's Menu</title> 
    <link rel="icon" type="image/x-icon" href="images/favicon.ico"> 
</head>
<?php
session_start();
include 'config.php';

// Check if the username is set in the session
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

   
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
                    <a href="knights_menu.php" class="active">
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
    <div class="text">Sir Arthur's Mighty Menu</div>
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


<div class="mighty-menu-container">
    <!--Message to tell the user about the page-->
    <div class="arthur-message">
        <h2>Click on the yummy foods to make Sir Arthur happy and strong! But watch out for the not-so-yummy ones, he doesn't like them.</h2>
    </div>

    <!-- Arthur the Knight sprite -->
    <div class="sprite-animation"></div>

    <!-- Food options and message area -->
    <div class="food-options-container">
    <h2>Choose a food item to feed Sir Arthur:</h2>
    <div class="food-options">
       <!-- Food options with onclick event handlers --> 
        <div class="food-option">
            <img id="cookie" src="images/cookies.png" alt="Cookie" width="100" onclick="arthurSays('cookie', true)">
        </div>
        <div class="food-option">
            <img id="orange" src="images/orange.png" alt="Orange" width="100" onclick="arthurSays('orange', false)">
        </div>
        <div class="food-option">
            <img id="candy" src="images/lollipop.png" alt="Candy" width="100" onclick="arthurSays('candy', true)">
        </div>
        <div class="food-option">
            <img id="apple" src="images/apple.png" alt="Apple" width="100" onclick="arthurSays('apple', false)">
        </div>
        <div class="food-option">
            <img id="cake" src="images/cupcake.png" alt="Apple" width="100" onclick="arthurSays('cake', true)">
        </div>        
    </div>
</div>
    <div id="messageArea" style="display: none;"></div>
</div>
<script>
    // JavaScript function to display Arthur the Knight's responses to food options
    function arthurSays(foodID, isSugary) {
    var messageArea = document.getElementById("messageArea");
    var message = "";

    if (foodID === "cookie" || foodID === "candy" || foodID === "cake") {
        message = "Sir Arthur says: Oh no, not more sugar!";
        messageArea.className = "red-message";
    } else if (foodID === "apple" || foodID === "orange") {
        message = "Sir Arthur says: That's a healthy choice! I'm feeling stronger already!";
        messageArea.className = "green-message";
        startAnimation(); 
    } else {
        message = "Please select a food item.";
        messageArea.className = "";
    }

        // The message in the message area is displayed after a food option has been clicked
        messageArea.innerText = message;
        messageArea.style.display = "block"; 
    }

    // Function to start animation when a healthy food option is clicked, the animation restarts after every click
    function startAnimation() {
        var knight = document.querySelector(".sprite-animation");
        knight.classList.remove("animate"); 
        void knight.offsetWidth; 
        knight.classList.add("animate");
    }

</script>

</body>
</html>


