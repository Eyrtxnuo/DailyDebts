<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: /login?ref='.$_SERVER['REQUEST_URI']);
	exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>+ credito</title>
    <link rel="stylesheet" href="/resources/typeahead/jquery.typeahead.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/resources/typeahead/jquery.typeahead.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css">
    <link rel="stylesheet" href="/resources/css/base/style.css">
    <script>
        $(document).ready(function() {
            $.typeahead({
                input: '.typeahead-Users',
                source: {
                    tag: {
                        ajax: function (query) {
                            return {
                                url: "/search?<?= isset($_GET["group"])?"group=".$_GET["group"]."&":"";   ?>key="+query,
                            }
                        }
                    }
                },
                dynamic: true,
                minLenght: 2,
                asyncResult: false,
                mustSelectItem: false,
                cancelButton: true,
                callback: {
                    onInit: function (node) {
                        console.log('Typeahead Initiated on ' + node.selector);
                    }
                }
            });
        });
    </script>
    <style>
         #search-usrn[readonly]{
            background: lightgray !important;
        }
    </style>
</head>
<body>

<nav class="navtop">
        <div>
            <a href="/dashboard" style="display: contents;"><img src="\resources\immagini\LogoNoBG.png"></a>
            <div style="width:100%; justify-content: flex-end;">
                <a href="/dashboard" class="navElement"><i class="fa-solid fa-house"></i>Dashboard</a>
                <a href="/logout" class="navElement"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </div>
    </nav>
<div class="window">
    <h1>Aggiungi un credito
        <?php
                if(isset($_GET["group"])){
                    include_once($_SERVER['DOCUMENT_ROOT'] . "/functions/phpUtils.php");
                    echo "<br>(Gruppo: <strong>\"". _UTILS_getGroupByCode($_GET["group"])["GROUPNAME"] ."\"</strong>)"; 
                }
            
             ?>
        </h1>
        <form id="form-Debit" name="form-Debit" method="post" action="/fnct/register_credit?<?= isset($_GET["group"])?"group=".$_GET["group"]:"";   ?>" >
            
            <div class="typeahead__container">
                <div class="typeahead__fieldz">
                    <div class="typeahead__queryz">
                        <label for="user" class="field">
                            <i class="fas fa-user"></i>
                        </label>
                        <input  id="search-usrn" class="typeahead-Users" name="user" placeholder="Username" autocomplete="off" value=<?= $_GET["user"] ?> <?= (isset($_GET["user"])&& $_GET["user"] != "")?"readonly":"" ?> >
                    </div>
                    <div class="typeahead__button">
                        
                    </div>
                </div>
            </div>
            <br>
            <label for="sum" class="field">
                <i class="fa-solid fa-euro-sign"></i>
            </label>
            <input type="number" name="sum" placeholder="Valore" id="sum" min="0.01" step="0.01" autocomplete="off" required>
            
            <br>

            <label for="desc" class="field">
                <i class="fa-solid fa-quote-right"></i>
            </label>
            <textarea name="desc" id="desc" placeholder="Description" cols="40" rows="5" autocomplete="off" maxlength="1000"></textarea>
            
            
            <br>
            <input type="submit" value="Aggiungi">
        </form>
    </div>
    
</body>
</html>