let topDocument = window.top.document;
let wrapper = topDocument.querySelector('.wrapper');

let menuDOM = wrapper.querySelector('.menu');
let i = 0;
for (let li of menuDOM.querySelectorAll('li')) {
    let handler;
    switch(i) {
        case 0: 
            handler = () => {
                // settings
            }
            break;
        case 1: 
            handler = () => {
                window.top.location = '/saves';
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

let statsDOM = wrapper.querySelector('#stats');
show(statsDOM);
statsDOM.innerHTML = 'Statistics';


function show(elem) {
    elem.style.display = 'initial';
}
function hide(elem) {
    elem.style.display = 'none';
}

function setup() {
    createCanvas(windowWidth, windowHeight);
}

function windowResized() {
    resizeCanvas(windowWidth, windowHeight);
}

function draw() {
    background(51,51,51,75);
}

function mouseDragged() {
    stroke(random()*255,random()*255,random()*255);
    strokeWeight(random(1,3)*16);
    point(mouseX, mouseY);
}