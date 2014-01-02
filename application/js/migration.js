/**
 * Author: Macavity
 * News Init file
 */

require(['status'], function(){
    require([
            'controller/MigrationController',
            'tooltip'
        ],
        function (MigrationController, Tooltip) {
            $(function () {
                var controller = new MigrationController();
            });
        }
    );
});

