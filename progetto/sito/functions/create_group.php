<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	http_response_code(401);	
	header('Location: /login');
	exit;
}

$DATABASE_USER = getenv("DB_USERNAME");
$DATABASE_PASS = getenv("DB_PASSWORD");
$DATABASE_NAME = getenv("DB_DATABASE");

$conn = oci_pconnect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, 'AL32UTF8');

$stid = oci_parse($conn, ' SELECT "ADMIN"."CREATE_GROUP"(:gname,:descr,:usrn)  FROM "DUAL" ' );

//DECLARE groupID NUMBER; BEGIN INSERT INTO "GROUPS" (GROUPNAME, DESCRIPTION) VALUES (:gname,:descr) RETURNING ID INTO groupID; INSERT INTO "GroupMembers" (USERNAME, GROUPID) VALUES (:usrn, groupID); END;


oci_bind_by_name($stid, ':gname', $_POST['group-name']);
oci_bind_by_name($stid, ':descr', $_POST["desc"]);
oci_bind_by_name($stid, ':usrn', $_SESSION['name']);

if(!oci_execute($stid, OCI_COMMIT_ON_SUCCESS)){
	http_response_code(400);	
	echo("Errore!");
	print_r(oci_error($stid)['message']);
	exit;
}
$groupID = oci_fetch_array($stid)[0];
oci_free_statement($stid);
$stid = oci_parse($conn, 'SELECT * FROM "GROUPS" WHERE ID = :gid');

oci_bind_by_name($stid, ':gid', $groupID);

oci_execute($stid);

$groupData = oci_fetch_array($stid);

echo("Gruppo creato! (".$groupData['GROUPNAME'].")");
print_r("<br>Codice invito: ".$groupData['CODE']);



	

oci_free_statement($stid);
?>