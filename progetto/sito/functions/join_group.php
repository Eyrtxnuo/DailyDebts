<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	http_response_code(401);	
	header('Location: /login');
	exit;
}

if($_POST["GroupCODE"] == null || !isset($_POST["GroupCODE"])){
    http_response_code(400);
    exit("Group code not set!");
}

$conn = oci_pconnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"));

$stid = oci_parse($conn, 'SELECT JOIN_GROUP(:usrn, :Gcode) FROM "DUAL"');

oci_bind_by_name($stid, ':Gcode', $_POST['GroupCODE']);
oci_bind_by_name($stid, ':usrn', $_SESSION['name']);

if(!oci_execute($stid, OCI_COMMIT_ON_SUCCESS)){
	http_response_code(400);	
	echo("Errore nella query!");
	print_r(oci_error($stid)['message']);
	exit;
}
$resp = oci_fetch_array($stid);
if($resp[0] != 1){
    http_response_code(400);	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/functions/phpUtils.php");	
	_UTILS_showMessage("Valori non validi!","Errore!");
	exit;
}
include_once($_SERVER['DOCUMENT_ROOT'] . "/functions/phpUtils.php");	
_UTILS_showMessage("Gruppo Joinato!"," Ok!");
	


?>
