
define(['./BaseController'], function (BaseController) {


    var PageController = BaseController.extend({
        init: function(){
            this._super();
            debug.debug("PageController.initialize");
        },

        getTemplate: function(templateName){
            return Handlebars.templates[templateName];
        }

    });

    return PageController;
});
