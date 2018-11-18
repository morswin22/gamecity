let movable = [];

movable.push(document.querySelector('#stats'));

function firstToUpper(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

let movableData = sessionStorage.getItem('gamecity--movable-data') || '{}';
movableData = JSON.parse(movableData);
let id =0;
for(let item of movable) {
    dragElement(item, id);
    if (movableData[id]) {
        item.style.top = constrain(movableData[id][0], 'height', item) + 'px';
        item.style.left = constrain(movableData[id][1], 'width', item) + 'px';
    }
    id++;
}

function constrain(value, type, elem) {
    let min = 0;
    let max = (type=='width') ? window.innerWidth : window.innerHeight;
    if (value + elem['offset'+firstToUpper(type)] / 2 > max) value = max - elem['offset'+firstToUpper(type)] / 2;
    if (value - elem['offset'+firstToUpper(type)] / 2 < min) value = elem['offset'+firstToUpper(type)] / 2;
    return value;
}

function dragElement(elmnt, id) {
  var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
  if (document.getElementById(elmnt.id + "header")) {
    // if present, the header is where you move the DIV from:
    document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
  } else {
    // otherwise, move the DIV from anywhere inside the DIV: 
    elmnt.onmousedown = dragMouseDown;
  }

  function dragMouseDown(e) {
    e = e || window.event;
    e.preventDefault();
    // get the mouse cursor position at startup:
    pos3 = e.clientX;
    pos4 = e.clientY;
    document.onmouseup = closeDragElement;
    // call a function whenever the cursor moves:
    document.onmousemove = elementDrag;
  }

  function elementDrag(e) {
    e = e || window.event;
    e.preventDefault();
    // calculate the new cursor position:
    pos1 = pos3 - e.clientX;
    pos2 = pos4 - e.clientY;
    pos3 = e.clientX;
    pos4 = e.clientY;
    // set the element's new position:
    let newTop = constrain((elmnt.offsetTop - pos2), 'height', elmnt);
    let newLeft = constrain((elmnt.offsetLeft - pos1), 'width', elmnt);
    
    let movableData = sessionStorage.getItem('gamecity--movable-data') || '{}';
    movableData = JSON.parse(movableData);
    movableData[id] = [newTop, newLeft];
    sessionStorage.setItem('gamecity--movable-data', JSON.stringify(movableData));

    elmnt.style.top = newTop + "px";
    elmnt.style.left = newLeft + "px";
  }

  function closeDragElement() {
    // stop moving when mouse button is released:
    document.onmouseup = null;
    document.onmousemove = null;
  }
}