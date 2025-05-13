<?php 

include "connect_mysql.php";

function input_check()
{
    return isset($_POST['email']) && !empty($_POST['email']) &&
           isset($_POST['password']) && !empty($_POST['password']);
}

function txt_decode($string)
{
    $keys = [5, -14, 31, -9 ,3];
    $key_index = 0; 

    $i = 0;
    while($i < strlen($string))
    {
        if($string[$i] == chr(0x0A))
        {
            $key_index = 0;
        }
        else
        {
            $string[$i] = chr(ord($string[$i]) - $keys[$key_index]);
            $key_index++;
        }

        if($key_index == 5) $key_index = 0;
        
        $i++;
    }
    return $string;
}

function get_color($email)
{
    global $connect;
    $statement = $connect->prepare("SELECT Titkos FROM tabla WHERE Username = ?");
    $statement->bind_param("s", $email);
    $statement->execute();
    $result = $statement->get_result();
    
    if ($row = $result->fetch_assoc())
    {
        return $row['Titkos'];
    }
    else
    {
        return false;
    }
}

if (input_check())
{
    $file_read = file_get_contents("password.txt");
    $txt = txt_decode($file_read);
    $key_and_value = array();

    $lines = explode("\n", $txt);

    foreach ($lines as $line)
    {
        $arry = explode("*", $line);
        if (count($arry) == 2)
        {
            $email = $arry[0];
            $jelszo = $arry[1];
            $key_and_value[$email] = $jelszo;
        }
    }
    
    $emails = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (array_key_exists($emails, $key_and_value))
    {
        
        if ($password == $key_and_value[$emails])
        {
            $color = get_color($emails);
            $body_color = "";
            if($color == "piros")
            {
                $body_color = "red";
            }
            elseif($color == "zold")
            {
                $body_color = "green";
            }
            elseif($color == "sarga")
            {
                $body_color = "yellow";
            }
            elseif($color == "kek")
            {
                $body_color = "blue";
            }
            elseif($color == "fekete")
            {
                $body_color = "black";
            }
            elseif($color == "feher")
            {
                $body_color = "white";
            }

            $bg_color = "var bodyColor = '$body_color';";
        } 
        else
        {
            echo "Hibás jelszó!";
sleep(3);
header("Location: https://www.police.hu");
exit();

        }
    }
    else
    {
        echo "Nem található ilyen felhasználó!";
    }
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <div class="me">
        <p>Veréb Gergő - GQY892</p>
    </div>
    <div class="container">
        <form action="index.php" method="post">

            <h1>Login</h1>
            
            <input type="email" name="email" placeholder="E-mail">
            
            <input type="password" name="password" placeholder="Password">

            <div class="text-container">
                <div class="left-text">
                    <div class="radio-content">
                        <input type="checkbox">
                    </div>
                    <p>Remember me</p>
                </div>
                
                <div class="right-text">
                    <p>Forgot password?</p>
                </div>
            </div>

           <button type="submit">Login</button>

            <div class="register-text">
                <p>Dont't have an account? <span>Register</span></p>
            </div>
        
        </form>

    </div>
    <script>
        <?php
            if (isset($bg_color))
            {
                echo $bg_color;
            }
        ?>
            document.addEventListener("DOMContentLoaded", function() {
            if (typeof bodyColor !== 'undefined') {
                document.body.style.backgroundColor = bodyColor;
            }
        });
    </script>
</body>
</html>