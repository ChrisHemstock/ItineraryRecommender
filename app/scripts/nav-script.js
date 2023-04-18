document.querySelector('.menu-toggle').addEventListener('click', function(){
    let present = document.querySelector(".nav").classList.toggle("mobile-nav");
    if(present) {
        document.getElementById("map").style.zIndex = -1;
    } else {
        document.getElementById("map").style.zIndex = 0;
    }
    document.querySelector('.menu-toggle').classList.toggle("is-active");
 })