/**
 * Author: Macavity
 * Bugtracker Init file
 */

requirejs.config({
    baseUrl: '/application/js',

    // Disable internal caching of the files (development only)
    //urlArgs: "bust=" + (new Date()).getTime(),
    urlArgs: "rev=617.3",

    paths: {
    }
});

require([
        'static',
        'controller/BugtrackerController'
    ],
    function (static, BugtrackerController) {
        $(function () {
            debug.debug("js/bugtracker");
            var controller = new BugtrackerController();
        });
    }
);