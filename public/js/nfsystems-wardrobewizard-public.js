var slideIndex = 1;
function plusDivs(n) {
    showDivs(slideIndex += n);
}

function showDivs(n) {
    var i;
    var x = document.getElementsByClassName("mySlidesjs");
    if (n > x.length) {slideIndex = 1}
    if (n < 1) {slideIndex = x.length}
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    x[slideIndex-1].style.display = "block";
}

var slideIndexTwo = 1;
function plusDivsTwo(n) {
    showDivsTwo(slideIndex += n);
}

function showDivsTwo(n) {
    var i;
    var x = document.getElementsByClassName("mySlidesTwo");
    if (n > x.length) {slideIndex = 1}
    if (n < 1) {slideIndex = x.length}
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    x[slideIndex-1].style.display = "block";
}

jQuery(document).ready(function () {


showDivs(slideIndex);
showDivsTwo(slideIndexTwo);
});

	jQuery(document).ready(function(){
        var text = jQuery(".text");
        text.removeClass("hidden");
        jQuery(window).scroll(function() {
            var scroll = jQuery(window).scrollTop();
            if (scroll >= 200){
                text.removeClass("hidden");
            } else {
                text.addClass("hidden");
            }



        });



    });
