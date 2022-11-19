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
    <title>Creazione Gruppo</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css">
    
</head>
<body>
<div class="window">
        <h1>Creazione gruppo</h1>
        <form method="post" action="/fnct/create_group" >
            
            
            <label for="group-name">
                <i class="fa-solid fa-hashtag"></i>
            </label>
            <input type="text" name="group-name" placeholder="Nome del gruppo" maxlength="100" required>
            
            <br>

            <label for="sum">
                <i class="fa-solid fa-quote-right"></i>
            </label>
            <textarea name="desc" id="desc" placeholder="Description" cols="40" rows="5" autocomplete="off" maxlength="1000"></textarea>
            
            <br>
            <input type="submit" value="Aggiungi">
        </form>
    </div>
</body>
</html>