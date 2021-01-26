<?php
session_start();

if(isset($_POST['email']))
{
    $validation = true;
    
    // Nick validation
    $nick = $_POST['nick'];

    if((strlen($nick) < 3) || (strlen($nick) > 20)) {
        $validation = false;
        $_SESSION['e_nick'] = "Nick must have min 3 or max 20 characters";
    }

    if(!ctype_alnum($nick)) {
        $validation = false;
        $_SESSION['e_nick'] = "Nick must have only numbers and letters";
    }

    // Email validation
    $email = $_POST['email'];

    $email_clear = filter_var($email, FILTER_SANITIZE_EMAIL);

    if(!filter_var($email_clear, FILTER_VALIDATE_EMAIL) || $email_clear!=$email) {
        $validation = false;
        $_SESSION['e_email'] = "Email is not correct"; 
    }

    // Password validation
    $password = $_POST['password'];
    $re_password = $_POST['password2'];

    if($password!==$re_password ) {
        $validation = false;
        $_SESSION['e_password'] = "Re-Password is not correct"; 
    } else if (strlen($password)<8 || strlen($password)>20) {
        $validation = false;
        $_SESSION['e_password'] = "Password must have min 8 characters"; 
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Checkbox  validation

    if(!isset($_POST['regulamin'])) {
        $validation = false;
        $_SESSION['e_regulamin'] = "You must accept privacy policy"; 
    }

    // To Bot or Not to Bot? 

    $secret = "6LcaZzkaAAAAANP1AS4Wl8fsQPHviUINvR5m-_bu";
    $bot_check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);

    $response = json_decode($bot_check);


    if($response->success==false) {
        $validation = false;
        $_SESSION['e_bot'] = "You must check anti-Bot option"; 
    }

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
        $connection = new mysqli($host, $db_user, $db_pass, $db_name);

        if ($connection->connect_errno!=0) 
        {
            throw new Exception(mysqli_connect_errno());
        } 
        else {
            // Email is registered? 
            $result = @$connection->query("SELECT id FROM uzytkownicy WHERE email='$email'");
            if(!$result) {
                throw new Exception($connection->error);
            }
            $emails = $result->num_rows;
            if ($emails>0) {
                $validation = false;
                $_SESSION['e_email'] = "This Email already exists"; 
            }

            // Login is registered? 
            $result = @$connection->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
            if(!$result) {
                throw new Exception($connection->error);
            }
            $nicks = $result->num_rows;
            if ($nicks>0) {
                $validation = false;
                $_SESSION['e_nick'] = "This Nickname already exists"; 
            }
            if($validation) {
                // Validation is ok!
                // header('Location: thank.php');
                if($connection->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$password_hash', '$email', 100, 100, 100, 14)")) {
                    $_SESSION['valid_registration']='true';
                    header('Location: thank.php');
                } else {
                    throw new Exception($connection->error);
                }
                exit();
            }

            $connection->close();
        }
    }
    catch (Exception $e)
    {
        echo ' <span style=" display: block; color:red; text-align:center; width:100%;"> :-( Server error! Please try again letter!</span>';
        echo '<br> Dev-info: '.$e;
    }

    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        .error {
            color: red;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    
    <div class="mb-3" style="width: 100%; text-align:center; display:flex; flex-direction:column;">
        <form  method="POST" style="width: 30%; display:flex; flex-direction:column; margin: 0 auto;">
            <input type="text" placeholder="Nickname: " name="nick" style=" margin: 5px;">
            <?php 
            if(isset($_SESSION['e_nick'])) 
            {
                echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
                unset($_SESSION['e_nick']);
            }
            ?>
            <input type="text" placeholder="Email: " name="email" style=" margin: 5px;">
            <?php 
            if(isset($_SESSION['e_email'])) 
            {
                echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                unset($_SESSION['e_email']);
            }
            ?>
            <input type="password" placeholder="Password: " name="password" style=" margin: 5px;">
            <input type="password" placeholder="Re-Password: " name="password2" style=" margin: 5px;">
            <?php 
            if(isset($_SESSION['e_password'])) 
            {
                echo '<div class="error">'.$_SESSION['e_password'].'</div>';
                unset($_SESSION['e_password']);
            }
            ?>
            <div>
                <label> 
                    <input type="checkbox" name="regulamin" style=" margin: 5px;"/> Accept privat policy
                </label>
            </div>
            <?php 
            if(isset($_SESSION['e_regulamin'])) 
            {
                echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
                unset($_SESSION['e_regulamin']);
            }
            ?>
            <div class="g-recaptcha" data-sitekey="6LcaZzkaAAAAAOBBHqCzUS8XIT6QVLJ9AXgx-vdg"></div>
            <br/>
            <?php 
            if(isset($_SESSION['e_bot'])) 
            {
                echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
                unset($_SESSION['e_bot']);
            }
            ?>
            <button class="btn btn-primary" type="submit">Registration</button><br>
            <a href="login.php">Login</a>
        </form>
    </div>

</body>
</html>