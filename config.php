<?php 
try{ 
  $db=new PDO("mysql:dbname=website; host=localhost", "root","");  
  } catch (PDOException $ex) {      
      ?> 
      <p> Connection failed because:<em> <?= $ex->getMessage() ?> </em></p>     
      <?php     
  }  
?>