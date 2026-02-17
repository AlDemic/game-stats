<?php 
    include_once dirname(__DIR__, 2) . "/core/init.php";

    wrongMethod(); //if not post

    adminOnly(); //admin only(for safety)

    //if ok start main logic

    try {
        //get data from from
        $g_title = removeXSS(trim($_POST['title'])) ?? ''; //remove tags and spaces

        //title empty
        if($g_title === '') json_error('Game title can\'t be empty.', 200);
        
        //title length incorrect(3-64)
        $g_title_length = (int)strlen($g_title);
        if($g_title_length < 3 || $g_title_length > 64) json_error("Title length is incorrect(Min 3, Max 64)", 200);

        $g_url = strtolower(str_replace(' ', '-', $g_title));

        $g_year = (int)removeXSS(trim($_POST['year'])) ?? ''; //remove tags and spaces

        //year is empty
        if($g_year === '') json_error("Game year can't be empty.", 200);

        //year length incorrect
        $g_year_length = (int)strlen($g_year);
        if($g_year_length < 4 || $g_year_length > 4) json_error("Year length is incorrect(Should be XXXX format)", 200);
    
        //pic part
        $logo_pic = $_FILES['logo'] ?? '';

        //no have pic
        if($logo_pic === '') json_error("You didn't select pic", 200);

        //check for errors
        if($logo_pic['error'] !== UPLOAD_ERR_OK) json_error('Error during loading pic', 200);

        //check for pic size(not more than 2mb)
        if($logo_pic['size'] > 2 * 1024 * 1024) json_error('Picture can\'t be more than 2mb', 200);

        //check for extension 
        $ext_array = ['png', 'jpg', 'jpeg', 'webp'];
        $ext_logo = strtolower(pathinfo($logo_pic['name'], PATHINFO_EXTENSION));

        if(!in_array($ext_logo, $ext_array)) json_error("Wrong extension of pic", 200);

        //where to save
        $clean_logo_title = str_replace(' ', '_', $g_title);
        $pic_path = "/img/$clean_logo_title.$ext_logo";
        $save_path = $_SERVER['DOCUMENT_ROOT'] . $pic_path;

        //save it
        if(!move_uploaded_file($logo_pic['tmp_name'], $save_path)) json_error("Problem during saving", 200);

        //add game to DB
        $db_game = $pdo->prepare("INSERT INTO games (title, realesed_year, pic, url) VALUES (:title, :realesed_year, :pic, :url)");
        $db_game->execute([
            'title' => $g_title,
            'realesed_year' => $g_year,
            'pic' => $pic_path,
            'url' => $g_url
        ]);

        //send back json 
        json_ok('Game is added!');
    } catch(PDOException $e) {
        debug($e->getMessage());
    }
?>