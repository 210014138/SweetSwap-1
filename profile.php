<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://kit.fontawesome.com/71d3188217.js" crossorigin="anonymous"></script>
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico"> 
</head>
<?php
session_start();
include 'config.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

// Fetch the user's ID based on the username
$stmt = $db->prepare("SELECT Id FROM users WHERE Username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) {
    $user_id = $user['Id']; 
    $_SESSION['user_id'] = $user_id; 
    $email = $user['Email'];
} else {
    echo "<p>User not found</p>";
}

$stmt = $db->prepare("SELECT Username, Email, Password FROM users WHERE Id = ?");
$stmt->execute([$user_id]);
$user_details = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user_details) {
    $username = $user_details['Username'];
    $email = $user_details['Email'];
    $password = $user_details['Password'];
} else {
    echo "<p>User details not found</p>";
}
}

// Check if the form is submitted for updating user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve updated user information from the form
    $updated_username = $_POST['name']; 
    $updated_email = $_POST['email'];



    // Update the user information in the database
    $stmt = $db->prepare("UPDATE users SET Username = ?, Email = ? WHERE Id = ?");
   
    if ($stmt->execute([$updated_username, $updated_email, $user_id])) {
        // Update the $username variable with the new value
        $_SESSION['username'] = $updated_username;
        $username = $updated_username;
        $email = $updated_email;
       
        $success_message = "Your information has been updated successfully!";
    } else {
      
        echo "Failed to update username in the database.";
    }


      // Retrieve the new password from the form
      $new_password = $_POST['new_password'];

      if (!empty($new_password)) {
          // Generate the hash of the new password
          $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
          // Update the user's password in the database
          $stmt = $db->prepare("UPDATE users SET Password = ? WHERE Id = ?");
          $stmt->execute([$hashed_password, $_SESSION['user_id']]);
      }
}


?>
<body>
<nav class="sidebar">
        <header class="header"> 
        <a href="#" class="logo"> <i class="fa-solid fa-cubes-stacked"></i> SweetSwap</a>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="profile.php" class="active">
                           
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
        <div class="text">Profile Page</div>
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
          
              <a href="logout.php">Logout</a>
              
            </div>
        </div>
        
    </section>
    
    <div class="profile-container">
        <div class="profile">
        <h3 class="profile-heading">Change Profile Picture</h3>
        <img src="images/profile-pic.png" class="user">
            
            <form action= "profile.php" method="POST" enctype="multipart/form-data" class="image-form">
                <input type= "file" name="file">
                <div class="button-container">
                    <button type="submit" name="submit" class="upload-button">Upload</button>
                </div>
            </form>
         <div>
            
         </div>
        </div>
        <?php if (isset($success_message)) : ?>
        <script>
            // Display an alert if the details have been updated successfully
            alert("<?php echo $success_message; ?>");
        </script>
    <?php endif; ?>
        <div class="profile">
            <h3 class="user-info-heading">My Details</h3>
        <form method="post" class="info-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo isset($username) ? $username : ''; ?>"><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>"><br>

         
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password"><br>

            <input type= "submit" value="Update" class="update-button">
        </form>
        </div>
    </div>
    
</body>
</html>