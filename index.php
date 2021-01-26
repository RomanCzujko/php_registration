<?php
    session_start();

    if ((isset ($_SESSION['user_active_status'])) && ($_SESSION['user_active_status'] == true)) 
    {
        header('Location: game.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<body>
    
    <br>
    <?php
        if(isset($_SESSION['error'])) {
            echo ($_SESSION['error']);
            unset ($_SESSION['error']);
        } 
    ?>
    <br>
    
    <div class="mb-3" style="width: 100%; text-align:center;">
        <form action="login.php" method="POST">
            <input type="text" placeholder="Login: " name="login">
            <input type="password" placeholder="Password: " name="password"><br><br>
            <button class="btn btn-primary" type="submit">Login</button><br><br>
            <a href="registration.php">Registration</a>
        </form>
    </div>
    <?php
        exit();
    ?>

</body>
</html>