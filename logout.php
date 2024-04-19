<?php
session_start(); 

// Unset all of the session variables
$_SESSION = array();


// Destroy the session
session_destroy();

// Redirect the user to the home page
header("Location: login.php"); 
exit; 
?>
<script>
// Clear local storage when the user logs out
localStorage.clear();
</script>