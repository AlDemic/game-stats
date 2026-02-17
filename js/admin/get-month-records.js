//js logic to show records per month

//global var
const monthInfoBlock = document.querySelector('.month-info'); //block with records

if(monthInfoBlock) {
    //switch off submit btn
    const submitBtnControl = document.querySelector('#month-info__show');
    submitBtnControl.disabled = true;

    try {
        monthInfoBlock.addEventListener('click', async (e) => {

            if(e.target.id === 'month-info__show') {
                e.preventDefault(); //stop auto reload

                //get stat for correct db selection
                const statGame = new URLSearchParams(window.location.search).get('stat') ?? 'no';
                if(!statGame) return;
                
                //get id from user
                const idGame = document.getElementById('month-info__id')?.value ?? 0;
                if(!idGame) return;

                //get month from user
                const monthGame = document.getElementById('month-info__date')?.value ?? 0;
                if(!monthGame) return;

                //make server request
                const req = await fetch(`${apiPath}admin/get-month-records.php`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'json/application'
                    },
                    body: JSON.stringify({id: idGame, month: monthGame, stat: statGame})
                });

                if(!req.ok) throw new Error(`Error! Problem: ${req.status}`);

                //get request
                const res = await req.json(); //get json

                //get block to write msg
                const recordsBlock = document.querySelector('.month-info__records');
                if(!recordsBlock) return;
                const notifBlock = document.querySelector('.sys-msg');
                if(!notifBlock) return;

                if(Array.isArray(res.records) && res.records.length > 0) {
                    recordsBlock.innerHTML = ''; //clean content
                    notifBlock.innerHTML = ''; //clean msg inform block

                    res.records.forEach(rec => {
                        const p = document.createElement('p');
                        p.innerHTML = `${statGame}: ${rec[statGame]} - <span>Source: ${rec.source}</span><br/>`;
                        recordsBlock.appendChild(p);
                    });
                } else {
                    notifBlock.innerHTML = ''; //clean msg inform
                    notifBlock.innerHTML = "<p style='color:red'>No data</p>";
                }
            }
        });
    } catch(err) {
        console.log(err);
    } finally {
        //switch on button
        submitBtnControl.disabled = false;
    }
}