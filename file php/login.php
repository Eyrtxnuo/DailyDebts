<?php
    session_start();

    //se sessione è gà attiva passa alla pagina della sessione
    if(isset($_SESSION['username'])){
        header('location: paginaPrincipale.php');
    }

    if(isset($_POST['btn_submit'])){
        include "config.php";
        $username=mysqli_real_escape_string($link,$_POST['txt_username']);
        $password=mysqli_real_escape_string($link,$_POST['txt_pwd']);

        if($username != "" && $password != ""){
            $sql_query="SELECT COUNT(*) AS countUser FROM users WHERE username=? AND password=?";
            if($stmt = mysqli_prepare($link,$sql_query)){
               mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_pass);
        
                $param_username = trim($_POST["txt_username"]);
                $param_pass = trim($_POST["txt_pwd"]);
        
                if(mysqli_stmt_execute($stmt)){//inserisce i dati
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
                    if($row["countUser"]==1){
                        ob_start();
                        $result=ob_get_clean();
                        if(empty($result)){
                            $_SESSION['username']=$username;
                            header('Location: paginaPrincipale.php');
                            exit();
                        }else{
                            echo $result;
                        }
                        
                    }else{
                        echo "invalid username or password";
                    }
                }
            }else{
                echo "something went wrong";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <h1>Login</h1>

        <input type="text" id="txt_username" name="txt_username" placeholder="Username">
        <input type="password" id="txt_pwd" name="txt_pwd" placeholder="Password">
        <input type="submit" value="Submit" name="btn_submit" id="btn_submit">
    </form>
</body>
</html>