//js logic with function to get game statistics from DB
const apiPath = window.location.origin + "/api/"; //api path

//collect online
async function collectDbStat(idGame, date = '0000-00', dbStat) { //filter is for render parameter; stat for db choice
    try {
        const req = await fetch(`${apiPath}collect-db-stats.php`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            body: JSON.stringify({id: idGame, filter: date, stat: dbStat})
        });

        if(!req.ok) throw new Error(`Error! Problem is: ${req.status}`);

        //if request is ok get json
        const res = await req.json();

        if(!res.online) res.online = 0;

        //give back result
        return res.online;
    } catch(err) {
        console.log(err);
    }
}