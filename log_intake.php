<?php
include 'config.php';
session_start();
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




// Get input data
$cups = $_POST['cups'];

// Conversion factor: 1 cup = 250 ml respectively
$conversion_factor = 250;
$amount = $cups * $conversion_factor;

// Placeholders are used to prevent SQL injection
$sql = "INSERT INTO water_intake (user_id, amount, date) VALUES (:user_id, :amount, CURDATE())";

try {
   
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
    $stmt->execute();

       // Stay on the same page after logging the intake
       echo "<script>window.location.href = 'water_tracker.php';</script>";
       exit;
   
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
