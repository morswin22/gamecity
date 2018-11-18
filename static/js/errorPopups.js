(()=>{
    let errorList = document.querySelector('.error-list');
    
    let success = errorList.querySelectorAll('.success');
    let i = 0;
    for (let item of success) {
        setTimeout(()=>{
            item.classList.add('fade');
            setTimeout(()=>{
                item.remove();
            }, 400);
        }, 2000 + i*400);
        i++;
    }

    for (let item of errorList.querySelectorAll('li i')) {
        item.addEventListener('click', ()=>{
            item.parentElement.classList.add('fade');
            setTimeout(()=>{
                item.parentElement.remove();
            }, 400);
        });
    }
    
})();