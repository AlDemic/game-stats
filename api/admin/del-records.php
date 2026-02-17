<?php 
    //php logic to delete records from db
    include_once dirname(__DIR__, 2) . "/core/init.php";

    wrongMethod(); //if not post

    //json answer
    header('Content-Type: application/json; charset=utf-8');

    try {
        //array mode for safety
        $mode_array = ['one', 'month'];

        //array stat for db check(online/income)
        $stat_array = [ //array for db selection
            'online' => 'g_online',
            'income' => 'g_income'
        ];

        //get stat from form
        $stat = isset($stat_array[$_POST['stat']]) ? $_POST['stat'] : '';
        if($stat === '') json_error("Stat is incorrect", 200);
        $stat_db = $stat_array[$stat];

        //get mode from form(to know 1 record delete or per month)
        $rec_mode = in_array($_POST['mode'], $mode_array) ? $_POST['mode'] : '';
        if($rec_mode === '') json_error('Wrong mode in form', 200);

        //Source only for "one" record delete
        if($rec_mode === 'one') {
            $rec_source = isset($_POST['del-one__source']) ? trim($_POST['del-one__source']) : '';
            if($rec_source === '') json_error('Wrong source', 200);
            if(strlen($rec_source) < 3 || strlen($rec_source) > 128) json_error("Wrong length(3-128 should be)", 200);
        }

        //get game id
        $g_id = isset($_POST['del-id']) ? (int)$_POST['del-id'] : 0;
        if($g_id <= 0) json_error("Wring game id", 200);

        //get month and check for date format
        $g_month = isset($_POST['del-date']) ? $_POST['del-date'] : '';
        if($g_month === '') json_error("Wrong date", 200);
        if(!dateFormatMonth($g_month, 'ym')) json_error("Incorrect format of date", 200);
        
        //make date for db(in db format XXXX-XX-XX)
        $g_month = $g_month . '-01%';

        //make db operation
        switch($rec_mode) {
            case 'one':
                $db_del_records = $pdo->prepare("DELETE FROM $stat_db WHERE source = :source AND id_game = :id_game AND date LIKE :date");
                $db_del_records->bindValue(':source', $rec_source, PDO::PARAM_STR);
                break;
            case 'month':
                $db_del_records = $pdo->prepare("DELETE FROM $stat_db WHERE id_game = :id_game AND date LIKE :date");
                break;
        }
        $db_del_records->bindValue(':id_game', (int)$g_id, PDO::PARAM_INT);
        $db_del_records->bindValue(':date', $g_month, PDO::PARAM_STR);
        $db_del_records->execute();

        //count how many records are deleted
        $del_rec_count = $db_del_records->rowCount();

        if($del_rec_count === 0) {
            json_error("No records to delete", 200);
        }

        //if deleted
        echo json_encode([
            'status' => 'ok',
            'msg' => 'Deleted.'
        ]);

    }catch(PDOException $e) {
        debug($e->getMessage());
    }catch(Exception $e) {
        debug($e->getMessage());
    }
?>