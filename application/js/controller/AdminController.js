
define(['./BaseController','modules/wiki','modules/wiki_related'], function (BaseController, Wiki, WikiRelated) {


    var AdminController = BaseController.extend({
        init: function(){
            this._super();
            debug.debug("AdminController.initialize");
        },

        initWiki: function(wrapperId, options){
            debug.debug("AdminController.initWiki");
            Wiki.related[wrapperId] = new WikiRelated(wrapperId, options);
        }

    });

    return AdminController;
});
