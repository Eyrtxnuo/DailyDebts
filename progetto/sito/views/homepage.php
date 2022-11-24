<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css">
    <link rel="stylesheet" href="/resources/css/base/style.css">
</head>

<body style="height:100vh; max-height: 100vh; margin:0; ">
    <img src="..\resources\immagini\LogoNoBG.png">
    <div style=" 	
            display: flex;
            flex-direction: column; 	
            flex-wrap: wrap;        	
            justify-content: center;	
            align-items: center;">
        <div style="margin:auto">
            <div id="btnLog" >
                <!-- <p>hai gia un account:</p> -->
                
                <a href="/login"><button class="btnHome">Login</button></a>
            </div>
            <div id="btnReg">
                <!-- <p>non hai ancora un account:</p> -->
                <a href="/register"><button class="btnHome">Register</button></a>
            </div>
        </div>
    </div>
</body>

</html>
