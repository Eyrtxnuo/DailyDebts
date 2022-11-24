<?php
header("Link: <https://use.fontawesome.com/releases/v5.7.1/css/all.css>; rel=preload; as=style; nopush", false);
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	$redirect = "/";
}else{
    $redirect = "/dashboard";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title?></title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css">
    <link rel="stylesheet" href="/resources/css/base/style.css">
</head>
<body>
    
<a href="<?= $redirect ?>" style="display: contents;"><img src="\resources\immagini\LogoNoBG.png"></a>
<div class="window">
        <h1><?= $title?></h1>
        <h2><p class="center" style="left:0;color:rgb(59, 59, 59);"><?= $message?></p></h2>
        <p class="center" style="left:0;"><button onclick="window.location.href='<?= $redirect ?>'" style="background:rgb(150 150 150); color: rgb(255, 204, 87); font-size:20px;"><h2 style="padding: 10px; margin: 0;">OK</h2></button></p>
    </div>
</body>
</html>