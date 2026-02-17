//header (navigation) js logic

async function loadGamesList(filter = 'main') {
    try {
        //request to db to get "games" list
        const req = await fetch(`${apiPath}navigation/header.php`, {
            method: 'POST',
            headers: {
                'Accept-Type': 'application/json'
            }
        });

        if(!req.ok) throw new Error(`Error! Problem: ${req.status}`);

        //if no problem -> get json and call render function
        const res = await req.json(); //get game list
        
        return res; //give games list
    } catch(err) {
        console.log(err);
    }
}
