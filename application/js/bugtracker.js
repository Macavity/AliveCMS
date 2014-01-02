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
                var controller = new BugtrackerController();
            });
        }
    );

});