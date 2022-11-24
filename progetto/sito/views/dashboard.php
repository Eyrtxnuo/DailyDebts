<?php
header("Link: <https://use.fontawesome.com/releases/v5.7.1/css/all.css>; rel=preload; as=style; nopush", false);
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: /login');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Cache-control" content="private">
		<title>DailyDebts - Dashboard</title>
		<link rel="stylesheet" href="/resources/css/base/style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css">
		<style>
			div.span button{
				background: #2f3947;
				color: #c1c4c8;
				font-weight: bold;
				border: transparent;
				border-radius: 2px;
				padding: 5px 15px;
				align: right;
				margin: 0 1px ;
			}
			div.span button:hover{
				background-color:#3f4d61;
			}


			div.span h2{
				display: flex;
    			justify-content: space-between;
			}
		</style>
	</head>
	<body class="loggedin">
	<nav class="navtop">
			<div>
				<a href="/dashboard" style="display: contents;"><img src="\resources\immagini\LogoNoBG.png"></a>
				<div style="width:100%; justify-content: flex-end;">
					<a href="/profile" class="navElement"><i class="fas fa-user-circle"></i>Profile</a>
					<a href="/logout" class="navElement"><i class="fas fa-sign-out-alt"></i>Logout</a>
				</div>
			</div>
		</nav>
		<div class="content">
			<h2>Home Page</h2>
			<div class="cbox">
				<h2>Groups <div class="boxButton"><button onclick="window.location.href='/createGroup'">Create</button><button onclick="window.location.href='/joinGroup'">Join</button></div></h2>
				<ul>
					<?php  
						$DATABASE_USER = getenv("DB_USERNAME");
						$DATABASE_PASS = getenv("DB_PASSWORD");
						$DATABASE_NAME = getenv("DB_DATABASE");
						
						$conn = oci_pconnect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, 'AL32UTF8');
						
						$stid = oci_parse($conn, 
						'SELECT 
							GROUPNAME,CODE,JOINED_AT
						 FROM "GroupMembers"
						  INNER JOIN "GROUPS" ON GROUPID = GROUPS.ID 
						  WHERE USERNAME = :usrn 
						ORDER BY 
							COALESCE(
								(SELECT MAX(deb.CREATED_AT) FROM "DEBTS" deb WHERE deb.GROUP_ID = GROUPID),
								JOINED_AT
							)
						 DESC' );
						
						
						oci_bind_by_name($stid, ':usrn', $_SESSION['name']);
						
						if(!oci_execute($stid)){
							echo("Errore nel caricamento dei gruppi!");
							print_r(oci_error($stid));
						}else{
							while (oci_fetch($stid)) {
								echo "<a href='/group/".oci_result($stid, 'CODE')."'><li>".oci_result($stid, 'GROUPNAME')."</li></a>";
							}
						}
					?>
				</ul>
			</div>
			<div class="cbox">
				<h2>Friends <div class="boxButton"><button onclick="window.location.href='/addFriend'">Add Friend</button></div></h2>
				<ul>
					<?php  
					$stid = oci_parse($conn,
					'SELECT 
					USERNAME,NAME,SURNAME,ID 
					FROM "Users" 
					INNER JOIN 
						( SELECT
						  USER1 AS FRIEND,FRIENDS_FROM
						  FROM "FRIENDSHIPS" 
						  WHERE USER2 = :usrn 
						 UNION 
						  SELECT 
						  USER2 AS FRIEND,FRIENDS_FROM 
						  FROM "FRIENDSHIPS" WHERE USER1 = :usrn )
					ON USERNAME = FRIEND 
					ORDER BY
					 COALESCE(
						(SELECT MAX(CREATED_AT) FROM DEBTS WHERE (DEBTOR = :usrn AND CREDITOR = USERNAME) OR (DEBTOR = USERNAME AND CREDITOR = :usrn) AND GROUP_ID IS NULL),
						(SELECT FRIENDS_FROM  FROM "FRIENDSHIPS" WHERE (USER1 = :usrn AND USER2 = USERNAME) OR (USER1 = USERNAME AND USER2 = :usrn))
					 )
					 DESC');
    
					oci_bind_by_name($stid, ':usrn', $_SESSION['name']);
				
					oci_execute($stid);
					while(($row = oci_fetch_array($stid)))
					{
						echo "<a href='/user/".$row["USERNAME"]."'><li>".$row["NAME"]." ".$row["SURNAME"]." (".$row["USERNAME"].")"."</li></a>";
					}
						
					?>
				</ul>
			</div>
		</div>
		
	</body>
</html>
