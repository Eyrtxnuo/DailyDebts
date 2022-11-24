<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	http_response_code(401);	
	header('Location: /login');
	exit;
}

if($_POST["user"] == $_SESSION['name']){
	http_response_code(400);	
    exit("Cosa scusa? hai un debito con te stesso?");
}
require_once(__DIR__ .'/phpUtils.php');

$user = _UTILS_getUser($_POST["user"]);
if($user == null){
	http_response_code(400);	
	exit("No user found!");
}



$DATABASE_USER = getenv("DB_USERNAME");
$DATABASE_PASS = getenv("DB_PASSWORD");
$DATABASE_NAME = getenv("DB_DATABASE");

$conn = oci_pconnect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, 'AL32UTF8');
$stid = oci_parse($conn, 'INSERT INTO "DEBTS" (DEBTOR, CREDITOR, VALUE, DESCRIPTION, GROUP_ID) VALUES (:debit, :credit, :deb_sum, :descr, :grId)');

oci_bind_by_name($stid, ':debit', $_SESSION['name']);
oci_bind_by_name($stid, ':credit', $user["USERNAME"]);
oci_bind_by_name($stid, ':deb_sum', $_POST["sum"]);
oci_bind_by_name($stid, ':descr', $_POST["desc"]);
$groupID = _UTILS_getGroupByCode($_GET["group"])["ID"];
oci_bind_by_name($stid, ':grId', $groupID);

if(oci_execute($stid, OCI_COMMIT_ON_SUCCESS)){
	echo("Debito aggiunto");
}else{
	http_response_code(400);	
	echo("Errore!");
	print_r(oci_error($stid)['message']);
}
	
oci_free_statement($stid);
?>