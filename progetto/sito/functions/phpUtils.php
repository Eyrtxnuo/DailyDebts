<?php

function _UTILS_getUser($username){
    $DATABASE_USER = getenv("DB_USERNAME");
    $DATABASE_PASS = getenv("DB_PASSWORD");
    $DATABASE_NAME = getenv("DB_DATABASE");
    
    try {
        $conn = oci_pconnect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        
        $stid = oci_parse($conn,'SELECT USERNAME,NAME,SURNAME,ID FROM "Users" WHERE Username = :usrn');
        
        oci_bind_by_name($stid, ':usrn', $username);
        oci_execute($stid);
        $user = oci_fetch_assoc($stid);
        
        oci_free_statement($stid);

        return $user;
    
    } catch(Exception $e) {
        return false;
    }
    return false;
}

?>