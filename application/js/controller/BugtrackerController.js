
define(['./BaseController', 'modules/wiki', 'modules/wiki_related'], function (BaseController, Wiki, WikiRelated) {

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

                bugTable.pageUrl = '/bugtracker/buglist/';
                bugTable.related.buglist = new WikiRelated('buglist', {
                    paging: true,
                    totalResults: buglist.data("rowcount"),
                    column: 5,
                    method: 'date',
                    type: 'desc'
                }, bugTable);

                var activeTab = $(".filter-tabs .tab-active");
                if(activeTab.length){
                    activeTab.click();
                }
            }

        }
    });

    return BugtrackerController;
});
