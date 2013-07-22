/**
 * A slideshow system. Will display a single slide (with a description, title and image) at a time,
 * and will fade between the slides based on a duration. You can manually jump to specific slides.
 */
/*jshint -W083 */

define(['modules/core','modules/cookie'], function (Core, Cookie) {
    var Slideshow = {

        /**
         * The slide containing element.
         */
        object: null,

        /**
         * The rotation timer.
         */
        timer: null,

        /**
         * Current index.
         */
        index: 0,

        /**
         * Array of slide data.
         */
        data: [],

        /**
         * A collection of the slide DOM objects.
         */
        slides: [],

        /**
         * Is rotation currently playing.
         */
        playing: false,

        /**
         * The last slide index.
         */
        lastSlide: null,

        /**
         * Initialize the slider by building the slides based on this.data and starting the rotation.
         *
         * @param string object - CSS expression
         * @param array data
         * @constructor
         */
        initialize: function(object, data) {
            Slideshow.object = $(object);
            Slideshow.data = data;

            Slideshow.slides = Slideshow.object.find('.slide');

            // Apply events
            Slideshow.object.find('.mask').hover(
                function() { Slideshow.pause(); },
                function() { Slideshow.play(); }
            );



            Slideshow.object.find('.paging a').mouseleave(function() {
                Slideshow.object.find('.preview').empty().hide();
            });

            // Generate paging
            for(var i = 0; i < Slideshow.data.length; i++){
                var slideIndex = $(Slideshow.slides[i]).data("index");
                var pager = Slideshow.object.find("#paging-"+slideIndex);

                $(pager)
                    .on("mouseover", function(el){
                        var object = $(this);
                        Slideshow.preview(object.data("index"));
                    })
                    .on("click", function(){
                        var object = $(this);
                        Slideshow.jump(object.data("index"), this);
                    });
            }

            // Save views
            if (Slideshow.data.length > 0 && Slideshow.data[0].id) {
                var firstId = Slideshow.data[0].id;
                var cookie = Cookie.read('slideViewed');

                if (!cookie)
                    cookie = [];
                else
                    cookie = decodeURIComponent(cookie).split(',');

                if ($.inArray(firstId.toString(), cookie) < 0)
                    cookie.push(firstId);

                if (cookie.length > 100)
                    cookie.shift();

                Cookie.create('slideViewed', cookie.join(','), {
                    escape: true,
                    expires: 744 // 1 month
                });
            }

            if (Slideshow.slides.length <= 1)
                Slideshow.object.find('.controls, .paging').hide();

            Slideshow.link(0);
            Slideshow.play();

            window.Slideshow = Slideshow;
        },

        /**
         * Fade out the slides and fade in selected.
         *
         * @param int index
         */
        fade: function(index) {
            Slideshow.slides.stop(true, true).fadeOut('normal');
            Slideshow.slides.eq(index).fadeIn(1500);
            Slideshow.link(index);

            var caption = Slideshow.object.find('.caption');

            caption.stop(true, true).fadeOut('fast', function() {
                if (Slideshow.data[index]) {
                    caption.html("")
                        .append('<h3><a href="'+Slideshow.data[index].url+'">'+ Slideshow.data[index].title +'</a></h3>')
                        .append(Slideshow.data[index].desc)
                        .fadeIn(1500);
                }
            });

            Slideshow.lastSlide = index;
        },

        /**
         * Manually jump to a specific slide. Pauses rotation.
         *
         * @param int index
         * @param object control
         */
        jump: function(index, control) {
            if ((Slideshow.lastSlide == index) || (Slideshow.slides.length <= 1))
                return;

            Slideshow.pause();
            Slideshow.fade(index);
            Slideshow.index = index;

            Slideshow.object.find('.paging a').removeClass('current');
            $(control).addClass('current');
        },

        /**
         * Link the mask overlay and track the event.
         *
         * @param int index
         */
        link: function(index) {
            if (Slideshow.data[index]) {
                Slideshow.object.find('.mask')
                    .unbind('click.slideshow')
                    .bind('click.slideshow', function() {
                        Core.goTo(Slideshow.data[index].url);
                    });
            }
        },

        /**
         * Play the rotation.
         */
        play: function() {
            if (Slideshow.slides.length <= 1)
                return;

            if (!Slideshow.playing) {
                Slideshow.playing = true;
                Slideshow.timer = window.setInterval(Slideshow.rotate, 5000);
            }
        },

        /**
         * Pause the automatic rotation.
         */
        pause: function() {
            if (Slideshow.slides.length <= 1)
                return;

            window.clearInterval(Slideshow.timer);

            Slideshow.playing = false;
        },

        /**
         * Display a tooltip preview.
         */
        preview: function(index) {
            if (Slideshow.data[index]) {
                var tooltip = Slideshow.object.find('.preview');
                var top = (index * 15) + 15;

                if (Slideshow.data[index].image) {
                    $('<img/>', {
                        src: Slideshow.data[index].image,
                        width: 100,
                        height: 47,
                        alt: ''
                    }).appendTo(tooltip);
                }

                tooltip.append('<span>'+ Slideshow.data[index].title +'</span>').css('top', top);
                tooltip.show();
            }
        },

        /**
         * Automatically cycle through all the slides.
         */
        rotate: function() {
            var slideIndex = Slideshow.index + 1;

            if (slideIndex > (Slideshow.slides.length - 1))
                slideIndex = 0;

            if (Slideshow.lastSlide == slideIndex)
                return;

            Slideshow.fade(slideIndex);
            Slideshow.index = slideIndex;

            // Set control to current
            Slideshow.object
                .find('.paging a').removeClass('current').end()
                .find('.paging a:eq('+ slideIndex +')').addClass('current');
        },

        /**
         * Toggle between play and pause.
         */
        toggle: function() {
            if (Slideshow.playing)
                Slideshow.pause();
            else
                Slideshow.play();
        }

    };

    return Slideshow;

});