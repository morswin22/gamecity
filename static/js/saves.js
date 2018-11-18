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
})