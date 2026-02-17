<?php
    //php logic for collecting game's online details
    include_once dirname(__DIR__, 1) . '/core/init.php'; //connect main file

    //send json back
    header('Content-Type: application/json; charset=utf-8');

    wrongMethod('POST', 'json'); //check correct request method

    try {
        //if all is ok -> get game id
        $jsonData = json_decode(file_get_contents('php://input'), true);
        if(!$jsonData) json_error("No json sent", 200); //if invalid json

        //get game id
        if(!isset($jsonData['id'])) json_error("No send game\'s id", 200);

        //check game id
        $id_game = (int)$jsonData['id'];

        if($id_game <= 0) json_error('Wrong game id', 200);

        //get filter info
        if(!isset($jsonData['filter'])) json_error("No send game\'s date", 200);

        //check filter
        if (!preg_match('/^\d{4}-\d{2}$/', $jsonData['filter'])) {
            json_error("Wrong date format", 200);
        }

        //get game stat for db check(online/income)
        $stat_array = [ //array for db selection
            'online' => 'g_online',
            'income' => 'g_income'
        ];

        //if stat = online -> g_online db 
        $game_stat_db = $stat_array[$jsonData['stat']] ?? null;
        if($game_stat_db === null) json_error('Wrong stat parameter', 200);

        $average_online = 0; //result var

        //get online depends on filter
        if($jsonData['filter'] === '0000-00') { //average from all records
            $average_online = averageOnlineAll($id_game, $pdo, $game_stat_db, $jsonData['stat']);
        } else {
            $average_online = averageOnlineMonth($id_game, $jsonData['filter'], $pdo, $game_stat_db, $jsonData['stat']);
        }
        
        if($average_online === null) $average_online = 0;
        //json answer
        echo json_encode([
            'status' => 'ok',
            'online' => $average_online
        ]);
    }catch(PDOException $e) {
        debug($e->getMessage());
    }catch(Exception $e) {
        debug($e->getMessage());
    }

    //count average online for all month
    function averageOnlineAll($id_game, $pdo, $game_stat_db, $stat) {
        $db_get_online = $pdo->prepare("SELECT AVG($stat) AS average_online FROM $game_stat_db WHERE id_game = ?");
        $db_get_online->bindValue(1, (int)$id_game, PDO::PARAM_INT);
        $db_get_online->execute();

        $average = $db_get_online->fetchColumn(); //get number from db 
        return number_format($average, 1, '.', '');
    }

    //count average online for one month
    function averageOnlineMonth($id_game, $date, $pdo, $game_stat_db, $stat) {
        $date = $date . '%'; //to find in db without month (2020-02%)
        $db_get_online = $pdo->prepare("SELECT AVG($stat) AS average_online FROM $game_stat_db WHERE id_game = ?
                                                                                            AND date LIKE ?
        ");
        $db_get_online->bindValue(1, (int)$id_game, PDO::PARAM_INT);
        $db_get_online->bindValue(2, $date, PDO::PARAM_STR);
        $db_get_online->execute();

        $average = $db_get_online->fetchColumn(); //get number from db 
        return number_format($average, 1, '.', '');
    }
?>