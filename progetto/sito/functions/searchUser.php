<?php
header('Content-Type: application/json');
$DATABASE_USER = getenv("DB_USERNAME");
$DATABASE_PASS = getenv("DB_PASSWORD");
$DATABASE_NAME = getenv("DB_DATABASE");
$key = $_GET['key'];
if(strlen($key)<2){
    exit("[]");
}
session_start();
// If the user is logged in, filter his username...
if (isset($_SESSION['loggedin'])) {
    $filter = $_SESSION["name"];
}

if(isset($_GET["group"])){
    include_once($_SERVER['DOCUMENT_ROOT'] . "/functions/phpUtils.php");
    $groupFilter = _UTILS_getGroupByCode($_GET["group"])["ID"];
    $groupFilter = $groupFilter."";
}

$key = "%".$key."%";
try {
    $conn = oci_pconnect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    $query= (isset($groupFilter))?
     'SELECT us.USERNAME AS USERNAME FROM "Users" us LEFT JOIN "GroupMembers" gm ON us.USERNAME = gm.USERNAME WHERE us.USERNAME LIKE :key COLLATE BINARY_CI AND GROUPID = :grc'
     :
     'SELECT us.USERNAME AS USERNAME FROM "Users" us WHERE us.USERNAME LIKE :key COLLATE BINARY_CI ';
    $stid = oci_parse($conn,$query);
    oci_bind_by_name($stid, ':key', $key);
    oci_bind_by_name($stid, ':grc', $groupFilter);
    oci_execute($stid);
    print_r(oci_error($stid));
    $array = array();
    while(oci_fetch($stid))
    {
        $val = oci_result($stid, "USERNAME");
        if($val != $filter){
             $array[] = oci_result($stid, "USERNAME");
        }
     
    }
    echo json_encode($array);
    oci_free_statement($stid);

} catch(Exception $e) {
    exit('[]');
}
?>


