/**
 * Author: Macavity
 * Extern JS Init file
 */

require(['./static'], function(){

    requirejs.config({
        baseUrl: 'http://www.senzaii.net/application/js',

        // Disable internal caching of the files (development only)
        //urlArgs: "bust=" + (new Date()).getTime(),
        urlArgs: "rev=617.4",

        paths: {
        }
    });

    require([
        'controller/PageController'
    ],
        function (PageController) {

            $(function () {
                debug.debug("js/extern");
                var controller = new PageController();

                if(typeof vb_user_id != "undefined" && vb_user_id > 0){
                    controller.loadUserplate(vb_user_id);
                }
            });
        }
    );
});
