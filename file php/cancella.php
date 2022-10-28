<?php
if(isset($_POST['btn'])){
    if (isset($_GET["id"])&& !empty(trim($_GET["id"]))) {
        require_once 'config.php';
    
        $sql="DELETE FROM  WHERE ID=?";
    
        if($stmt = mysqli_prepare($link,$sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
    
            $param_id = trim($_GET["id"]);
    
            if(mysqli_stmt_execute($stmt)){
                header("location: visualizza.php");
                exit();
            }else{
                echo "something went wrong";
            }
        }
        mysqli_stmt_close($stmt);
    }else{
        if (empty(trim($_GET["id"]))) {
           header("location: error.php");
           exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?id=".trim($_GET["id"]);?>" method="post">
        <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>">
        <p>sei sicuro?</p><br>
        <p><input type="submit" value="Yes" name="btn">
        <a href="visualizza.php">No</a>
        </p>
    </form>
</body>
</html>