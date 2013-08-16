/**
 * Author: Macavity
 * Bugtracker Init file
 */

require(['static'], function(){

    require([
            'controller/BugtrackerController'
        ],
        function (BugtrackerController) {
            $(function () {
                debug.debug("js/bugtracker");
                var controller = new BugtrackerController();
            });
        }
    );

});