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
    include_once($_SERVER['DOCUMENT_ROOT'] . "/functions/phpUtils.php");	
	_UTILS_showMessage("Cosa scusa? hai un credito con te stesso?","Errore!");
	exit();
}
require_once(__DIR__ .'/phpUtils.php');

$user = _UTILS_getUser($_POST["user"]);
if($user == null){
	http_response_code(400);	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/functions/phpUtils.php");	
	_UTILS_showMessage("Utente non trovato!","Errore!");
	exit();
}



$DATABASE_USER = getenv("DB_USERNAME");
$DATABASE_PASS = getenv("DB_PASSWORD");
$DATABASE_NAME = getenv("DB_DATABASE");

$conn = oci_pconnect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, 'AL32UTF8');
$stid = oci_parse($conn, 'INSERT INTO "DEBTS" (DEBTOR, CREDITOR, VALUE, DESCRIPTION, GROUP_ID) VALUES (:debit, :credit, :deb_sum, :descr, :grId)');

oci_bind_by_name($stid, ':debit', $user["USERNAME"]);
oci_bind_by_name($stid, ':credit', $_SESSION['name']);
oci_bind_by_name($stid, ':deb_sum', $_POST["sum"]);
oci_bind_by_name($stid, ':descr', $_POST["desc"]);
$groupID = _UTILS_getGroupByCode($_GET["group"])["ID"];
oci_bind_by_name($stid, ':grId', $groupID);

if(oci_execute($stid, OCI_COMMIT_ON_SUCCESS)){
	include_once($_SERVER['DOCUMENT_ROOT'] . "/functions/phpUtils.php");	
	_UTILS_showMessage("Credito aggiunto","Ok!");
}else{
	http_response_code(400);	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/functions/phpUtils.php");
	$error = oci_error($stid);
	switch($error['code']){
		case 2291:
			$err2291 = _UTILS_getStringBetween($error["message"],"(",")");
			switch($err2291){
				case 'ADMIN.INGROUPDEBTOR_FK':
					$regerr = 'Il debitore non è stato trovato!';
					break;
				case 'ADMIN.INGROUPCREDITOR_FK':
					$regerr = 'Il creditore non è stato trovato!';
					break;
				default:
					$regerr = 'Valori non validi!';
					break;
			}
			break;
		case 1400:
			$err1400 = _UTILS_getStringBetween($error["message"],"(",")");
			switch($err1400){
				case '"ADMIN"."DEBTS"."DEBTOR"':
					$regerr = 'Il debitore non è stato trovato!';
					break;
				case '"ADMIN"."DEBTS"."CREDITOR"':
					$regerr = 'Il creditore non è stato trovato!';
					break;
				default:
					$regerr = 'Conrtolla di aver compilato tutti i valori!';
					break;
			}
			break;
		default:
			$regerr = 'Errore sconosciuto!'.print_r(oci_error($stid)["message"],true);
			break;
	}
	include_once($_SERVER['DOCUMENT_ROOT'] . "/functions/phpUtils.php");	
	_UTILS_showMessage($regerr,"Errore!");
}
oci_free_statement($stid);
?>