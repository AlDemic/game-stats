<?php 
    //additional functions as assist

    //debug writing to txt file
    function debug($msg) {
        try {
            //time for record
            $time = date("Y-m-d H:i:s");

            //file name
            $file = __DIR__ . '/debug.txt';

            //text line
            $err_msg = "$time : $msg";

            //write to end of file
            file_put_contents($file, PHP_EOL . $err_msg, FILE_APPEND);
            exit;
        } catch(Exception $e) {
            error_log($e->getMessage());
        }
    }

    //html ok 
    function html_ok($msg = 'Okay', $back = '/') {
        header('Content-Type: text/html; charset=utf-8');
        echo "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <title>Ok</title>
                <link rel='stylesheet' href='/css/styles.css'>
            </head>
            <body>
                <main class='main error-page'>
                    <h2>Ok</h2>
                    <p>$msg</p>
                    <a href='$back' class='admin-btn'>← Go back</a>
                </main>
            </body>
            </html>
            ";

        exit;
    }

    //html error 
    function html_error($msg = 'Something wrong', $back = '/') {
        header('Content-Type: text/html; charset=utf-8');
        echo "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <title>Error</title>
                <link rel='stylesheet' href='/css/styles.css'>
            </head>
            <body>
                <main class='main error-page'>
                    <h2>Error</h2>
                    <p>$msg</p>
                    <a href='$back' class='admin-btn'>← Go back</a>
                </main>
            </body>
            </html>
            ";

        exit;
    }
    
    //json ok
    function json_ok($msg = 'Ok', $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'ok',
            'msg' => $msg
        ]);
        exit;
    }

    //json error 
    function json_error($msg = 'Something wrong', $code = 400) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'msg' => $msg
        ]);
        exit;
    }

    //wrong method
    function wrongMethod($method = 'POST', $type = 'html') {
        if($_SERVER['REQUEST_METHOD'] !== $method) {
            if($type === 'json') {
                json_error('Wrong method!');
            } else {
                html_error('Wrong method!');
            }
        }
    }

    //admin already
    function adminAlready($msg = 'You are admin already', $back = '/') {
        header('Content-type: text/html; charset=utf-8');
        echo $msg . "<br/>";
        echo "<a href='$back'><- Go back</a><br/>";
        echo '<META HTTP-EQUIV="refresh" content="1;URL=' . $back . '">';
        exit;
    }

    //admin only
    function adminOnly() {
        if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== 1) {
            html_error("For admin only!");
        }
    }

    //remove XSS
    function removeXSS($text) {
        if($text === null) return '';

        $text = strip_tags($text);
        $text = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return $text;
    }

    //check date format 
    //format 'y' -> XXXX / 'ym' -> XXXX-XX / 'ymd' -> XXXX-XX-XX
    function dateFormatMonth($date, $format) {
        $validation = false;
        switch($format) {
            case 'ym':
                if(preg_match('/^\d{4}-\d{2}$/', $date)) {
                    $validation = true;
                    break;
                }
            case 'y':
                if(preg_match('/^\d{4}$/', $date)) {
                    $validation = true;
                    break;
                }
            default:
                $validation = false;
        }

        //return answer
        return $validation;
    }
?>
