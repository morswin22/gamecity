let addForm = document.createElement('form');
addForm.method = 'POST';
addForm.action = '/newSave';

let addName = document.createElement('input');
addName.placeholder = 'New save';
addName.name = 'name';
addName.autocomplete = 'off';

let addI = document.createElement('i');
addI.classList.add("material-icons");
addI.innerHTML = 'add_circle_outline';
addI.addEventListener('click', ()=>{
    addForm.submit();
});

let addSubmit = document.createElement('input');
addSubmit.hidden = 'hidden';
addSubmit.type = 'submit';

addForm.appendChild(addName);
addForm.appendChild(addI);
addForm.appendChild(addSubmit);
document.querySelector('.wrapper').appendChild(addForm);

let savesList = document.createElement('ul');
document.querySelector('.wrapper').appendChild(savesList);

async function getSaves() {
    let savesData = await (await fetch('/getSaves')).json();
    for(let save of savesData) {
        let li = document.createElement('li');
        li.innerHTML = save['name'];

        let i = document.createElement('i');
        i.innerHTML = 'play_arrow';
        i.classList.add('material-icons');
        i.setAttribute('data-save-id', save['id']);
        i.addEventListener('click', elt=>{
            location.href='/loadSave/'+elt.target.getAttribute('data-save-id');
        });

        li.appendChild(i);

        savesList.appendChild(li);
    }
}

getSaves();

let logout = document.querySelector('#logout');
logout.addEventListener('click', ()=>{
    location.href='/logout';
});