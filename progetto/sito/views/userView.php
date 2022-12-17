
<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: /login');
	exit;
}
if(!isset($userView)){
    exit("user not set?");
}
if($userView == $_SESSION['name']){
    exit("Sei tu ".$userView);
}


try {
    
    $conn = oci_pconnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), 'AL32UTF8');
   /* $stid = oci_parse($conn, 'SELECT * FROM "Users" WHERE USERNAME = :user');

    oci_bind_by_name($stid, ":user", $userView);

    oci_execute($stid);*/
    $stid = oci_parse($conn,'SELECT NAME, SURNAME FROM "Users" WHERE Username = :usrn');
	
    oci_bind_by_name($stid, ':usrn', $userView);
    oci_execute($stid);
    if(!oci_fetch($stid)){
        exit($userView."-> "."User not found!".print_r(oci_error($stid),true));
    }
    


} catch(Exception $e) {
    exit('Failed to connect to Database. ' . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css">
    <link rel="stylesheet" href="/resources/css/base/style.css">
    <title>User: <?=$userView?></title>
    <style>
        h2{
            display: inline;
        }
    </style>
</head>
<body>
<nav class="navtop">
        <div>
            <a href="/dashboard" style="display: contents;"><img src="\resources\immagini\LogoNoBG.png"></a>
            <div style="width:100%; justify-content: flex-end;">
                <a href="/dashboard" class="navElement"><i class="fa-solid fa-house"></i>Dashboard</a>
                <a href="/logout" class="navElement"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </div>
    </nav>
<div class="window" style="padding:20px;">
<h1><?= oci_result($stid, "NAME")." ".oci_result($stid, "SURNAME")?></h1>
<h2>Username:</h2> <?= $userView ?><br>
<h2>Nome:</h2> <?= oci_result($stid, "NAME") ?><br>
<h2>Cognome:</h2> <?= oci_result($stid, "SURNAME") ?>

<br>
<br>
<button onclick="window.location.href='/addDebt?user=<?= $userView ?>'">Aggiungi debito</button>
<button onclick="window.location.href='/addCredit?user=<?= $userView ?>'">Aggiungi credito</button>
<br>
<br>

<h2>Totale debiti:</h2>  <?php
    $stid = oci_parse($conn,'SELECT GETUSERDEBT(:usrn, :other) FROM "DUAL"');
    oci_bind_by_name($stid, ':usrn', $_SESSION['name']);
    oci_bind_by_name($stid, ':other', $userView);
    oci_execute($stid);
    print_r(oci_fetch_array($stid)[0]);
?>
€
<br><br>

<h2>Storico debiti:</h2>
<ul>
<?php

$stid = oci_parse($conn,'
(SELECT -VALUE as VAL,ID,DESCRIPTION,CREATED_AT FROM "DEBTS" WHERE DEBTOR = :usrn AND CREDITOR = :other AND GROUP_ID IS NULL)
UNION  
(SELECT VALUE as VAL,ID,DESCRIPTION,CREATED_AT FROM "DEBTS"  WHERE DEBTOR = :other AND CREDITOR = :usrn AND GROUP_ID IS NULL)
ORDER BY CREATED_AT DESC
');
oci_bind_by_name($stid, ':usrn', $_SESSION['name']);
oci_bind_by_name($stid, ':other', $userView);
oci_execute($stid);
while($res = oci_fetch_array($stid)){
    echo("<li>".$res["VAL"])."€: ".$res["DESCRIPTION"]."</li>";
}
print_r(oci_error($stid));

?>
<ul>
</div>
</body>
</html>