let cstSliders = document.querySelectorAll('#basevillagersSliders figcaption div:nth-of-type(2)');

for(let slider of cstSliders) {
    let span = slider.querySelector('span');
    let input = slider.querySelector('input[type=range]');
    span.innerHTML = input.value;
    input.addEventListener('input', ()=>{
        span.innerHTML = input.value;
    });
}

let menu = document.querySelectorAll('#menu li');
let i = 0;
for (let li of menu) {
    console.log(li);
    li.addEventListener('click', (i==1) ? () => {
        location.href = '/logout';
    } : () => {
        location.href = '/saves';
    } );
    i++;
}