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
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Amico</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css">
    <link rel="stylesheet" href="/resources/typeahead/jquery.typeahead.min.css">
    <link rel="stylesheet" href="/resources/css/base/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/resources/typeahead/jquery.typeahead.min.js"></script>
    <script >
        $(document).ready(function() {
            $.typeahead({
                input: '.typeahead-Users',
                source: {
                    tag: {
                        ajax: function (query) {
                            return {
                                url: "/search?key="+query,
                            }
                        }
                    }
                },
                dynamic: true,
                minLenght: 2,
                asyncResult: false,
                mustSelectItem: false,
                callback: {
                    onInit: function (node) {
                        console.log('Typeahead Initiated on ' + node.selector);
                    }
                }
            });
        });
    </script>
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
        <h1>Aggiungi Amico</h1>
        <form method="post" action="/fnct/add_friend" >
            
            
            <div class="typeahead__container">
                <div class="typeahead__fieldz">
                    <div class="typeahead__queryz">
                        <label for="friend">
                            <i class="fas fa-user"></i>
                        </label>
                        <input id="search-usrn" class="typeahead-Users" name="friend" placeholder="Username" autocomplete="off">
                    </div>
                    <div class="typeahead__button">
                        
                    </div>
                </div>
            </div>

            <br>
            <input type="submit" value="Aggiungi">
        </form>
    </div>
</body>
</html>

