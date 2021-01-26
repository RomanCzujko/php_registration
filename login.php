<?php

session_start();


if ((!isset($_POST['login'])) || (!isset($_POST['password'])))
    {
        header('Location: index.php');
        exit();
    }


require_once "connect.php";

$connection = @new mysqli($host, $db_user, $db_pass, $db_name);

if ($connection->connect_errno!=0) 
{
    echo "Error".$connection->connect_errno;
}
else 
{
    $login = $_POST['login'];
    $password = $_POST['password'];

    $login = htmlentities($login, ENT_QUOTES, "UTF-8");

    if ($result = @$connection->query(sprintf("SELECT * FROM uzytkownicy WHERE user='%s'", mysqli_real_escape_string($connection,$login))))
    {
        $users = $result->num_rows;
        if ($users>0) {
            $user_data = $result->fetch_assoc();

            if(password_verify($password, $user_data['pass']))
            {
            
                $_SESSION['user_active_status'] = true;
                
                $_SESSION['id'] = $user_data['id'];
                $_SESSION['user'] = $user_data['user'];
                $_SESSION['drewno'] = $user_data['drewno'];
                $_SESSION['kamien'] = $user_data['kamien'];
                $_SESSION['zboze'] = $user_data['zboze'];
                $_SESSION['email'] = $user_data['email'];
                $_SESSION['dnipremium'] = $user_data['dnipremium'];

                unset ($_SESSION['error']);
                $result->free_result();

                header('Location: game.php');
            } else {
                $_SESSION['error'] = '<span style=" display: block; color:red; text-align:center; width:100%; ">Password is wrong!</span>';
                header('Location: index.php');
            }
           
        } else {
            $_SESSION['error'] = '<span style=" display: block; color:red; text-align:center; width:100%;">Login is wrong!</span>';
            header('Location: index.php');
        }
        
    }

    $connection->close();
    
}

?>