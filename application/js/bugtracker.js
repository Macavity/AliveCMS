/**
 * Author: Macavity
 * Bugtracker Init file
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
        'wiki': 'modules/wiki',
        'wiki_directory': 'modules/wiki_directory',
        'wiki_related': 'modules/wiki_related',
        'wow': 'modules/wow',
        'zone': 'modules/zone'
    }
});

require([
    'static',
    'controller/BugtrackerController',
    'wiki',
    'tooltip'
],
    function (static, BugtrackerController, Wiki, Tooltip) {

        $(function () {
            debug.debug("js/bugtracker");

            var controller = new BugtrackerController();

        });
    });