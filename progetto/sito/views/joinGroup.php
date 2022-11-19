<?php 
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: /login?ref='.$_SERVER['REQUEST_URI']);
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entra in un gruppo</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css">
    
</head>
<body>
<div class="window">
        <h1>Entra in un gruppo</h1>
        <form method="post" action="/fnct/join_group" >
            
            <label for="GroupCODE">
                <i class="fas fa-user"></i>
            </label>
            <input name="GroupCODE" placeholder="Group invite code" autocomplete="off" required>
                    
            <br>
            <input type="submit" value="Aggiungi">
        </form>
    </div>
</body>
</html>
