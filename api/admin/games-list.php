<?php 
    //get list of games from DB (id, title)
    include_once dirname(__DIR__, 2) . "/core/init.php";

    wrongMethod(); //if not post

    header('Content-Type: application/json;charset=utf-8'); //send json

    try {
        //get ID and game TITLE from DB(games)
        $db_get_games = $pdo->prepare("SELECT id, title FROM games");
        $db_get_games->execute();

        $get_games = $db_get_games->fetchAll(PDO::FETCH_ASSOC);

        //check if array is empty
        if(!$get_games) $get_games = [];

        //send back json
        echo json_encode([
            'status' => 'ok',
            'games' => $get_games
        ]);
    } catch(PDOException $e) {
        debug($e->getMessage());
    } catch(Exception $e) {
        debug($e->getMessage());
    }

?>