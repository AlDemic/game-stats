//js logic to delete records from DB 

if(recDelBlock) {
    recDelBlock.addEventListener('submit', async (e) => {
        //switch off submit btn
        const submitBtnControl = document.querySelector('[type="submit"]');
        submitBtnControl.disabled = true;

        try {
                if(!e.target.id) return;

                e.preventDefault();

                //get form
                const form = new FormData(e.target);
                if(!form) return;

                //get stat from url
                const statUrl = new URLSearchParams(window.location.search).get('stat') ?? '';
                if(statUrl === '') return;

                //add to form the url STAT
                form.append('stat', statUrl);

                //send form to server
                const req = await fetch(`${apiPath}admin/del-records.php`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: form
                });

                if(!req.ok) throw new Error(`Error! Problem: ${req.status}`);

                //get json
                const res = await req.json();

                //notification block for user
                const notifBlock = document.querySelector('.sys-msg');
                notifBlock.innerHTML = ''; //clear content

                //msg for user
                if(res.status === 'ok') {
                    e.target.reset(); //reset form

                    notifBlock.innerHTML = `<p style="color:green">${res.msg}</p>`;
                } else {
                    notifBlock.innerHTML = `<p style="color:red">${res.msg}</p>`;
                }
    
        } catch(err) {
            console.log(err);
        } finally {
            //switch on button
            submitBtnControl.disabled = false;
        }
    });
}