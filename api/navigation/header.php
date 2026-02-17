<?php 
    //header main php logic
    //Take game list from db as navigation
    //send as json / if no games send 'empty'

    include_once dirname(__DIR__, 2) . '/core/init.php';

    header('Content-Type: application/json; charset=utf-8'); //json answer 

    wrongMethod('POST', 'json'); //if not post method give json error

    try {
        //get game list from db
        $db_game_list = $pdo->prepare("SELECT id, title, pic, url FROM games");
        $db_game_list->execute();

        $game_list = $db_game_list->fetchAll(PDO::FETCH_ASSOC); //game list array

        //send json
        echo json_encode([
            'status' => 'ok',
            'navList' => $game_list
        ]);
    } catch(PDOException $e) {
        debug($e->getMessage());
    } catch(Exception $e) {
        debug($e->getMessage());
    }

?>