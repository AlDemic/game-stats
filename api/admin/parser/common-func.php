<?php
    //common function for game stats parser

    function parseUrl($url) {
        libxml_use_internal_errors(true);  //html warning in buffer
    
        $html = fetch($url);

        //if curl error -> fetch return null => exit for next url
        if(!$html) return null;

        $dom = new DOMDocument();
        $dom->loadHTML($html);

        libxml_clear_errors(); //clean buffer with html warnings

        $xpath = new DOMXPath($dom);

        return $xpath;
    }

    //common fetch for url
    function fetch($url) {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEJAR => __DIR__.'/cookies.txt',
            CURLOPT_COOKIEFILE => __DIR__.'/cookies.txt',
            CURLOPT_TIMEOUT => 15,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
                "Accept: text/html",
                "Accept-Language: en-US,en;q=0.9",
            ]
        ]);

        $html = curl_exec($ch);
        $errNumber = curl_errno($ch);
        $errMsg = curl_error($ch);
        $curlDetails = curl_getinfo($ch);
        curl_close($ch);

        //if has any curl error
        if($errNumber) {
            logParse('ERROR', 'Curl failed', ['url' => $url, 'errNumber' => $errNumber, $errMsg => $errMsg]);

            return null;
        }

        //if has http error
        $httpCode = $curlDetails['http_code'] ?? 0;
        if($httpCode >= 400) {
            logParse('Warning', 'Http error', ['url' => $url, 'code' => $httpCode]);

            return null;
        }

        //delay
        usleep(rand(500000, 1500000)); // 0.5 - 1.5 sec

        //write result of curl in parser.log 
        logParse('OK', 'Curl is ok', ['url' => $url, 'details' => $curlDetails]);

        return $html;
    }

    //log function
    function logParse($tag, $msg, $array = []) {
        $file = __DIR__ . '/../parser/parser.log';
        $time = date('Y-m-d H:i:s');
        $arrayToStr = $array ? ' ' . json_encode($array, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) : '';
        $str = "[$time] [$tag] $msg $arrayToStr" . PHP_EOL;

        //write to file
        file_put_contents($file, $str, FILE_APPEND);
    }

    //function to make from line "Month year" -> "xxxx-xx-01"
    function lineMonthYearToDate($dateLine) {
        $month_array = [
            'january' => '01',
            'february' => '02',
            'march' => '03',
            'april' => '04',
            'may' => '05',
            'june' => '06',
            'july' => '07',
            'august' => '08',
            'september' => '09',
            'october' => '10',
            'november' => '11',
            'december' => '12'
        ];

        //separate line to array
        $line_to_array = explode(" ", strtolower($dateLine));

        //global vars for month and year
        $month = null;
        $year = null;

        foreach($line_to_array as $line) {
            if(preg_match('/^[0-9]{4}$/', $line)) $year = $line; 
            if(array_key_exists(trim($line), $month_array)) $month = $month_array[$line];
        }

        if($month === null || $year === null) return false;

        $date_line = "$year-$month-01";
        return $date_line;
    }

    //func to make stat from string to float: '451.3k' -> '451.3'
    function lineStatToFloat($line) {
        $stat = (float)$line;
        return $stat;
    }

    //make from array['date'=>'stat'] -> final array with key: id_game, date, stat, source
    function makeFinalArray($array_date_stat, $id_game, $source) {
        $final_array = [];
        
        foreach($array_date_stat as $date => $stat) {
            $final_array[] = [
                'id_game' => $id_game,
                'date' => $date,
                'stat' => $stat,
                'source' => $source
            ];
        }

        return $final_array;
    }

    //save result as json file
    function saveAsJson($array, $path, $file_name) {
        $path = __DIR__ . $path;
        $file_name = $file_name . '.json';

        $full_path = $path . $file_name;
        
        //save json file
        $result = file_put_contents($full_path, PHP_EOL . json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        if($result === false) return 'Wrong json file creation';
    }


