
// The build will inline common dependencies into this file.
// For any third party dependencies, like jQuery, place them in the lib folder.
// Configure loading modules from the lib directory,
// except for 'app' ones, which are in a sibling
// directory.
requirejs.config({
    baseUrl: '/application/js/libs',

    // Disable internal caching of the files (development only)
    //urlArgs: "bust=" + (new Date()).getTime(),
    urlArgs: "rev=617.1",

    paths: {
        "jquery": "jquery/jquery-min",
        "debug": "debug/javascript-debug",
        "modernizr": "modernizr/modernizr-min"
    }
});

var mapStatic = {

    urls: {
    },

    /**
     * List of initialization class pairings.
     * The first class is always the class a object needs to have, the second class is added when the binding is complete.
     * In further stages the DOM element can be cleansed of both classes after the binding for optimization,
     * the current way makes the reading of the DOM easier.
     */
    initClasses: {
    },

    selectors: {
    },

    /**
     * Will be filled by scripts for further use of generic values
     */
    "precompiled": {}
};

