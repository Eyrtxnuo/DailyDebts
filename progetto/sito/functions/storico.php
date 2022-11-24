<style>
td{
    border: 1px solid black;
}
table{
    border-collapse: collapse;
}
</style>

<?php
    // We need to use sessions, so you should always start sessions using the below code.
    session_start();
    if(!isset($_SESSION['loggedin']))
    {
        header('Location: /login');
    }
    $user=$_SESSION["name"];
    
    $query="SELECT q.ID,CREDITOR,DEBTOR,q.DESCRIPTION,VALUE,q.CREATED_AT,gr.CODE FROM DEBTS q LEFT JOIN GROUPS gr ON gr.ID = GROUP_ID where DEBTOR= :deb OR CREDITOR= :cred ORDER BY q.ID";
    
    $conn = oci_pconnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), 'AL32UTF8');
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":deb", $user);
    oci_bind_by_name($stid, ":cred", $user);
    oci_execute($stid);

    // if(oci_result($stid,'debitor')>0)
    // {
        echo '<table>';
        echo '<tr>';
        echo '<th> ID</th>';
        echo '<th> Debitor</th>';
        echo '<th> Creditor</th>';
        echo '<th> Description</th>';
        echo '<th> Sum</th>';
        echo '<th> Created At</th>';
        echo '<th> Group ID</th>';
        echo '</tr>';

        while ($row = oci_fetch_array($stid)) {
            echo '<tr>';
            echo '<td>' . $row['ID'] . '</td>';
            echo '<td>' . $row['DEBTOR'] . '</td>';
            echo '<td>' . $row['CREDITOR'] . '</td>';
            echo '<td>' . $row['DESCRIPTION'] . '</td>';
            echo '<td>' . $row['VALUE'] . '€ </td>';
            echo '<td>' . $row['CREATED_AT'] . '</td>';
            echo '<td>' . $row['CODE'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
?>