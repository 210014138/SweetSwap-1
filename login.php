<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://kit.fontawesome.com/71d3188217.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico"> 

</head>
<?php
include 'config.php'; 

session_start();

if(isset($_SESSION['user'])) {
    // If the user is already logged in, redirect to the dashboard
    header("Location: index.html");
    exit;
}

if(isset($_POST['submit'])){
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists in the database
    $stmt = $db->prepare("SELECT * FROM users WHERE Username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['Password'])) {
            

            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['ID']; 

            // Redirect to the dashboard
            header('Location: index.php');
            exit;
        } else {
            // Incorrect password
            header('Location: login.php');
            echo '<script>alert("Incorrect password. Please try again")</script>'; 
            exit;
        }
    } else {
        // Username not found
        header('Location: login.php');
        echo '<script>alert("Username not found. Please try again")</script>';
        exit;
    }
}
?>

<body>
    <!-- header section starts -->

    <header class="header">

        <a href="landing.html" class="logo"> <i class="fa-solid fa-cubes-stacked"></i> SweetSwap</a>
        <div class="icons">
            <div class="fas fa-user" id="login-btn"></div>
        </div>

    </header>

    <!-- header section ends -->

    <div class="login-container">
        <div class="registerbox form-box">

            <header>Login</header>
            <form action="login.php" method="post">
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>
               
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
               
                <div class="register-button">
                    <input type="submit" name="submit" class="loginbtn" value="Login" required>
                </div>
                <div class="links">
                    Not a member? <a href="register.php">Sign Up Here</a>
                </div>
            </form>
        </div>
     
    </div>




     <!-- footer section starts -->

     <section class="footer">

        <div class="box-container">

            <div class="box">
                <h3><i class="fa-solid fa-cubes-stacked"></i> SweetSwap</h3>
                <p>Swap the Sugar, Keep the Sweetness: <br> Sweet Swap - Nourishing Childhood <br>Health!</p>
            </div>

           

            <div class="box">
                <h3>Category</h3>
                <a href="#about"> <i class="fas fa-caret-right"></i> About</a>
                <a href="#contact"> <i class="fas fa-caret-right"></i> Contact Us</a>
            </div>

            <div class="box">
                <h3>Other</h3>
                <a href="#"> <i class="fas fa-caret-right"></i> Privacy Policy</a>
                <a href="#"> <i class="fas fa-caret-right"></i> Terms & Conditions</a>
                <a href="#"> <i class="fas fa-caret-right"></i> FAQs</a>
            </div>

        </div>


    </section>
    <!-- footer section ends -->


    <script src="script.js"></script>

 
</body>
</html>