<?php

    # .htaccess transform the requested url to this file, 
    #  with the old local path as the argument.
    $request = $_SERVER['REQUEST_URI'];

    # split the old path from the URL parameters.
    $param = explode('?', $request);
    $param[0] = rtrim($param[0], '/');
    #remove trailing slashes and load the correct webpage.

    switch ($param[0]) {
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
        case '/addDebt':
            require __DIR__ . '/views/addDebt.php';
            break;
        case '/addCredit':
            require __DIR__ . '/views/addCredit.php';
            break;
        case '/addFriend':
            require __DIR__ . '/views/addFriend.php';
            break;
        case '/createGroup':
            require __DIR__ . '/views/createGroup.php';
            break;
        case '/joinGroup':
            require __DIR__ . '/views/joinGroup.php';
            break;
        case '/admin/fakesadd':
            require __DIR__ . '/functions/adminFuncions/usersFake.php';
            break;
        case '/admin/fakesremove':
            require __DIR__ . '/functions/adminFuncions/usersFakeRemove.php';
            break;
        case '/fnct/register_debt':
            require __DIR__ . '/functions/register_debt.php';
            break;
        case '/fnct/register_credit':
            require __DIR__ . '/functions/register_credit.php';
            break;
        case '/fnct/create_group':
            require __DIR__ . '/functions/create_group.php';
            break;
        case '/fnct/join_group':
            require __DIR__ . '/functions/join_group.php';
            break;
        case '/fnct/friendList':
            require __DIR__ . '/functions/friendList.php';
            break;
        case '/fnct/add_friend':
            require __DIR__ . '/functions/add_friend.php';
            break;
	case '/icanhashphp':
	    phpinfo();
	    break;
        default:
            $paths = explode('/', trim($param[0],"/"));
            if($paths[0]=="user"){
                $userView = $paths[1];
                require __DIR__ . "/views/userView.php";
                break;
            }
            if($paths[0]=="group"){
                $groupView = $paths[1];
                require __DIR__ . "/views/groupView.php";
                break;
            }
            
            http_response_code(404); # set HTTP error code to 404: Not Found, if the route is not found.
            require __DIR__ . '/errors/404.php';
            break;
    }
?>
