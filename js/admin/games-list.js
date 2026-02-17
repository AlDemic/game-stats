//js logic to get games from db for admin selection

const apiPath = window.location.origin + '/api/'; //base api path

async function getGamesList() {
    try {
        const req = await fetch(`${apiPath}admin/games-list.php`, {
            method: 'POST',
            headers: {
                'Accept-Type': 'application/json'
            },
        });

        if(!req.ok) throw new Error(`Error! Problem: ${req.status}`);

        //if request ok
        const res = await req.json();

        //get games list block
        const gamesListBlock = document.querySelector('.games-list');
        if(!gamesListBlock) return;

        if(!Array.isArray(res.games)) throw new Error('Error! Problem: not array');

        if(res.games.length === 0) {
            gamesListBlock.innerHTML = 'No games in db. Add any game firstly.';
            return;
        }

        res.games.forEach(game => {
            const div = document.createElement('div');
            div.className = 'game-block';
            div.innerHTML = `
                            <p>Game Title: <b>${game.title}</b> - ID: <b>${game.id}</b></p><br/>
            `;
                
            //add to html block
            gamesListBlock.appendChild(div);
        });
    } catch(err) {
        console.log(err);
    }
}

getGamesList(); //load on page