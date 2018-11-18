let topDocument = window.top.document;
let wrapper = topDocument.querySelector('.wrapper');

let menu = wrapper.querySelector('.menu');
let i = 0;
for (let li of menu.querySelectorAll('li')) {
    let handler;
    switch(i) {
        case 0: 
            handler = () => {
                // settings
            }
            break;
        case 1: 
            handler = () => {
                // about
            }
            break;
        case 2: 
            handler = () => {
                window.top.location = '/logout';
            }
            break;
    }
    li.addEventListener('click', handler);
    i++;
}

function setup() {
    createCanvas(windowWidth, windowHeight);
}

function windowResized() {
    resizeCanvas(windowWidth, windowHeight);
}

function draw() {
    background(51);
}