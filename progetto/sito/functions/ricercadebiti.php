<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    http_response_code(401);
    header('Location: /login');
    exit;
}
if($_GET["descr"]==null){
    http_response_code(400);
    exit;
}
$descr = "%".$_GET["descr"]."%";
$conn = oci_pconnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), "AL32UTF8");
$stid = oci_parse($conn, 'SELECT * FROM "DEBTS" WHERE DESCRIPTION LIKE :descr AND (DEBTOR = :usrn or CREDITOR = :usrn) ');
    oci_bind_by_name($stid, ":descr", $descr);
    oci_bind_by_name($stid, ":usrn", $_SESSION["name"]);
    oci_execute($stid);
    print_r(oci_error($stid));
    while(oci_fetch($stid) != false){
        echo       
         oci_result($stid, "DEBTOR") 
        . " -> "
        . oci_result($stid, "CREDITOR")
        . " ("
        . oci_result($stid, "VALUE")
        .  " € ) :" 
        . oci_result($stid, "DESCRIPTION")
        . " -- "
        . oci_result($stid, "CREATED_AT")
        ."<br>"
        ;
    }
    
?>