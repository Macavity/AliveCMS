
define(['./BaseController'], function (BaseController) {


    var PageController = BaseController.extend({
        init: function(){
            this._super();
            debug.debug("PageController.initialize");
        },

        initWiki: function(){

        },

        getTemplate: function(templateName){
            return Handlebars.templates[templateName];
        }

    });

    return PageController;
});
