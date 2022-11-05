<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (isset($_SESSION['loggedin']) And $_SESSION['loggedin'] == TRUE) {
	header('Location: /dashboard');
	exit();
}

// Change this to your connection info.
$DATABASE_USER = getenv("DB_USERNAME");
$DATABASE_PASS = getenv("DB_PASSWORD");
$DATABASE_NAME = getenv("DB_DATABASE");


try {
    $conn = oci_connect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == NULL || $password == NULL || !isset($_POST['username'], $_POST['password'])){
        if($username == NULL Xor $password == NULL){
            $loginerr = 'Please fill both the username and password fields!';
        }
        goto document;
    }

    $stid = oci_parse($conn, 'SELECT PSSW_HASH FROM "Users" WHERE Username = :usrn');
    
    oci_bind_by_name($stid, ':usrn', $username);
    oci_execute($stid);
    oci_fetch($stid);

    if (password_verify($password,oci_result($stid, 'PSSW_HASH'))) {
        
        #header ( 'Location: /dashboard' );
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
        header('Location: /dashboard');
    } else {
        $loginerr = 'Invalid username or password.';
    }
        

    oci_free_statement($stid);

} catch(Exception $e) {
    $loginerr = ('Failed to connect to Database. ' . $e->getMessage());
    goto document;
}
       
document:
 ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css">
        <link rel="stylesheet" href="/resources/css/base/style.css">
	</head>
	<body>
		<div class="login">
			<h1>Login</h1>
			<form method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" value="<?= $_POST['username']  ?>"  required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" >
                <label class="error" id="login-error">  
                    <?= $loginerr ?>
				</label>
				<input type="submit" value="Login">
			</form>
		</div>
	</body>
</html>
