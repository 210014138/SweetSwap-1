<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: url('images/leaderboard.jpg') no-repeat;
            background-size: cover;
        }

        .go-back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 20px 30px;
            background-color: #e9768f;
            font-size: 15px;
            border-radius: 10px;
            cursor: pointer;
            z-index: 3;
            text-decoration: none;
            color: #ffffff;
        
        }


        .go-back-btn:hover {
            background-color: pink;
        }

        .leaderboard {
            width: 80%;
            max-width: 700px;
            background-color: #fff;
            border-radius: 30px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height:80%;
            max-height:700px;
          
        }

        .leaderboard-title {
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            padding: 10px 70px; 
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 2; 
            font-size: 30px;
            margin-bottom: 20px;
            background-color: #32CD32;
            color: #ffffff;
            
        }

        .row {
            display: flex;
            align-items: center;
            margin-top:30px;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .rank {
            flex: 0 0 50px;
            text-align: center;
            font-weight: bold;
            font-size: 20px;
        }

        .username {
            flex-grow: 1;
            padding-left: 20px;
            font-size: 15px;
        }

        .score {
            flex: 0 0 100px;
            text-align: right;
            font-size: 20px;
        }

        .crown {
            width: 30px;
            height: auto;
            margin-bottom:5px;
        }
       
    </style>
</head>

<?php
    include 'config.php';

    session_start();

    $challengeName = "Sugar Free Challenge";

    $result = $db->prepare("SELECT Username, Score FROM scores WHERE Challenge = :challengeName ORDER BY Score DESC");
    $result->bindParam(':challengeName', $challengeName, PDO::PARAM_STR);
    $result->execute();

    $rank = 1;

?>
<body>
<a href="challenges.php" class="go-back-btn">Back to Challenges</a>
<div class="leaderboard">
<div class="leaderboard-title">Leaderboard</div>
        <?php
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='row'>";
                echo "<div class='rank'>";
                if ($rank == 1) {
                    echo "<img class='crown' src='images/crown.png' alt='Crown'>";
                }
                echo "{$rank}</div>";
                echo "<div class='username'>{$row['Username']}</div>";
                echo "<div class='score'>{$row['Score']}</div>";
                echo "</div>";
                $rank++;
            }
        } else {
            echo "<div>No scores found for '{$challengeName}'.</div>";
        }
        ?>
    </div>
</body>
</html>

