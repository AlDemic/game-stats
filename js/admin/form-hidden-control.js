//js logic to hide and show form block

//take var for delete forms block
const recDelBlock = document.querySelector('.records-del');

//event listener
if(recDelBlock) {
    recDelBlock.addEventListener('click', (e) => {

        if(!e.target.dataset.mode) return;

        e.preventDefault();

        //get all forms
        const forms = document.querySelectorAll('.del-form');

        //add hidden class for each one
        forms.forEach(form => {
            form.classList.add('hidden');
        });

        //remove hidden from selected by btn
        const selectedForm = document.getElementById(`del-${e.target.dataset.mode}`);
        selectedForm.classList.remove('hidden');
    });
}


//take var for parser selection forms block
const parserFormBlock = document.querySelector('.parser');

//event listener
if(parserFormBlock) {
    parserFormBlock.addEventListener('click', (e) => {

        if(!e.target.dataset.mode) return;

        e.preventDefault();

        //get all forms
        const forms = document.querySelectorAll('.parser-form');

        //add hidden class for each one
        forms.forEach(form => {
            form.classList.add('hidden');
        });

        //remove hidden from selected by btn
        const selectedForm = document.getElementById(`parser-${e.target.dataset.mode}`);
        selectedForm.classList.remove('hidden');
    });
}