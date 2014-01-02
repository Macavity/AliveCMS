/**
 * Author: Macavity
 * News Init file
 */

require(['static'], function(){
    require([
            'controller/AdminController',
            'tooltip'
        ],
        function (AdminController, Tooltip) {
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
});

