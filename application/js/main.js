/**
 * Author: Macavity
 * Main JS Init file
 */

requirejs.config({
    baseUrl: '/application/js',

    // Disable internal caching of the files (development only)
    //urlArgs: "bust=" + (new Date()).getTime(),
    urlArgs: "rev=617.2",

    paths: {
    }
});

require([
        'static',
        'controller/PageController'
    ], function (static, PageController) {

    $(function () {
        debug.debug("js/main");
        var controller = new PageController();
        //UserAgent.initialize();

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