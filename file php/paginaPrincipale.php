<?php
session_start();

if(!isset($_SESSION['username'])){
    header('location: login.php');
}
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
</head>
<body>
    Selezionare l'azione da svolgere <br>
    <a href="inserisci.php"> Aggiungi un debito</a> <br>
    <a href="visualizza.php"> Visualizza i tuoi debiti</a> <br>
    <a href="elimina.php"> Elimina i tuoi debiti</a> <br>
</body>
</html>
