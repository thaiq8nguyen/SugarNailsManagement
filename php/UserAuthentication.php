<?php
    session_start();
    require_once "Connection.php";

    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = mysqli_real_escape_string($link,$_POST['username']);
        $password = md5($_POST['password']);

        $query = "SELECT technicians.technicianID AS technicianID,userID,userName FROM users INNER JOIN technicians ON users.technicianID = technicians.technicianID
        WHERE users.userName = ? AND users.password = ?";

        $stmt = mysqli_prepare($link,$query);
        mysqli_stmt_bind_param($stmt,'ss',$username,$password);
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt,$technicianID,$userID,$username);
                mysqli_stmt_fetch($stmt);
                $_SESSION['sessionID'] = session_id();
                $_SESSION['userID'] = $userID;
                $_SESSION['technicianID'] = $technicianID;
                $_SESSION['username'] = $username;
                $verify = "pass";
                echo($verify);
            }
            else{
                $verify = "fail";
                echo($verify);
            }
        }

    }
?>