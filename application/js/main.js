/**
 * Author: Macavity
 * Main JS Init file
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
        'controller/PageController'
    ],
    function (static, PageController) {

        $(function () {
            debug.debug("js/main");
            var controller = new PageController();
        });
    }
);