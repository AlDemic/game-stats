//js logic to get from db stat depends on filter

//global selectors (common for all js files)
const navBlock = document.querySelector('.nav'); //nav block
const mainPageBlock = document.querySelector('.main-page'); //for main page content
const mainTitleGame = document.querySelector('.main__title-game'); // game name in main title block
const gameTitleStat = document.querySelector('.main__title-game-stat');  //chosen stat near with game title
const mainTitleFilters = document.querySelector('.main__title-filters'); // game stats filters in main title block
const mainContentStats = document.querySelector('.main__content-stats'); // game statistic block in content block
const mainContentInfo = document.querySelector('.main__content-info'); // game statistic details from db block in content block
const mainContentBackBtn = document.querySelector('.game-back-btn'); // block in main for rendering back to main page btn

mainContentInfo.addEventListener('click', async (e) => {
    const urlStat = new URLSearchParams(window.location.search).get('stat') ?? 'no';
    
    if(urlStat !== 'no') {
        //switch off btn to wait result
        const loadStatBtn = document.querySelector('#load-stat');
        loadStatBtn.disabled = true;

        try {
            if(e.target.id === 'load-stat') {
                const dateUserYear = document.getElementById('year-select');
                if(!dateUserYear) return;

                const dateUserMonth = document.getElementById('month-select');
                if(!dateUserMonth) return;

                const dateValue = dateUserYear?.value + '-' + dateUserMonth?.value ?? '0000-00'; //if no chose

                //if date is ok
                const idGame = mainTitleGame.dataset.id;
                if(!idGame) return;

                //call func depends on stat
                const result = await collectDbStat(idGame, dateValue, urlStat);

                const statResult = document.querySelector('.stat-result');
                if(!statResult) return;

                //write in html
                statResult.innerHTML = `Average per this month: <b>${result}</b>`;
            }
        } catch(err) {
            console.log(err);
        } finally {
            //switch on btn
            loadStatBtn.disabled = false;
        }
    }
});