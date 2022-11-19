<?php
header('Content-Type: application/json');
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	http_response_code(401);
	exit;
}
try {
    $conn = oci_pconnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"));
    
    $stid = oci_parse($conn,'SELECT USER1 as FRIEND FROM "FRIENDSHIPS" WHERE USER2 = :usrn UNION SELECT USER2 as FRIEND FROM "FRIENDSHIPS" WHERE USER1 = :usrn');
    
    oci_bind_by_name($stid, ':usrn', $_SESSION['name']);

    oci_execute($stid);
    $array = array();
    while(($row = oci_fetch_array($stid)[0]))
    {
        $array[] = $row;
    }
    echo json_encode($array);
    oci_free_statement($stid);

} catch(Exception $e) {
    exit('[]');
}
?>