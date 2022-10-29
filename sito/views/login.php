<form method="POST">
    <input type="text" name="username" required minlength="6">
    <input type="password" name="password" required minlength="8">
    <input type="submit" value="SUBMIT">
</form>
<?php 
try {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == NULL || $password == NULL){
        return;
    }

    $conn = oci_connect('ADMIN','c6!RDMX1LSb@7R', 'debiti_high');
    $stid = oci_parse($conn, 'SELECT * FROM "Users" WHERE Username = :usrn');
    
    oci_bind_by_name($stid, ':usrn', $username);
    oci_execute($stid);
    oci_fetch($stid);
    
    

    if (password_verify($password,oci_result($stid, 'PSSW_HASH'))) {
        echo('Password is valid!');
        header ( 'Location: /dashboard' );
    } else {
        echo('Invalid password.');
    }
        echo("<br>");
        

    oci_free_statement($stid);

} catch(Exception $e) {
    echo $e->getMessage();
}
        
 /*
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