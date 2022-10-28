<?php
    if(!isset($_SESSION['username'])){
        require_once 'config.php';
        $sql="SELECT * FROM casa";
        if($result = mysqli_query($link,$sql)){
            if(mysqli_num_rows($result)>0){
                echo "<table>";
                    echo"<thead>";
                        echo"<tr>";
                            echo "<th>ID</th>";
                            echo "<th>debito</th>";
                            echo "<th>persone</th>";
                            echo "<th>costo</th>";
                        echo"</tr>";
                    echo"</thead>";
                    echo"<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo"<tr>";
                                echo "<td>".$row['ID']."</td>";
                                echo "<td>".$row['debito']."</td>";
                                echo "<td>".$row['persone']."</td>";
                                echo "<td>".$row['costo']."</td>";
                                echo "<td> <a href='cancella.php?id=".$row['ID'] . "'> D </a> </td>";
                            echo"</tr>";
                        }
                    echo"</tbody>";
                echo"</table>";
                echo "<a href='paginaPrincipale.php'> indietro </a>";
                mysqli_free_result($result);
            }else{
                echo"<br><em>No records found</em>";
            }
        }else{
            echo"ERROR: Could not able to execute $sql";
        }
        mysqli_close($link);
    }else{
        header("location: paginaPrincipale.php");
    }
        
    ?>