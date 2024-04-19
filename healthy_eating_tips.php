<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://kit.fontawesome.com/71d3188217.js" crossorigin="anonymous"></script>
<title>Healthy Eating Tips</title>
<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
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
                        <a href="challenges.php" >
                            
                            <span class="text nav-text" >Challenges</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="quests.php" >
                            
                            <span class="text nav-text">Adventures</span>
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
                        <a href="sugar.php" class="active">
                     
                            <span class="text nav-text" >Healthy Eating Tips</span>
                        </a>
                    </li>
                    

                </ul>

               
            </div>

        </div>

    </nav>

    <section class="home">
      <div class="text">Healthy Eating Tips</div>
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
              Level: <?php echo $user_level; ?> <!-- Display user's level -->
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
  <!--Healthy eating tips start-->
  <div class="tips">
      <div class="tip tip1">
        <img src="images/tip.png" width="30px" height="30px">
        <p>Choose water or milk instead of sugary drinks</p>
      </div>

      <div class="tip tip2">
        <img src="images/tip.png" width="30px" height="30px">
        <p>Look at food labels with grown-ups to find foods with less sugar.</p>
      </div>

      <div class="tip tip3">
        <img src="images/tip.png" width="30px" height="30px">
        <p>Pick fresh fruits instead of sweets for snacks.</p>
      </div>

      <div class="tip tip4">
        <img src="images/tip.png" width="30px" height="30px">
        <p>Limit sugary snacks and drinks to keep your teeth and body strong.</p>
      </div>

      <div class="tip tip5">
        <img src="images/tip.png" width="30px" height="30px">
        <p>Drink plenty of water to stay hydrated.</p>
      </div>

      <div class="tip tip6">
        <img src="images/tip.png" width="30px" height="30px">
        <p>Save desserts for special days!</p>
      </div>
  </div>

  <!--Healthy eating tips end-->

  <div class="healthy-eating-container">
  
    <div class="game-heading">
      <h2>Play The Healthy Eating Game</h2>
      <img src="images/pointing-down.png" width="50px" height="50px">
      
    </div>

    <p class="game-message">Drag and drop the food items into the correct boxes!</p>
    
    <!--Healthy and Unhealthy food categories-->
    <div class="categories">
      <div class="category" id="healthy" ondrop="drop(event, 'healthy')" ondragover="allowDrop(event)">
        <h3>Healthy Choices</h3>
      </div>

      <div class="category" id="unhealthy" ondrop="drop(event, 'unhealthy')" ondragover="allowDrop(event)">
        <h3>Unhealthy Choices</h3>
      </div>
    </div>

    <!--Food items to be dragged and dropped-->
    <div class="foods">
      <div class="food" draggable="true" ondragstart="drag(event)" data-category="healthy" data-alt="Carrot">
        <img src="images/carrots.png" alt="Carrot">
      </div>
      <div class="food" draggable="true" ondragstart="drag(event)" data-category="healthy" data-alt="Apple">
        <img src="images/apple.png" alt="Apple">
      </div>
      <div class="food" draggable="true" ondragstart="drag(event)" data-category="unhealthy" data-alt="Chocolate">
        <img src="images/chocolate-bar.png" alt="Chocolate">
      </div>
      <div class="food" draggable="true" ondragstart="drag(event)" data-category="unhealthy" data-alt="Cake">
        <img src="images/cupcake.png" alt="Cake">
      </div>     
      <div class="food" draggable="true" ondragstart="drag(event)" data-category="healthy" data-alt="Banana">
        <img src="images/banana.png" alt="Banana">
      </div>
      <div class="food" draggable="true" ondragstart="drag(event)" data-category="unhealthy" data-alt="Lollipop">
        <img src="images/lollipop.png" alt="Lollipop">
      </div>

    </div>
  </div>

<script>
  var totalFoods = document.querySelectorAll('.food').length; // Total number of food items for tracking purposes

  //Function to allow elements to be dropped
  function allowDrop(event) {
    event.preventDefault();
  }


  // Function to handle items being dragged
  function drag(event) {
    var draggedFoodAlt = event.currentTarget.dataset.alt;
    var draggedCategory = event.currentTarget.dataset.category; 
  

    if (draggedFoodAlt && draggedCategory) {
      event.dataTransfer.setData("text", draggedCategory);
      event.dataTransfer.setData("alt", draggedFoodAlt);
    } else {
      console.error("Cannot drag the food item.");
    }
  }


function drop(event, category) {
  event.preventDefault();

  //Get the data from the dragged food item
  var draggedCategory = event.dataTransfer.getData("text");
  var draggedFoodAlt = event.dataTransfer.getData("alt");
  
  // Find the dragged food item in the DOM based on its category and the alt text
  var draggedFood = document.querySelector('.food[data-category="' + draggedCategory + '"][data-alt="' + draggedFoodAlt + '"]');


  if (draggedCategory === category && draggedFood) {
    var draggedFoodImage = draggedFood.querySelector('img'); // Get the image element of the dragged food item
    
    // Clone the image element to create a copy of it
    var draggedFoodImageClone = draggedFoodImage.cloneNode(true);

    // CSS to keep the cloned images the same size
    draggedFoodImageClone.style.width = draggedFoodImage.offsetWidth + 'px';
    draggedFoodImageClone.style.height = draggedFoodImage.offsetHeight + 'px';

    // Add the cloned image to the target category box
    event.target.appendChild(draggedFoodImageClone);

    event.target.classList.add("highlighted");
    event.target.style.color = "black";

    draggedFood.parentNode.removeChild(draggedFood); // Remove the dragged food item from its previous location
   
    //Remove the highlight from the category box after 1 second
    setTimeout(function() {
      event.target.classList.remove("highlighted");
    }, 1000);

    totalFoods--; // The counter for the ramining food items decrements 

    //Display a message when all the food items have been dragged into the correct categories
    if (totalFoods === 0) {
      setTimeout(function() {
        alert("Well done! You have sorted all the foods.");
      }, 1000);
    } 
  } else {
    alert("Try again! Drag the food to the correct category.");
  }
}




</script>

</body>
</html>
