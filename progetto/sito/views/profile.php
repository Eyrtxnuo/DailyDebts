<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: /');
	exit;
}
$DATABASE_USER = getenv("DB_USERNAME");
$DATABASE_PASS = getenv("DB_PASSWORD");
$DATABASE_NAME = getenv("DB_DATABASE");


try {
    $conn = oci_connect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    
    $stid = oci_parse($conn,'SELECT NAME, SURNAME FROM "Users" WHERE Username = :usrn');
	
    oci_bind_by_name($stid, ':usrn', $_SESSION['name']);
    oci_execute($stid);
    oci_fetch($stid);



} catch(Exception $e) {
    exit('Failed to connect to Database. ' . $e->getMessage());
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile Page</title>
		<link href="/resources/css/base/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Website Title</h1>
				<a href="/profile"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="/logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Profile Page</h2>
			<div>
				<p>Your account details are below:</p>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td>Name</td>
						<td><?=oci_result($stid, 'NAME')?></td>
					</tr>
					<tr>
						<td>Surname</td>
						<td><?=oci_result($stid, 'SURNAME')?></td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>
<?php 
    oci_free_statement($stid);
?>