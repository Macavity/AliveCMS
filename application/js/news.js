/**
 * Author: Macavity
 * News Init file
 */

require(['static'], function(){
    require([
            'controller/NewsController',
            'tooltip'
        ],
        function (NewsController, Tooltip) {
            $(function () {
                debug.debug("js/news");

                var controller = new NewsController();

            });
        }
    );
});

