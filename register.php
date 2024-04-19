<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://kit.fontawesome.com/71d3188217.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico"> 
    

</head>
<?php
include 'config.php'; 

session_start();

if(isset($_POST['submit'])){
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if ($password !== $password2) {
        echo "<script>alert('Passwords do not match. Please try again.')</script>";
        exit; 
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email exists in the database
    $stmt = $db->prepare("SELECT Email FROM users WHERE Email = ?");
    $stmt->execute([$email]);
    $emailExists = $stmt->fetchColumn();

    if ($emailExists){
        echo "<script>alert('Email address already exists. Please try another email.')</script>";
    } else {
        // Insert user record into database with hashed password
        $stmt = $db->prepare("INSERT INTO users (Username, Email, Password) VALUES (?, ?, ?)");
        $result = $stmt->execute([$username, $email, $hashedPassword]);

        if ($result){
            header("Location: login.php");
            exit;
        } else {
            echo "<script>alert('Something went wrong, please try again')</script>";
        }
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

            <header>Sign Up</header>
            <form action="register.php" method="post">
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
                <div class="field">
                <label for="password2">Confirm Password</label>
                <input type="password" name="password2" id="password2" autocomplete="off" required>
                </div>
                <div class="register-button">
                    <input type="submit" name="submit" class="loginbtn" value="Register" required>
                </div>
                <div class="links">
                    Already a member? <a href="login.php">Sign In Here</a>
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
                <a href="landing.html"> <i class="fas fa-caret-right"></i> About</a>
                <a href="landing.html"> <i class="fas fa-caret-right"></i> Contact Us</a>
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
