//js logic of adding new game in db

const addGameBtn = document.getElementById('add-game');
const apiPath = window.location.origin + '/api/'; //base api path

if(addGameBtn) {
    addGameBtn.addEventListener("submit", async (e) => {
        e.preventDefault(); //stop refresh

        //switch off submit btn
        const submitBtnControl = document.querySelector('[type="submit"]');
        submitBtnControl.disabled = true;

        try {
            //get data from form
            const form = e.target;
            const formData = new FormData(form);

            //make request to server
            const req = await fetch(`${apiPath}admin/add-game.php`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });
   
            if(!req.ok) throw new Error(`Error! Problem: ${req.status}`);

            //if okey
            const res = await req.json();

            //notification block for user
            const notifBlock = document.querySelector('.sys-msg');
            notifBlock.innerHTML = ''; //clear content

            //msg for user
            if(res.status === 'ok') {
                addGameBtn.reset(); //reset form

                notifBlock.innerHTML = `<p style="color:green">${res.msg}</p>`;
            } else {
                notifBlock.innerHTML = `<p style="color:red">${res.msg}</p>`;
            }
        } catch (err) {
            console.log(err);
        } finally {
            //switch on button
            submitBtnControl.disabled = false;
        }
    });

}