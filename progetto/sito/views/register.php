<?php



// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...


// Change this to your connection info.
$DATABASE_USER = getenv("DB_USERNAME");
$DATABASE_PASS = getenv("DB_PASSWORD");
$DATABASE_NAME = getenv("DB_DATABASE");


try {
    $conn = oci_pconnect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];

    $regerr = '';

    if($username == NULL || $password == NULL || $name == NULL || $surname == NULL){
        if(! ( $username == NULL And $password == NULL And $name == NULL And $surname == NULL)){
            $regerr = 'Please fill all the fields!';
        }
        goto document;
    }

    /*$usg = oci_parse($conn, 'SELECT 1 FROM "Users" WHERE Username = :usrn');
    oci_bind_by_name($usg, ':usrn', $username);
    oci_execute($usg);
    if(oci_fetch($usg)){
        $regerr = 'L\'Username è già usato!';
        goto document;
    }*/

    $stid = oci_parse($conn, 'INSERT INTO "Users" (USERNAME, NAME, SURNAME, PSSW_HASH) VALUES (:usrn, :nm, :sn, :pssw)');
    
    oci_bind_by_name($stid, ':usrn', $username);
    oci_bind_by_name($stid, ':nm', $name);
    oci_bind_by_name($stid, ':sn', $surname);
    oci_bind_by_name($stid, ':pssw', password_hash($password, PASSWORD_ARGON2ID, ['memory_cost' => 4096, 'time_cost' => 8, 'threads' => 1]));

    if(oci_execute($stid, OCI_COMMIT_ON_SUCCESS)){
        oci_free_statement($stid);
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
        header('Location: /dashboard');
	    exit();
    }       
    include_once($_SERVER['DOCUMENT_ROOT'] . "/functions/phpUtils.php");
    $error = oci_error($stid);
    switch($error['code']){
        case 1:
            $err = _UTILS_getStringBetween($error["message"],"(",")");
            switch($err){
                case 'ADMIN.USERS_PK':
                    $regerr = 'L\'username è già stato usato!';
                    break;
                default:
                    $regerr = 'Valori non validi!';
                    break;
            }
            break;
        case 2290:
            $err = _UTILS_getStringBetween($error["message"],"(",")");
            switch($err){
                case 'ADMIN.USERNAMEREGEX':
                    $regerr = 'L\'username contiene caratteri non validi!';
                    break;
                default:
                    $regerr = 'Valori non validi!';
                    break;
            }
            break;
        default:
            $regerr = 'Errore nella registrazione!';//.print_r(oci_error($stid,true));
            break;
    }
    oci_free_statement($stid);

} catch(Exception $e) {
    $regerr = ('Failed to connect to Database. ' . $e->getMessage());
    goto document;
}
       
document:
 ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Register</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css">
        <link rel="stylesheet" href="/resources/css/base/style.css">
	</head>
    <body>
        <img src="\resources\immagini\LogoNoBG.png">
		<div class="login">
			<h1>Register</h1>
			<form method="post">
				<label for="username" class="field">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" minlength="3" required>
				<label for="Name" class="field">
                    <i class="fa-solid fa-n"></i>
				</label>
				<input type="text" name="name" placeholder="Name" id="name" required>
				<label for="Surname" class="field">
                    <i class="fa-solid fa-s"></i>
				</label>
				<input type="text" name="surname" placeholder="Surname" id="surname" required>
				<label for="password" class="field">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" >
                <label class="error" id="login-error" >  
                    <?= $regerr ?>
				</label>
				<input type="submit" value="Register">
			</form>
		</div>
        <p class="center" style="left:0;"><a href="/login" class="center">Login</a>
	</body>
</html>
