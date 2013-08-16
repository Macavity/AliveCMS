/**
 * Author: Macavity
 * News Init file
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
    'controller/MigrationController',
    'tooltip'
],
    function (static, MigrationController, Tooltip) {

        $(function () {
            debug.debug("js/migration");

            var controller = new MigrationController();

        });
    });