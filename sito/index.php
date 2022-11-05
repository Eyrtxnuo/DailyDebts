<?php

    # .htaccess transform the requested url to this file, 
    #  with the old local path as the argument.
    $request = $_SERVER['REQUEST_URI'];

    # split the old path from the URL parameters.
    $param = explode('?', $request);
    
    #remove trailing slashes and load the correct webpage.
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
        case '/profile':
            require __DIR__ . '/views/profile.php';
            break;
        case '/logout':
            require __DIR__ . '/functions/logout.php';
            break;
        case '/usersearch':
            require __DIR__ . '/views/userSearch.php';
            break;
        case '/search':
            require __DIR__ . '/functions/searchUser.php';
            break;
        case '/admin/fakesadd':
            require __DIR__ . '/functions/usersFake.php';
            break;
        case '/admin/fakesremove':
            require __DIR__ . '/functions/usersFakeRemove.php';
            break;
        default:
            http_response_code(404); # set HTTP error code to 404: Not Found, if the route is not found.
            require __DIR__ . '/errors/404.php';
            break;
    }
?>