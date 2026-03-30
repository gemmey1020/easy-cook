window.addEventListener("load", function(){
    var load_screen = document.getElementById("load_screen");

    if (load_screen && load_screen.parentNode === document.body) {
        document.body.removeChild(load_screen);
    }
});
