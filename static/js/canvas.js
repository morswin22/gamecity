const updateIntervalValue = 5000;

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

let stats = prepareTools('#stats');
let statsHeader = document.createElement('span');
statsHeader.innerHTML = 'Statistic';
stats.appendChild(statsHeader);
let statsBody = document.createElement('div');
stats.appendChild(statsBody);

let guis = [];
let buildingGUI = wrapper.querySelector('#buildingGUI');
    let buildingGUI_Header = document.createElement('header');
    buildingGUI_Header.innerHTML = ''; // selected building name
    buildingGUI.appendChild(buildingGUI_Header);
    let buildingGUI_Body = document.createElement('section');
        let buildingGUI_Body_Image = document.createElement('img');
        buildingGUI_Body_Image.src = ''; // selected building image src
        buildingGUI_Body_Image.alt = 'error';
        buildingGUI_Body.appendChild(buildingGUI_Body_Image);
        let buildingGUI_Body_Description = document.createElement('p');
        buildingGUI_Body_Description.innerHTML = ''; // selected building description
        buildingGUI_Body.appendChild(buildingGUI_Body_Description);
        let buildingGUI_Body_Level = document.createElement('p');
        buildingGUI_Body_Level.innerHTML = ''; // selected building level
        buildingGUI_Body.appendChild(buildingGUI_Body_Level);
        let buildingGUI_Body_LevelUp = document.createElement('button');
        buildingGUI_Body_LevelUp.classList.add('levelup');
        buildingGUI_Body_LevelUp.innerHTML = 'Level up!';
        buildingGUI_Body_LevelUp.addEventListener('click',()=>{
            // TODO: will fetch to /process-game-action (with params :D)
        });
        buildingGUI_Body.appendChild(buildingGUI_Body_LevelUp);
    buildingGUI.appendChild(buildingGUI_Body);

function showBuildingGUI(data) {
    buildingGUI_Header.innerHTML = data.name || 'name';
    buildingGUI_Body_Image.src = data.src || '/static/img/e404.png';
    buildingGUI_Body_Description.innerHTML = data.description || 'description';
    buildingGUI_Body_Level.innerHTML = `Level: ${data.level || 'level'}`;

    show(buildingGUI);
}

guis.push(buildingGUI);
for(let gui of guis) {
    // add the exit functionality
    gui.classList.add('gui');

}
let GUI_JUST_OPENED = false;
document.addEventListener('click', ()=> {
    // close guis
    if (!GUI_JUST_OPENED) {
        for(let gui of guis) {
            // add the exit functionality
            hide(gui);
        }
    }
    GUI_JUST_OPENED = false;
})

function prepareTools(querySelector) {
    let elemDOM = wrapper.querySelector(querySelector);
    show(elemDOM);
    let elemInner = elemDOM.querySelector('div:nth-of-type(2)');
    return elemInner;
}

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

const Game = {
    setup: true,
    loading: false,
    playing: false,
    assets: {}
}

function draw() {
    background(51,51,51,75);
    imageMode(CENTER);

    if (Game.setup) {
        getAssets();
    } else if (Game.loading) {
        // display loading bar
    } else if (Game.playing) {
        // display the game
        for(let buildingRaw in Game.response.buildings) {
            let building = Game.response.buildings[buildingRaw];
            let img = Game.assets.buildings[`${buildingRaw}-${building.level}`];
            if (img) {
                image(img, building.location[0] || 0, building.location[1] || 0, building.width, building.width*img.height/img.width);
            }
        }
    }
}

async function getAssets() {
    Game.setup = false;
    Game.loading = true;

    let totalAwaiting = 0;
    let totalLoaded = 0;
    
    let assets = await (await fetch('/getAssets')).json();
    for (let assetKey in assets) {
        for(let asset of assets[assetKey]) {
            totalAwaiting++;
            assetName = /(.*)\..*/.exec(asset)[1];
            if (!Game.assets[assetKey]) Game.assets[assetKey] = [];
            Game.assets[assetKey][assetName] = loadImage('/static/img/buildings/'+asset, ()=>{
                totalLoaded++;

                if (totalLoaded == totalAwaiting) {
                    // done loading;
                    Game.loading = false;
                    Game.playing = true;
                }
            });
        }
    }
}

function mousePressed() {
    push()
        stroke(random()*255,random()*255,random()*255);
        strokeWeight(random(1,3)*16);
        point(mouseX, mouseY);
    pop();

    console.log(mouseX, mouseY);

    // check collisions with buildings
    for(let buildingRaw in Game.response.buildings) {
        let building = Game.response.buildings[buildingRaw];
        let img = Game.assets.buildings[`${buildingRaw}-${building.level}`];
        if (img) {
            let rect = [ building.location[0] || 0, building.location[1] || 0, building.width, building.width*img.height/img.width ];
            if (mouseX >= rect[0]-rect[2]/2 && mouseX <= rect[0]+rect[2]/2 &&
                mouseY >= rect[1]-rect[3]/2 && mouseY <= rect[1]+rect[3]/2) {
                console.log(`Clicked on ${building.name}`);
                GUI_JUST_OPENED = true;
                showBuildingGUI({
                    name: building.name,
                    level: building.level,
                    src: `/static/img/buildings/${buildingRaw}-${building.level}.png`
                });
                break;
            }
        }
    }
}

// function mouseDragged() {
//     stroke(random()*255,random()*255,random()*255);
//     strokeWeight(random(1,3)*16);
//     point(mouseX, mouseY);

//     console.log(mouseX, mouseY);
// }

async function processGame() {
    let res = await(await fetch('/process-game')).json();
    if (res['needsSetup'] == true) {
        window.top.location.href = '/setup-game';
    } else {
        Game.lastResponse = Game.response || false;
        Game.response = res;

        statsBody.innerHTML = `Max CS size: ${res.buildings['community storage'].size}`;
    }
}
processGame();
setInterval(processGame, updateIntervalValue);