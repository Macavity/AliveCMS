
define(['./BaseController', 'modules/slideshow'], function (BaseController, Slideshow) {

    var NewsController = BaseController.extend({
        init: function(){
            this._super();

            debug.debug("NewsController.initialize");
            this.initSlideshow();
            debug.debug("NewsController.initialize -- DONE");
        },

        initSlideshow: function(){
            if($("#slideshow").length){
                debug.debug("NewsController.initSlideshow");

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
        }
    });

    return NewsController;
});
