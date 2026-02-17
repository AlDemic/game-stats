<?php 
    //json raw file -> db

    function parsFromJson($pdo, $g_id = null, $source = null, $stat = null, $stat_db = null, $date_from = null, $date_to = null, $json_raw = null) {
        if($g_id === null || $source === null || $stat === null || $stat_db === null || $date_from === null || $date_to === null || $json_raw === null) {
            json_error('Some var is empty', 200);
        }

        //open file (name depends on id_game + stat)
        $file_name = "$json_raw.json";
        $file_path = __DIR__ . "/results/$file_name";

        //exist file
        if(!file_exists($file_path)) json_error('File not found', 200);

        //open file and make assoc array
        $array_records = json_decode(file_get_contents($file_path), true);
        if(empty($array_records)) json_error('File is empty', 200);

        //if not json
        if(json_last_error() !== JSON_ERROR_NONE) json_error('Wrong json format', 200);

        //make array with full vars for db
        $result_array = makeFinalArray($array_records, $g_id, $source);

        //take records depends on date
        $result_array = array_filter($result_array, function($record) use ($date_from, $date_to) {
                    $record_month = substr($record['date'], 0, 7); //get XXXX-XX format
                    return $record_month >= $date_from
                        &&  $record_month <= $date_to;
                });

        //update db and inform user
        $result_db = resultInDb($pdo, $result_array, $stat, $stat_db);

        return $result_db;
    }

    //db record
    function resultInDb($pdo, $array, $stat, $stat_db) {
        
        $count_records = 0; //var to see how many records were done

        //prepare db req
        $db_req = $pdo->prepare("INSERT INTO $stat_db (id_game, date, $stat, source) 
                                                VALUES (:id_game, :date, :stat, :source)
                                                ON DUPLICATE KEY UPDATE $stat = VALUES($stat)
                                                ");

        //update or write new records in db
        foreach($array as $res) {
            $db_req->execute([
                'id_game' => $res['id_game'],
                'date' => $res['date'],
                'stat' => $res['stat'],
                'source' => $res['source']
            ]);
            $count_records++;
        }

        $msg_res = "DB operation is done. Were done for $count_records lines.";
        return $msg_res;
    }










