
define(['./BaseController'], function (BaseController) {

    function pageController(id){
        debug.debug("new PageController");
        BaseController.call(this, id);
        return (this);
    }

    pageController.prototype = new BaseController("Frontpage");

    // Define the class methods.
    pageController.init = function(Slideshow){
        debug.debug("Frontpage.initialize");

        debug.debug("Frontpage.initialize END");
    };

    pageController.initSlideshow = function(){
        if($("#slideshow").length){
            debug.debug("Frontpage.initSlideshow");
            var Slideshow = require('libs/alive/slideshow');

            var slideData = [];

            var slides = $("#slideshow .slide");
            var slideCount = slides.length;

            for(var n = 0; n < slideCount; n++){
                var slide = $(slides[n]);

                slideData.push({
                    image: slide.data("image"),
                    desc: slide.data("desc"),
                    title: slide.data("title"),
                    url: slide.data("url"),
                    id: slide.attr("id")
                });
            }

            Slideshow.initialize("#slideshow", slideData);
        }
    };

    return (pageController);
});
