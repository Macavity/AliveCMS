/**
 * Author: Macavity
 * News Init file
 */

requirejs.config({
    baseUrl: '/application/js',

    // Disable internal caching of the files (development only)
    //urlArgs: "bust=" + (new Date()).getTime(),
    urlArgs: "rev=617.1",

    paths: {
    }
});

require([
        'static',
        'controller/NewsController',
        'libs/alive/core',
        'libs/alive/slideshow',
        'libs/alive/login'
    ],
    function (static, controller, Core) {

    $(function () {

        debug.debug("asdas");
        controller.init();

        controller.initSlideshow();

        /*
         Core.staticUrl = 'http://forum.wow-alive.de/static-wow';
         Core.baseUrl = 'http://cms.wow-alive.de';
         Core.cdnUrl = 'http://cms.wow-alive.de';
         Core.project = 'wow';
         Core.locale = 'de-de';
         Core.buildRegion = 'eu';
         Core.loggedIn = false;
         */

    });
});