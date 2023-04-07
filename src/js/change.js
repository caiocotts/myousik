let image = document.getElementById('change');
let images = ['images/one.png','images/two.png','images/three.png','images/four.png'];

setInterval(function(){
    let random = Math.floor(Math.random()*3);
    change.src= images[random];
}, 5000);