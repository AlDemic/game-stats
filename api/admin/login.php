<?php 
    require_once dirname(__DIR__, 2) . "/core/init.php"; 

    wrongMethod(); //check if not POST method

    if(isset($_SESSION['admin']) && $_SESSION['admin'] === 1) adminAlready(); //if already admin

    //globals for admin
    define('ADMIN_LOGIN', 'admin');
    define('ADMIN_PASS', '1212');

    //get globals from user's form
    $u_login = trim($_POST['login']) ?? ''; //login
    $u_pass = $_POST['pass'];

    try {
        if($u_login === ADMIN_LOGIN && $u_pass === ADMIN_PASS) {
            //create session for 24 hour
            session_regenerate_id();

            //get info of session
            $new_sess = session_get_cookie_params();
            
            //make long life cookie
            setcookie(
                session_name(),
                session_id(),
                [
                    'expires' => time() + 24*3600,
                    'path' => $new_sess['path'],
                    'domain' => $new_sess['domain'],
                    'secure' => $new_sess['secure'],
                    'httponly' => $new_sess['httponly'],
                    'samesite' => 'Lax'
                ]
            );

            //create global session
            $_SESSION['admin'] = 1;

            //inform user that admin
            html_ok('You are admin for 24 hour!', '/models/admin/index.php');
        } else {
            html_error('Wrong admin data. Try again..', '/models/admin/index.php');
        }
    } catch(Exception $e) {
        debug($e->getMessage());
    }

?>