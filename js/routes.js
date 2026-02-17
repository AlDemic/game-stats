//js routing logic

async function pageRoute() {
    //get games list from db
    const gameList = await loadGamesList();

    //get current url
    const url = window.location.pathname;
    const urlClean = url.slice(1); //to get "clean" game title(without "/")

    //check if url exist(game exist in array)
    const isExistUrl = gameList.navList.find(game => game.url === urlClean);

    //check stat in url
    const urlStat = new URLSearchParams(window.location.search).get('stat') ?? 'no'; 

    if(url === '/' || url === '/index.php') {
        //navigation render
        renderNav(gameList.navList, 'main');
        renderMainPage();
    }

    if(isExistUrl) { //check without "/" in url
        //nav render
        renderNav(gameList.navList, urlClean);
        renderContent(isExistUrl, urlStat, urlClean); //url: /game_name
    }

    if(!isExistUrl && url !== '/' && url !== '/index.php') {
        wrongUrl('/');
    }
}

pageRoute(); //load page

//SPA back btn 
window.addEventListener('popstate', () => {
    pageRoute();
});