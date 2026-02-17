<?php
    //online stat parser of ZZZ from activeplayer.io
    include_once 'common-func.php'; //file with common parser's common func

    //main function to start from controller
    function zzzOnlineStart() {
        $url = 'https://activeplayer.io/zenless-zone-zero/';

        //get $xpath 
        $xpath = parseUrl($url);
        if($xpath === null) return 'Problem with parser. Check logFile';

        //get online from parsed page
        $table = $xpath->query("//table[contains(@class, 'asdrm-monthly-stats-table')]/tbody//tr");

        //make assoc array to join apple/android stat
        $dates_stat = [];

        //get from parser date and online
        foreach($table as $tr) {
            //get td for current tr
            $tdMonthYear = $xpath->query("td", $tr)->item(0)->textContent;
            $tdStat = $xpath->query("td", $tr)->item(1)->textContent;
            
            //call function to make good view
            $date = lineMonthYearToDate($tdMonthYear);
            $stat = lineStatToFloat($tdStat);

            $dates_stat[$date] = round(($dates_stat[$date] ?? 0.0) + $stat, 1);
        }

        if(!is_array($dates_stat) || count($dates_stat) === 0) return "Array from parser is empty.";

        //save as json
        saveAsJson($dates_stat, '/results/', 'zzz-activeplayerio');

        //answer that is done
        return 'Parser for ZZZ online from activeplayer.io is done.';
    }