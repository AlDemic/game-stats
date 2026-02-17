<?php
    //parser logic
    //steps: get form data -> validate data -> check "type" -> call dif function and receive array -> put in DB -> json answer
    include_once dirname(__DIR__, 2) . "/core/init.php";
    include_once __DIR__ . "/parser/parsJsonRaw.php";
    include_once __DIR__ . "/parser/activeplayerio.php";

    //send json only
    header('Content-Type: application/json; charset=utf-8');

    wrongMethod(); //if not post

    //if ok start main logic

    try {
        //get mode parser
        $mode = $_POST['mode'] ?? '';

        switch($mode) {
            case 'run':
                startParser();
                break;
            case 'load':
                loadJsonRaw($pdo);
                break;
            default:
                echo json_encode([
                    'status' => 'ok',
                    'msg' => 'Wrong parser mode'
                ]);
        }
        
    } catch(PDOException $e) {
        debug($e->getMessage());
    } catch(Exception $e) {
        debug($e->getMessage());
    }


    //function to start selected parser
    function startParser() {
        //parsers array of function names for safety
        $parsers = [
            'zzz-activeplayerio' => 'zzzOnlineStart'
        ];

        //get user's selection
        $selected_parser = isset($_POST['parser-run']) ? $_POST['parser-run'] : '';
        
        //check that parser exist
        if(!array_key_exists($selected_parser, $parsers) || $selected_parser === '') json_error("Wrong parser" , 200);

        //run parser
        $func_name = $parsers[$selected_parser];
        $parser_result = $func_name();

        //send back json
        echo json_encode([
            'status' => 'ok',
            'msg' => $parser_result
        ]);
    }

    //function to take json raw file(after parser) and insert(update) db depends on dates
    function loadJsonRaw($pdo) {
        $stat_array = [ //array for db selection
            'online' => 'g_online',
            'income' => 'g_income'
        ];

        //which json take filter
        $parser_files = ['zzz-activeplayerio'];

        //get vars from form

        //game id
        $g_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if($g_id <= 0) json_error("Wrong game id", 200);

        //source 
        $source = isset($_POST['source']) ? $_POST['source'] : '';
        if($source === '') json_error("Source not exist", 200);
        if(strlen($source) < 3 || strlen($source) > 128) json_error("Source length is incorrect. Should be from 3 to 128 symbols.", 200);

        //game stat
        $stat = isset($stat_array[$_POST['stat']]) ? $_POST['stat'] : '';
        if($stat === '') json_error("Stat is incorrect", 200);
        $stat_db = $stat_array[$stat];

        //date from
        $date_from = $_POST['date-from'] ?? '';
        if($date_from === '') json_error("No send date FROM", 200);
        if(!dateFormatMonth($date_from, 'ym')) json_error("Incorrect format of date FROM", 200);

        //date to
        $date_to = $_POST['date-to'] ?? '';
        if($date_to === '') json_error("No send date TO", 200);
        if(!dateFormatMonth($date_to, 'ym')) json_error("Incorrect format of date TO", 200);
        
        //select RAW json file to take (parser result)
        $json_raw = $_POST['json-raw'] ?? '';
        if(!in_array($json_raw, $parser_files)) json_error('Filter not exist', 200);
        if($json_raw === '') json_error("Wrong parser filter", 200);

        //call function
        $result_msg = parsFromJson($pdo, $g_id, $source, $stat, $stat_db, $date_from, $date_to, $json_raw);

        //json answer if all ok
        echo json_encode([
            'status' => 'ok',
            'msg' => $result_msg
        ]);
    }

?>
