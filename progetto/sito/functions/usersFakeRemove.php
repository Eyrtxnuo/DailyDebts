<?php

$DATABASE_USER = getenv("DB_USERNAME");
$DATABASE_PASS = getenv("DB_PASSWORD");
$DATABASE_NAME = getenv("DB_DATABASE");

$conn = oci_connect($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    
$stid = oci_parse($conn, 'DELETE FROM "Users" WHERE USERNAME LIKE \'FakeUser_%\'');

    oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
    
    oci_free_statement($stid);

    echo("done");
?>