//js logic for any render

//logo block 
const logoPicBlock = document.querySelector('.logo__pic'); //logo picture

//logo pic size
let logoHeight = 70;
let logoWidth = 150;

//game stats details
const gameStats = ['online', 'income'];

//game stat filters
const gameStatFilters = ['Average all', 'Average monthly'];

//render
function renderLogo(src='/img/main-logo.png', alt='Game logo') {
    logoPicBlock.innerHTML = `<img src="${src}" width="${logoWidth}" height="${logoHeight}" alt="${alt}"/>`;
}

//navigation render
async function renderNav(navList, filter = 'main') { //filter is selected nav + change logo pic 
    //get globals tags for changing (nav list + logo pic)

    //make render
    if(navList.length > 0) {
        navBlock.innerHTML = ''; //clear nav block

        //render logo depends on url
        if(navList.length > 0 && filter !== 'main') {
            const game = navList.find(nav => nav.url === filter);
            const picGame = game?.pic; 

            renderLogo(picGame, game?.title);
        } else {
            renderLogo();
        }

        //create each game as nav btn
        for(let i = 0; i < navList.length; i++) {
            const navBtn = document.createElement('button');
            navBtn.className = 'btn-56'; 
            navBtn.textContent = navList[i].title;
            if(filter === navList[i].url) {
                navBtn.disabled = true; //selected game
            }

            //change url
            navBtn.addEventListener('click', () => {
                const urlChange = navList[i].url; // /gameName in url
                history.pushState({game: urlChange}, '', `/${urlChange}`);

                //reload routing
                pageRoute();
            });

            //add to html
            navBlock.appendChild(navBtn);
        }
    } else {
        //make "base" navigation
        navBlock.innerHTML = `<b>No games</b>`;

        //render base logo
        renderLogo();  
    }
}

//main content render
function renderContent(game, stat = 'no', url = '/') {
    mainPageBlock.innerHTML = ''; //clear content for main page

    //no selected game
    if(game?.id === 0 && stat === 'no', url === '/') {
        mainTitleGame.innerHTML = 'Choose any game to see details'; //game name
    }

    //selected game url: /game_name
    if(game?.id !== 0 && stat === 'no' && url !== '/') {
        mainTitleGame.innerHTML = game?.title; //selected game
        mainTitleGame.dataset.id = game.id; //add id for date online func

        //add stat near game title
        gameTitleStat.innerHTML = '';

        mainTitleFilters.innerHTML = 'Choose any game stat'; //filters block

        //make game stats with btns click listener to change url
        mainContentStats.innerHTML = '';
        renderGameStats(url);

        mainContentInfo.innerHTML = 'Select any stat to see info';
    }

    //game with stat url: /game_name?stat=
    if(game?.id !== 0 && stat !== 'no' && url !== '/') {
        mainTitleGame.innerHTML = game?.title; //selected game
        mainTitleGame.dataset.id = game.id; //add id for date online func

        //add stat near game title
        gameTitleStat.innerHTML = `<i>[${stat}]</i>`;

        //make game stats with btns click listener to change url
        mainContentStats.innerHTML = '';
        renderGameStats(url);

        //render filters btns
        mainTitleFilters.innerHTML = '';
        renderGameStatFilters(game?.id, stat);

        mainContentInfo.innerHTML = 'Select any filter to see info';
    }

    mainContentBackBtn.innerHTML = `<br/><a href='/' class="back-btn">Back to Main</a>`;
}

//render game details for each game(online, income, etc)
function renderGameStats(url) {
    for(let i = 0; i < gameStats.length; i++) {
        const btn = document.createElement('button');
        btn.textContent = gameStats[i];

        //click listener if pressed -> url /game?stat=online
        btn.addEventListener('click', () => {
            const urlChange = url + `?stat=${gameStats[i]}`;
            history.pushState({gameStat: urlChange}, '', `/${urlChange}`);

            //reload page
            pageRoute();
        }); 

        mainContentStats.appendChild(btn);
    }
}

//render game details filters(average and etc)
function renderGameStatFilters(idGame = 0, stat = 'no') {
    if(idGame !== 0 && stat !== 'no') {
        for(let i = 0; i < gameStatFilters.length; i++) {
            const btn = document.createElement('button');
            btn.textContent = gameStatFilters[i];
            btn.id = i;

            //call function to render content in html
            btn.addEventListener('click', (e) => {
                e.preventDefault(); //stop refresh
                
                const idFilter = e.target.id;
                renderContentInfo(idGame, stat, idFilter);
            });

            mainTitleFilters.appendChild(btn);
        }
    }
}

async function renderContentInfo(idGame, stat, idFilter) {
    let result = 'Choose any filter'; //main var for result
    let averMonthHtml = `
                        <label>
                            Select month:
                            <select id="month-select">
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </label>

                        <label>
                            Year:
                            <input type="number" id="year-select" min="2000" max="2030" step="1"/>
                        </label>
                        <button id='load-stat' class='admin-btn'>Show</button>
                        <div class='stat-result'></div>
    `;

    switch(idFilter) {
        case '0':
            result = await collectDbStat(idGame, '0000-00', stat);
            mainContentInfo.innerHTML = `Average ${stat} for all time: <b>${result}</b>`;
            break;
        case '1':
            mainContentInfo.innerHTML = averMonthHtml;
            break;
        default:
            mainContentInfo.innerHTML = result;
    }
}

//main page render
function renderMainPage() {
    mainContentBackBtn.innerHTML = ''; //clear back btn

    mainPageBlock.innerHTML = `<h2>Game statistics project<h2><br/>
                            <h3>Abilities:</h3><br/>
                            <ul>
                                <li>Various stats: income, online, etc</li>
                                <li>Various filters: average per all time, per month, etc</li>
                                <li>Add records by yourself: start parser/take from json/by form</li>
                                <li>Add games with logo and url</li>
                                <li>Games from db are headers nav - automatically</li>
                            </ul>
                            <br/>
                            <a href='/models/admin/index.php' class="admin-btn">Admin panel</a>`;
}

//render 404 page if wrong url
function wrongUrl(url) {
    const layoutBlock = document.querySelector('.layout');
    layoutBlock.innerHTML = '';

    const div = document.createElement('div');
    const titleText = `<h1>404 - Wrong page.</h1><a href='${url}' class="back-btn">Go back</a>`;

    div.innerHTML = titleText;
    layoutBlock.appendChild(div);
}


