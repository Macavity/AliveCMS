/*jshint -W041*/
define(['./BaseController', 'modules/wiki', 'modules/wiki_related'], function (BaseController, Wiki, WikiRelated) {

    var BugtrackerController = BaseController.extend({

        realmId: 1,

        openwowPrefix: 'http://wotlk.openwow.com',


        init: function(){
            this._super();

            debug.debug("BugtrackerController.initialize");

            this.initTables();
            this.initCreateForm();

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
        },

        initCreateForm: function(){

            var _jq = $;
            var Controller = this;

            if(_jq("#bugtrackerCreateForm")){
                _jq('[name="type"], #form-link').change(function(){
                    _jq("#search-quest-wrapper, #search-npc-wrapper, #search-zone-wrapper").hide();

                    var wowId = $("#form-id").val();

                    if(this.value == 1){
                        _jq("#search-quest-wrapper").show();
                        _jq("#form-link").val("http://wotlk.openwow.com/quest="+wowId);
                    }
                    if(this.value == 2){
                        _jq("#search-npc-wrapper").show();
                        _jq("#form-link").val("http://wotlk.openwow.com/npc="+wowId);
                    }
                    if(this.value == 3){
                        _jq("#search-zone-wrapper").show();
                        _jq("#form-link").val("http://wotlk.openwow.com/zone="+wowId);
                    }
                });

                /**
                 * Autocomplete for Quests
                 */
                _jq("#search-quest").autocomplete({
                    minLength: 3,
                    messages: {
                        noResults: '',
                        results: function() {}
                    },
                    source: function(request,response){
                        var term = request.term;

                        Controller.setRealmByCategory();

                        _jq.ajax({
                                url: Config.URL + 'ajax/search/quest/'+Controller.realmId+'/'+term,
                                success: function(data){
                                    response(data.results);
                                },
                                dataType: 'json'
                        });

                    },
                    select: function(event, ui) {
                        var item = ui.item;

                        var wowId = item.value;
                        var wowLabel = item.label;

                        _jq("#form-link").val(Controller.openwowPrefix+"quest="+wowId);
                        _jq("#form-id").val(wowId);
                        _jq("#form-title").val(wowLabel);

                        // Search for Reports about the same Quest
                        Controller.getSimilarBugs("quest",wowId);
                    },
                    change: function(event, ui) {
                        _jq("#search-quest").val("");
                    }
                });

                /**
                 * Autocomplete for Zones
                 */
                _jq("#search-zone").autocomplete({
                    minLength: 3,
                    messages: {
                        noResults: '',
                        results: function() {}
                    },
                    source: Config.URL+'ajax/search/zone/',
                    select: function(event, ui) {
                        var item = ui.item;
                        _jq("#form-id").val(item.value);
                        if(_jq("#form-title").val().length == 0)
                            _jq("#form-title").val(item.label);

                        _jq("#form-link").val(Controller.openwowPrefix+"zone="+item.value);
                        getBugs($("#form-link").val());
                    },
                    change: function(event, ui) {
                        _jq("#zone-name").val("");
                    }
                });
                _jq("#zone-name").blur(function(event, ui) {
                    _jq("#zone-name").val("");
                });

                _jq("#search-npc").autocomplete({
                    minLength: 3,
                    source: "http://portal.wow-alive.de/ajax/search/npc/",
                    select: function(event, ui) {
                        $("#npc-id").val(ui.item.value);
                        if($("#class").val() == 3){
                            $("#form-link").val("http://de.openwow.com/npc="+ui.item.value);
                            $("#link-tt").html('<a href="http://de.openwow.com/npc='+ui.item.value+'" target="_blank">WoWhead Tooltip</a>');
                            if($("#form-title").val().length == 0)
                                $("#form-title").val(ui.item.label);
                        }
                        else{
                            $("#link2-wrapper").show();
                            $("#form-link2").val("http://de.openwow.com/npc="+ui.item.value);
                            $("#link-tt2").html('<a href="http://de.openwow.com/npc='+ui.item.value+'" target="_blank">WoWhead Tooltip</a>');
                            $("#form-title").val($("#form-title").val()+": "+ui.item.label);
                        }
                        getBugs($("#form-link").val());
                    },
                    change: function(event, ui) {
                        $("#auto-npc").val("");
                    }
                });
            }
        },

        setRealmByCategory: function(){

            /**
             * The currently selected category
             * @type {*|jQuery}
             */
            var category = $("#project").val();

            /**
             * Top (Base) Category of the selected category
             * @type {number}
             */
            var baseCategory = 1;

            if(typeof bugtrackerProjectPaths != "undefined" &&
                typeof bugtrackerProjectPaths[cat] != "undefined" &&
                typeof bugtrackerProjectPaths[cat][0] != "undefined")
            {
                baseCategory = bugtrackerProjectPaths[cat][0] * 1;
            }

            var realmId = '1';

            // Main Project 1 is Norganon, Realm ID: 1
            if(baseCategory == 1){
                realmId = '1';
                this.openwowPrefix = 'http://wotlk.openwow.com';
            }
            // Main Project 2 is Cata, Realm ID: 2
            else if(baseCategory == 2){
                realmId = '2';
                this.openwowPrefix = 'http://cata.openwow.com';
            }

            this.realmId = realmId;

            return realmId;
        },

        /**
         * Search for Bugs to the same ID
         * @param questId
         */
        getSimilarBugs: function(type,wowId){

            var _jq = $;

            _jq.getJSON(Config.URL+'/ajax/search/bugs/'+type+'/'+wowId, function(data) {
                var items = [];

                _jq.each(data.results, function(bugId, bugLabel) {
                    items.push('<a href="/bugtracker/bug/' + bugId + '" target="_blank"> Bug #'+bugId+' - ' + bugLabel + '</a>');
                });

                if(items.length > 0){
                    _jq('#other-bugs').html('<span class="color-tooltip-red">Achtung!</span> Es gibt dazu schon Bug Reports:<br/>'+items.join('<br/>'));
                    _jq("#label-other-bugs").html("");
                }
                else{
                    _jq('#other-bugs').html('Keine anderen Bug Reports gefunden - <span class="color-tooltip-green">Alles in Ordnung</span>');
                    _jq("#label-other-bugs").html("");
                }
            });
        }


    });

    return BugtrackerController;
});
