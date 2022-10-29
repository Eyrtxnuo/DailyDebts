<?php

    $request = $_SERVER['REQUEST_URI'];
    
    $param = explode('?', $request);
    try{
        switch (rtrim($param[0], '/')) {
            case '/':
            case '':
            case '/home':
                require __DIR__ . '/views/homepage.php';
                break;
            case '/login':
                require __DIR__ . '/views/login.php';
                break;
            case '/register':
                require __DIR__ . '/views/register.php';
                break;
            case '/dashboard':
                require __DIR__ . '/views/dashboard.php';
                break;
            default:
                require __DIR__ . '/errors/404.php';
                break;
        }
    }catch(Exception $e){
        require __DIR__ . '/errors/404.php';
    }
?>