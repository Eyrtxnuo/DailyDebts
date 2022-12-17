
<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: /login');
	exit;
}

if(!isset($groupView)){
    exit("code not set?");
}

try {
    
    $conn = oci_pconnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"),'AL32UTF8');
   /* $stid = oci_parse($conn, 'SELECT * FROM "Users" WHERE USERNAME = :user');

    oci_bind_by_name($stid, ":user", $userView);

    oci_execute($stid);*/
    $stid = oci_parse($conn,'SELECT * FROM "GROUPS" WHERE CODE = :code');
	
    oci_bind_by_name($stid, ':code', $groupView);
    oci_execute($stid);
    if(!(oci_fetch($stid))){
        exit($groupView." -> "."Group not found!".print_r(oci_error($stid),true));
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
    <title>Group: <?=oci_result($stid, "GROUPNAME")?></title>
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
<h1><?= oci_result($stid, "GROUPNAME")?></h1>

<h2>Codice invito:</h2> <?= $groupView ?><br>
<h2>Nome del gruppo:</h2> <?= oci_result($stid, "GROUPNAME") ?><br>
<h2>Descrizione gruppo:</h2> <?= oci_result($stid, "DESCRIPTION") ?><br>
<br>
<button onclick="window.location.href='/addDebt?group=<?= $groupView ?>'">Aggiungi debito</button>
<button onclick="window.location.href='/addCredit?group=<?= $groupView ?>'">Aggiungi credito</button>
<br>
<br>
<h2>Membri:</h2>
<ul>
<?php
	$stid = oci_parse($conn,'SELECT * FROM "GroupMembers" INNER JOIN "GROUPS" ON GROUPID = ID WHERE CODE = :code');
		
	oci_bind_by_name($stid, ':code', $groupView);
	oci_execute($stid);
	while(oci_fetch($stid)){
		echo "<li>".oci_result($stid, 'USERNAME')."</li>";
	}
?>
</ul>
<br>
<br>
<h2>Storico debiti:</h2>
<ul>
<?php
	$stid = oci_parse($conn,'SELECT DEBTOR,CREDITOR,VALUE,debt.DESCRIPTION as DDESC FROM "DEBTS" debt INNER JOIN "GROUPS" gr ON GROUP_ID = gr.ID WHERE CODE = :code ORDER BY CREATED_AT DESC');
		
	oci_bind_by_name($stid, ':code', $groupView);
	oci_execute($stid);
	while(($res = oci_fetch_array($stid, OCI_ASSOC))!=false){
		echo("<li>".$res['DEBTOR']." <i class='fa-solid fa-arrow-right'></i> ".$res['VALUE']."€ <i class='fa-solid fa-arrow-right'></i> ". $res['CREDITOR'].": ".$res["DDESC"]."</li>");
	}
?>
</ul>


</div>
</body>
</html>