<?php
    session_start();

    if (!isset ($_SESSION['user_active_status']))
    {
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game</title>
</head>
<body>
    <?php

    echo "<p>Hello ".$_SESSION['user']."!</p>";
    echo "<p>Wood: ".$_SESSION['drewno']."</p>";
    echo "<p>Stone:  ".$_SESSION['kamien']."</p>";
    echo "<p>Food:  ".$_SESSION['zboze']."</p>";
    echo "<p>E-mail: ".$_SESSION['email']."</p>";
    echo "<p>Days left: ".$_SESSION['dnipremium']."</p>";
    echo '<a href="logout.php"><button class="btn btn-primary">Logout</button></a></p>';

    ?>
</body>
</html>
