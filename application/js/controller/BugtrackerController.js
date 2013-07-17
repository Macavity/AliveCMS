
define(['./BaseController', 'wiki', 'wiki_related'], function (BaseController, Wiki, WikiRelated) {

    var BugtrackerController = BaseController.extend({
        init: function(){
            this._super();

            debug.debug("BugtrackerController.initialize");

            this.initTables();

            debug.debug("BugtrackerController.initialize -- DONE");

        },

        initTables: function(){
            if($("#buglist")){

                var buglist = $("#buglist");

                var bugTable = Wiki;

                bugTable.pageUrl = '/bugtracker/';
                bugTable.related.loot = new WikiRelated('loot', {
                    paging: true,
                    totalResults: buglist.data("rowcount"),
                    column: 4,
                    method: 'date',
                    type: 'desc'
                });

            }

        }
    });

    return BugtrackerController;
});
