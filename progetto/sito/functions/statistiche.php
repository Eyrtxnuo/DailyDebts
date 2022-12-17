<table>
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
$debittot = oci_result($stid, "SUM(VALUE)");

$stid = oci_parse($conn, 'SELECT SUM(VALUE) FROM "DEBTS" WHERE CREDITOR=:USERN');
oci_bind_by_name($stid,":USERN", $_SESSION["name"]);
oci_execute($stid);
oci_fetch($stid);
$credittot = oci_result($stid, "SUM(VALUE)");
$percredit  =  $credittot / ($debittot+$credittot)*100;
$perdebit  =  $debittot / ($debittot+$credittot)*100;


echo "<tr><td>Crediti:</td><td> ".round($percredit,2)."%</td></tr>";
echo "<tr><td>Debiti:</td><td> ".round($perdebit,2)."%</td></tr>";

$stid = oci_parse($conn, 'SELECT CREDITOR,SUM(VALUE) FROM "DEBTS" WHERE DEBTOR=:USERN GROUP BY CREDITOR');
oci_bind_by_name($stid,":USERN", $_SESSION["name"]);
oci_execute($stid);
$max = null;
while($res=oci_fetch_array($stid)){
    if($res["SUM(VALUE)"]>$max["SUM(VALUE)"]){
        $max = $res;
    }
}
    echo "<tr><td>Hai avuto più debiti con:</td><td>".$max[0]." (".$max[1]."€)</td></tr>";


$stid = oci_parse($conn, 'SELECT DEBTOR,SUM(VALUE) FROM "DEBTS" WHERE CREDITOR=:USERN GROUP BY DEBTOR');
oci_bind_by_name($stid,":USERN", $_SESSION["name"]);
oci_execute($stid);
$max = null;
while($res=oci_fetch_array($stid)){
    if($res["SUM(VALUE)"]>$max["SUM(VALUE)"]){
        $max = $res;
    }
}
    echo "<tr><td>Hai avuto più crediti con:</td><td>".$max[0]." (".$max[1]."€)</td></tr>";


?>
</table>