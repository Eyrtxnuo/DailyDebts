<?php
header('Content-Type: application/json');
$DATABASE_USER = getenv("DB_USERNAME");
$DATABASE_PASS = getenv("DB_PASSWORD");
$DATABASE_NAME = getenv("DB_DATABASE");
$key = $_GET['key'];
if(strlen($key)<3){
    exit("[]");
}
$key = "%".$key."%";
try {
    $conn = oci_connect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    
    $stid = oci_parse($conn,'SELECT USERNAME FROM "Users" WHERE Username LIKE :key COLLATE BINARY_CI');
    
    oci_bind_by_name($stid, ':key', $key);
    oci_execute($stid);
    $array = array();
    while(oci_fetch($stid))
    {
      $array[] = oci_result($stid, "USERNAME");
    }
    echo json_encode($array);
    oci_free_statement($stid);

} catch(Exception $e) {
    exit('[]');
}
?>


