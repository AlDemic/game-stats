<?php 
    //get records of game per month from DB (stat(online/income)), source)
    include_once dirname(__DIR__, 2) . "/core/init.php";

    wrongMethod(); //if not post

    header('Content-Type: application/json;charset=utf-8'); //send json

    try {
        //array for db selection and stat
        $stat_array = [
            'online' => 'g_online',
            'income' => 'g_income'
        ];

        //get json
        $json_data = json_decode(file_get_contents('php://input'), true);
        if(!$json_data) return json_error('no sent json', 200);

        //get id game
        $g_id = isset($json_data['id']) ? (int)$json_data['id'] : 0;
        if($g_id <= 0) json_error('Wrong game id', 200);

        //get stat
        $g_stat = isset($stat_array[trim($json_data['stat'])]) ? trim($json_data['stat']) : '';
        if($g_stat === '') json_error('Wrong stat name', 200);

        //db column depends on stat (as key)
        $g_stat_db = $stat_array[$g_stat];

        //get month
        $g_month = isset($json_data['month']) ? $json_data['month'] : '';
        if($g_month === '') json_error('Wrong month', 200);

        //check date filter
        if(!dateFormatMonth($g_month, 'ym')) json_error('Wrong date format', 200);

        $g_month = $g_month . '%'; //to not check DAY in date
        //if all is ok get records from db
        $db_get_records = $pdo->prepare("SELECT $g_stat, source FROM $g_stat_db WHERE id_game = ? AND date LIKE ?");
        $db_get_records->bindValue(1, (int)$g_id, PDO::PARAM_INT);
        $db_get_records->bindValue(2, $g_month, PDO::PARAM_STR);
        $db_get_records->execute();

        //get array
        $records = $db_get_records->fetchAll(PDO::FETCH_ASSOC);

        //send json
        echo json_encode([
            'status' => 'ok',
            'records' => $records
        ]);
        
    } catch(PDOException $e) {
        debug($e->getMessage());
    } catch(Exception $e) {
        debug($e->getMessage());
    }

?>