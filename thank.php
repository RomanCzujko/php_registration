<?php

session_start();

    if ((!isset ($_SESSION['valid_registration'])) ) 
    {
        header('Location: index.php');
        exit();
    } else {
        unset($_SESSION['valid_registration']);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank Page</title>
</head>
<body>
    <div style="width: 100%; text-align:center; color:green; font-size: 50px;">Your Registration is successful!!!  <a href="index.php">Enjoy you GAME!</a></div>
    
</body>
</html>