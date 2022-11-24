<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    http_response_code(401);
    header('Location: /login');
    exit;
}
$conn = oci_pconnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), 'AL32UTF8');
$stid = oci_parse($conn, 'SELECT SUM(VALUE) FROM "DEBTS" WHERE DEBTOR=:USERN');
oci_bind_by_name($stid,":USERN", $_SESSION["name"]);
oci_execute($stid);
oci_fetch($stid);
$debittot = oci_result($stid, "SUM(VALUE)");// debito totale fatto

$stid = oci_parse($conn, 'SELECT SUM(VALUE) FROM "DEBTS" WHERE CREDITOR=:USERN');
oci_bind_by_name($stid,":USERN", $_SESSION["name"]);
oci_execute($stid);
oci_fetch($stid);
$credittot = oci_result($stid, "SUM(VALUE)"); //credito fatto

$perdebit = $debittot/($credittot+$debittot)*100;//percentuale debito creato confronto al credito fatto
$percredit = $credittot/($credittot+$debittot)*100;//percentuale credito creato confronto al debito fatto
echo "Crediti: ".round($percredit,2)."%<br>";
echo "Debiti: ".round($perdebit,2)."%<br>";
echo "Somma " . $percredit + $perdebit;

$stid = oci_parse($conn, 'SELECT CREDITOR,SUM(VALUE) FROM "DEBTS" WHERE DEBTOR=:USERN GROUP BY CREDITOR');
oci_bind_by_name($stid,":USERN", $_SESSION["name"]);
oci_execute($stid);
$max=null;
while($risultato=oci_fetch_array($stid)){
    if($risultato["SUM(VALUE)"]>$max["SUM(VALUE)"]){
        $max=$risultato;
    }
}
echo "<br>";
print_r($max);//creditore più alto

    $stid = oci_parse($conn, 'SELECT DEBTOR,SUM(VALUE) FROM "DEBTS" WHERE CREDITOR=:USERN GROUP BY DEBTOR');
oci_bind_by_name($stid,":USERN", $_SESSION["name"]);
oci_execute($stid);
$max=null;
while($risultato=oci_fetch_array($stid)){
    if($risultato["SUM(VALUE)"]>$max["SUM(VALUE)"]){
        $max=$risultato;
    }
}
echo "<br>";
print_r($max);//debitore più alto

?>