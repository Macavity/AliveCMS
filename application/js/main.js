/**
 * Author: Macavity
 * Main JS Init file
 */

require(['static'], function(){
    require([
        'controller/PageController'
    ],
        function (PageController) {

            $(function () {
                debug.debug("js/main");
                var controller = new PageController();
            });
        }
    );
});
