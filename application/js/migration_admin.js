/**
 * Author: Macavity
 * News Init file
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
    'controller/AdminController',
    'tooltip'
],
    function (static, AdminController, Tooltip) {

        $(function () {
            debug.debug("js/migration_admin");

            var controller = new AdminController();

            var totalResults = $("#totalResults").val();

            controller.initWiki('loot', {
                paging: true,
                totalResults: totalResults,
                column: 1,
                method: 'default',
                type: 'desc'
            });

        });
    });