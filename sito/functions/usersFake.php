<?php

$DATABASE_USER = getenv("DB_USERNAME");
$DATABASE_PASS = getenv("DB_PASSWORD");
$DATABASE_NAME = getenv("DB_DATABASE");

$conn = oci_connect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    
$stid = oci_parse($conn, 'INSERT INTO "Users" (USERNAME, NAME, SURNAME, PSSW_HASH) VALUES (:usrn, :nm, :sn, :pssw)');

    $username = "fake";
    oci_bind_by_name($stid, ':nm', $username);
    oci_bind_by_name($stid, ':sn', $username);
    oci_bind_by_name($stid, ':pssw', $username);

    
    
    for ($i=0; $i < 100; $i++) { 
        $username = "FakeUser_".$i;
        oci_bind_by_name($stid, ':usrn', $username);
        
        oci_execute($stid, OCI_NO_AUTO_COMMIT);
    }

    oci_commit($conn);
    
    oci_free_statement($stid);
?>