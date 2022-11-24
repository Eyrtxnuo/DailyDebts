<?php

function _UTILS_getUser($username){
    $DATABASE_USER = getenv("DB_USERNAME");
    $DATABASE_PASS = getenv("DB_PASSWORD");
    $DATABASE_NAME = getenv("DB_DATABASE");
    
    try {
        $conn = oci_pconnect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, 'AL32UTF8');
        
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

function _UTILS_getGroupByCode($code){
    $DATABASE_USER = getenv("DB_USERNAME");
    $DATABASE_PASS = getenv("DB_PASSWORD");
    $DATABASE_NAME = getenv("DB_DATABASE");
    
    try {
        $conn = oci_pconnect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, 'AL32UTF8');
        
        $stid = oci_parse($conn,'SELECT * FROM "GROUPS" WHERE CODE = :code');
        
        oci_bind_by_name($stid, ':code', $code);
        oci_execute($stid);
        $user = oci_fetch_assoc($stid);
        
        oci_free_statement($stid);

        return $user;
    
    } catch(Exception $e) {
        return null;
    }
    return null;
}

function _UTILS_getStringBetween($str,$from,$to, $withFromAndTo = false)
{
   $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
   if ($withFromAndTo)
     return $from . substr($sub,0, strrpos($sub,$to)) . $to;
   else
     return substr($sub,0, strrpos($sub,$to));
}

?>