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
    <link rel="stylesheet" href="/resources/css/base/style.ccss">
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
                minLenght: 3,
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

    <div class="window">
        <h1>Aggiungi un credito</h1>
        <form id="form-Debit" name="form-Debit" method="post" action="/fnct/register_credit" >
            
            <div class="typeahead__container">
                <div class="typeahead__fieldz">
                    <div class="typeahead__queryz">
                        <label>
                            <i class="fas fa-user"></i>
                        </label>
                        <input id="search-usrn" class="typeahead-Users" name="user" placeholder="Username" autocomplete="off">
                    </div>
                    <div class="typeahead__button">
                        
                    </div>
                </div>
            </div>
            <label for="sum">
                <i class="fa-solid fa-euro-sign"></i>
            </label>
            <input type="number" name="sum" placeholder="Sum" id="sum" min="0.01" step="0.01" autocomplete="off" required>
            
            <br>

            <label for="sum">
                <i class="fa-solid fa-quote-right"></i>
            </label>
            <textarea name="desc" id="desc" placeholder="Description" cols="40" rows="5" autocomplete="off" maxlength="1000"></textarea>
            
            
            <br>
            <input type="submit" value="Aggiungi">
        </form>
    </div>

</body>
</html>