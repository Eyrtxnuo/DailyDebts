<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: /login');
	exit;
}

if(!isset($groupView)){
    exit("code not set?");
}

try {
    
    $conn = oci_pconnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"),'AL32UTF8');
   /* $stid = oci_parse($conn, 'SELECT * FROM "Users" WHERE USERNAME = :user');

    oci_bind_by_name($stid, ":user", $userView);

    oci_execute($stid);*/
    $stid = oci_parse($conn,'SELECT * FROM "GROUPS" WHERE CODE = :code');
	
    oci_bind_by_name($stid, ':code', $groupView);
    oci_execute($stid);
    if(!(oci_fetch($stid))){
        exit($groupView." -> "."Group not found!".print_r(oci_error($stid),true));
    }
    

} catch(Exception $e) {
    exit('Failed to connect to Database. ' . $e->getMessage());
}

?>

Invite code: <?= $groupView ?><br>
Group name: <?= oci_result($stid, "GROUPNAME") ?><br>
Description: <?= oci_result($stid, "DESCRIPTION") ?><br>

Members:
<ul>
<?php
	$stid = oci_parse($conn,'SELECT * FROM "GroupMembers" INNER JOIN "GROUPS" ON GROUPID = ID WHERE CODE = :code');
		
	oci_bind_by_name($stid, ':code', $groupView);
	oci_execute($stid);
	while(oci_fetch($stid)){
		echo "<li>".oci_result($stid, 'USERNAME')."</li>";
	}
?>
</ul>