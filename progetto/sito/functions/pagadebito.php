<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    http_response_code(401);
    header('Location: /login');
    exit;
}
if($_POST["user"]==null || $_POST["deb"]==null){
    http_response_code(400);
    exit;
}
$conn = oci_pconnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"));
$stid = oci_parse($conn, 'INSERT INTO "DEBTS" (DEBTOR, CREDITOR, VALUE) VALUES (:debit, :credit, :deb_value)');

    oci_bind_by_name($stid,":debit", $_POST["user"]);
    oci_bind_by_name($stid,":credit", $_SESSION["name"]);
    oci_bind_by_name($stid,":deb_value", $_POST["deb"]);
    
    if(oci_execute($stid, OCI_COMMIT_ON_SUCCESS)){
        include_once($_SERVER['DOCUMENT_ROOT'] . "/functions/phpUtils.php");	
	    _UTILS_showMessage("È andato tutto a buon fine", "Ok!");
    }else{
        http_response_code(400);    
        include_once($_SERVER['DOCUMENT_ROOT'] . "/functions/phpUtils.php");	
	    _UTILS_showMessage("Errore!");
        print_r(oci_error($stid)['message']);
    }
?>