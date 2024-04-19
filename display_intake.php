<?php

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


$sql = "SELECT amount, date FROM water_intake WHERE user_id = :user_id ORDER BY date DESC";

try {

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $intake_history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        $prev_date = null; //To track changes
        $total_amount = 0; // Total water intake amount for each date

        foreach ($intake_history as $row) {
            $current_date = date("d-m-Y", strtotime($row['date']));

            // If it's a new date, display the total amount for the previous date
            if ($current_date != $prev_date && $prev_date !== null) {
                echo '<div class="intake-item">';
                echo "<p>On " . $prev_date . ", you drank " . $total_amount . " ml of water.</p>";
                echo '</div>'; 
                $total_amount = 0;
            }

            // Add the current amount to the total amount
            $total_amount += $row['amount'];
          
            // Update the previous date variable
            $prev_date = $current_date;
        
        // Check if the previous date is today's date and display today's intake
        if ($prev_date == date("d-m-Y")) {
          
            echo "<p>Today you drank " . $total_amount . " ml of water.</p>";
           
        }
        }

       
        // Display the total amount for the last date
        if ($prev_date !== null) {
            echo '<div class="intake-item">';
            echo "<p>On " . $prev_date . ", you drank " . $total_amount . " ml of water.</p>";
            echo '</div>'; 
        }
    } else {
        // Display a message if no water intake has been logged
        echo "No intake logged yet.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


?>




