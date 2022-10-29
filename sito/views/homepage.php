<a href="/login">Login</a><br>
<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $conn = oci_connect(getenv("DB_USERNAME"),getenv("DB_PASSWORD"), getenv("DB_DATABASE"));
    $stid = oci_parse($conn, 'SELECT * FROM "Users"');
    oci_execute($stid);
    while (oci_fetch($stid)) {
        echo oci_result($stid, 'USERNAME') . " is ";
        echo oci_result($stid, 'NAME') . "<br>\n";
    }
    oci_free_statement($stid);
} catch(Exception $e) {
    echo $e->getMessage();
}
        
/*
 $password = $_GET['password'];
 
 for($i=0;$i<1;$i++){
    $old_hash = $password_hash;
    $password_hash = password_hash($password, PASSWORD_ARGON2ID, ['memory_cost' => 4096, 'time_cost' => 8, 'threads' => 1]);
    if($old_hash == $password_hash){
        echo("whaat?");
    }
}
 echo($password_hash);
 echo('<br>'.$password.'<br>');
 if (password_verify($password,'$argon2id$v=19$m=4096,t=16,p=4$NWRDQmpHZWJ0eVYwQ3lXRg$Hv3ITWot6McKYqWSfytTMA')) {
    echo('Password is valid!');
} else {
    echo('Invalid password.');
}
    echo("<br>end");
    */
 ?>


