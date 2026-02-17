<?php 
    include_once dirname(__DIR__, 2) . "/core/init.php";

    wrongMethod(); //if not post

    adminOnly(); //admin only(for safety)

    //if ok start main logic

    try {
        //get data from from

        $stat_array = [
            'online' => 'g_online',
            'income' => 'g_income'
        ];

        //get stat from form
        $stat_db = $stat_array[$_POST['stat']] ?? null;
        if($stat_db === null) json_error('Wrong stat', 200);
        $stat = $_POST['stat']; //to get key text

        //get ID game
        $g_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if($g_id <= 0) json_error('Wrong game id', 200);

        //get stat(online/income) of game
        $g_stat_number = isset($_POST['stat_number']) ? (int)$_POST['stat_number'] : 0;
        if($g_stat_number <= 0) json_error('Wrong game stat number', 200);

        //get date
        $g_date = $_POST['date'] ?? '';
        if($g_date === '') json_error("No send online\'s date", 200);

        //check date filter
        if(!dateFormatMonth($g_date, 'ym')) json_error("Incorrect format of date", 200);

        //add "fake" day for date to put in db (bcs in db XXXX-XX-XX)
        $g_date_day = $g_date . '-01';

        //get source
        $g_source = removeXSS(trim(strtolower($_POST['source']))) ?? ''; //remove tags and spaces

        //source empty
        if($g_source === '') json_error('Game source can\'t be empty.', 200);
        
        //source length incorrect(3-64)
        $g_source_length = (int)strlen($g_source);
        if($g_source_length < 3 || $g_source_length > 128) json_error("Source length is incorrect(Min 3, Max 128)", 200);

        $date_not_day = $g_date . '%';
        //!check date and source unique in db
        $check_date_source = $pdo->prepare("SELECT 1 FROM $stat_db WHERE id_game = ? AND source = ? AND date LIKE ?");
        $check_date_source->bindValue(1, (int)$g_id, PDO::PARAM_INT);
        $check_date_source->bindValue(2, $g_source, PDO::PARAM_STR);
        $check_date_source->bindValue(3, $date_not_day, PDO::PARAM_STR);
        $check_date_source->execute();
        if($check_date_source->fetchColumn()) json_error('Game with this date and source exist already!', 200);

        //add game to DB
        $add_stat_db = $pdo->prepare("INSERT INTO $stat_db (id_game, date, $stat, source) VALUES (:id_game, :date, :stat, :source)");
        $add_stat_db->execute([
            'id_game' => $g_id,
            'date' => $g_date_day,
            'stat' => $g_stat_number,
            'source' => $g_source
        ]);

        //send back json 
        json_ok("$stat is added!");
    } catch(PDOException $e) {
        debug($e->getMessage());
    } catch(Exception $e) {
        debug($e->getMessage());
    }
?>