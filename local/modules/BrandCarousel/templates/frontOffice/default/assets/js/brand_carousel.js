/*********************************************************/
/*                                                       */
/*          Javascript for Brand carousel module         */
/*          Developed by Devoption                       */
/*                                                       */
/*********************************************************/

var brands = [];
var className = "brand_carousel_element";
var nbBrand = $("." + className).length;
var lastBrandIndex = nbBrand - 1;
var firstBrandIndex = 0;
var firstVisibleBrandIndex;
var lastVisibleBrandIndex;
var interval;
var animationSpeed = 1;
var animationFinished = true;


window.onload = function () {
    $(document).ready(function () {
       if (nbBrand > 4) {
            setBrands();    
            animateBrandsByInterval("Right");
         }
    });
};



$("#arrowRight").click(function () {
    if (animationFinished) {
        clearInterval(interval);
        animateBrands("Right");
        animateBrandsByInterval("Right");
    }
});

$("#arrowLeft").click(function () {
    if (animationFinished) {
        clearInterval(interval);
        animateBrands("Left");
        animateBrandsByInterval("Right");
    }
});


function animateBrandsByInterval(direction) {
    interval = setInterval(function () {
        animateBrands(direction);
    }, 3000);
}


function setBrands() {
    var i = 0;
    var brandElement;
    while (i < nbBrand) {
        brandElement = brandSelector(i);
        brandElement.css("visibility", "visible");
        brands.push({
            index: brandElement.index(),
            sliding: 0});
        i++;
    }
}

function animateBrands(direction) {
    animationFinished = false;
    if (direction === "Right") {
        moveLastOrFirstBrand(direction);
        for (var i = 0; i < brands.length; i++) {
            moveBrand(i, direction);
        }
        if (lastBrandIndex === nbBrand - 1) {
            initleftBrands();
        }
    } else if (direction === "Left"){
        for (var i = 0; i < brands.length; i++) {
            moveBrand(i, direction);
        }
        setTimeout(function () {
            moveLastOrFirstBrand(direction);
            if (firstBrandIndex === 0) {
                initleftBrands();
            }
        }, 1000 * animationSpeed);

    }
}

function initleftBrands() {
    for (var i = 0; i < brands.length; i++) {
        brands[i].sliding = 0;
        brandSelector(brands[i].index)
                .css({left: '0px'});
    }
}

function moveBrand(index, direction) {
    var brand_model = brands[index];
    var brand_view = brandSelector(brand_model.index);
    if (direction === "Right") {
        brand_model.sliding = brand_model.sliding + brand_view.outerWidth();
    } else if (direction === "Left"){
        brand_model.sliding = brand_model.sliding - brand_view.outerWidth();
    }
    brand_view
            .css("visibility", "visible")
            .css("transition-duration", animationSpeed + 's')
            .css({left: brand_model.sliding + 'px'});
    brands[index] = brand_model;
}

function moveLastOrFirstBrand(direction) {
    if (direction === "Right") {
        moveLastToFirst(lastBrandIndex);
        firstBrandIndex = lastBrandIndex;
        lastBrandIndex = lastBrandIndex - 1;
        if (lastBrandIndex < 0) {
            lastBrandIndex = nbBrand - 1;
        }
        //console.log(' Right lastBrandIndex :' + lastBrandIndex);
        //console.log(' Right firstBrandIndex :' + firstBrandIndex);
    } else if (direction === "Left"){
        moveFirstToLast(firstBrandIndex);
        lastBrandIndex = firstBrandIndex;
        firstBrandIndex = firstBrandIndex + 1;
        if (firstBrandIndex > nbBrand - 1) {
            firstBrandIndex = 0;
        }
        //console.log(' left firstBrandIndex :' + firstBrandIndex);
        //console.log(' left lastBrandIndex :' + lastBrandIndex);
    }
}

function moveLastToFirst(index) {
    var brandModel = brands[index];
    var brandView = $("." + className + ":eq(" + brandModel.index + ")");
    var left = brandView.offset().left;
    var leftBrandBanner = $(".brand_carousel:eq(0)").offset().left;
    var widthBrand = brandView.innerWidth();
    var nb = (nbBrand - 1) - index;
    left = left - widthBrand * nb;
    //console.log('left :' + left);
    brandModel.sliding = -(left - leftBrandBanner + widthBrand);
    brandView
            .css("transition-duration", '0s')
            .css("left", brandModel.sliding + 'px')
            .css("visibility", "hidden");
    //console.log('brandModel.sliding :' + brandModel.sliding);
    brands[index] = brandModel;
}

function moveFirstToLast(index) {
    var brandModel = brands[index];
    var brandView = $("." + className + ":eq(" + brandModel.index + ")");
    var left = brandView.offset().left;
    var leftLastBrand = brandSelector(lastBrandIndex).offset().left;
    var widthBrand = brandView.innerWidth();
    left = left + widthBrand * index;
    //console.log('left :' + left);
    brandModel.sliding = (leftLastBrand - left);
    brandView
            .css("transition-duration", '0s')
            .css("left", brandModel.sliding + 'px')
            .css("visibility", "hidden");
    //console.log('brandModel.sliding :' + brandModel.sliding);
    brands[index] = brandModel;
}

function brandSelector(index) {
    return $("." + className + ":eq(" + index + ")");
}

$("." + className).on('transitionend webkitTransitionEnd', function () {
    animationFinished = true;
});





