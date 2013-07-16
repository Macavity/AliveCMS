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
        'app' : 'modules/app',
        'blackout' : 'modules/blackout',
        'char_select' : 'modules/char_select',
        'cookie': 'modules/cookie',
        'core' : 'modules/core',
        'input': 'modules/input',
        'login' : 'modules/login',
        'slideshow' : 'modules/slideshow',
        'toggle': 'modules/toggle',
        'tooltip': 'modules/tooltip',
        'wow': 'modules/wow',
        'zone': 'modules/zone'
    }
});

require([
        'static',
        'controller/NewsController',
        'tooltip'
    ],
    function (static, NewsController, Tooltip) {

    $(function () {
        debug.debug("js/news");

        var controller = new NewsController();

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