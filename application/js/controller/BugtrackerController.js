/*jshint -W041*/
define(['./BaseController', 'modules/wiki', 'modules/wiki_related', 'modules/toast'], function (BaseController, Wiki, WikiRelated, Toast) {

    var BugtrackerController = BaseController.extend({

        realmId: 1,

        openwowPrefix: 'http://wotlk.openwow.com',

        category: null,

        otherLinkCounter: 0,

        lang: null,


        init: function(){
            this._super();

            debug.debug("BugtrackerController.initialize");

            this.initTables();
            this.initCreateForm();

            this.lang = mapStatic.lang.bugtracker;

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
                    method: 'numeric',
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

            if(_jq("#bugtrackerCreateForm").length > 0){

                /**
                 * Category Change - Show/Hide Form Wrappers
                 */
                _jq('#project').change(function(){ Controller.changeProject(); });

                // Execute once for initialization
                Controller.changeProject();

                /**
                 * Autocomplete for the search field
                 */
                _jq("#ac-search-field").autocomplete({
                    minLength: 3,
                    messages: {
                        noResults: '',
                        results: function() {}
                    },
                    source: function(request,response){
                        debug.debug("ac-search-field source");
                        Controller.autocompleteSource(request.term, response);
                    },
                    select: function(event, ui) { Controller.autocompleteSelectRow(ui.item); },
                    change: function(event, ui) { _jq("#ac-search-field").val(""); }
                }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {

                    var icon = '';
                    if(item.required_races == 690){
                        icon = '<i class="icon-faction-1 pre-icon"></i>';
                    }
                    else if(item.required_races == 1101){
                        icon = '<i class="icon-faction-0 pre-icon"></i>';
                    }

                    return _jq("<li>"+icon+"<a>" + item.label+ "</a></li>").appendTo(ul);
                };

                /**
                 * Button/Link Click Events
                 */
                _jq("#bugtrackerCreateForm")
                    .on("click",".jsDeleteLink", function(event){
                        event.preventDefault();
                        Controller.eventClickDeleteLink(event.target);
                    })
                    .on("click",".jsAddOtherLink", function(event){
                        event.preventDefault();
                        Controller.eventClickOtherLink(event.target);
                    })
                    .on("click","#form-submit", function(event){
                        event.preventDefault();
                        Controller.eventClickSubmit(event.target);
                    });
            }

            _jq("#content_ajax")
                .on("click", ".jsEditComment", function(event){
                    event.preventDefault();
                    Controller.eventClickEditComment(event.target);
                })
                .on("click", ".jsSubmitEditComment", function(event){
                    event.preventDefault();
                    Controller.eventClickSubmitEditComment(event.target);
                });
        },

        eventClickEditComment: function(target){

            var _jq = $;
            var Controller = this;

            var button = $(target);

            while(!button.is("button")){
                button = button.parent();
            }

            var commentId = button.data("comment");
            $.ajax({
                url: "/bugtracker/edit_comment/",
                type: 'POST',
                data: {
                    csrf_token_name: Config.CSRF,
                    action: "get",
                    comment: commentId
                },
                dataType: "json",
                success: function(data){

                    if(typeof data.msg != "undefined"){
                        Toast.show(data.msg);
                    }
                    if(typeof data.text != "undefined"){
                        var bug = $("#bug-id").val();
                        var template = Controller.getTemplate("bugtracker_edit_comment");
                        var html = template({
                            id: commentId,
                            content: data.text,
                            lang: mapStatic.lang
                        });

                        _jq("#comment-content-"+commentId).html(html);
                    }
                }
            });

        },

        eventClickSubmitEditComment: function(target){

            var _jq = $;
            var Controller = this;

            var button = $(target);

            while(!button.is("button")){
                button = button.parent();
            }

            var commentId = button.data("comment");

            var newContent = _jq("#comment-edit-textarea-"+commentId).val();

            $.ajax({
                url: "/bugtracker/edit_comment/",
                type: 'POST',
                data: {
                    csrf_token_name: Config.CSRF,
                    action: "edit",
                    comment: commentId,
                    content: newContent
                },
                dataType: "json",
                success: function(data){

                    if(typeof data.msg != "undefined"){
                        Toast.show(data.msg);
                    }
                    if(typeof data.text != "undefined"){
                        _jq("#comment-content-"+commentId).html(data.text);
                        _jq("#comment-content-"+commentId).parent().append().html('<span class="action-log"><span class="time">Gerade bearbeitet worden von '+data.username+'</span></span><br>');
                    }
                }
            });

        },

        eventClickDeleteLink: function(target){
            debug.debug("BugtrackerController.eventClickDeleteLink");

            var _jq = $;

            var button = $(target);

            if(!button.is("button")){
                button = button.parent();
            }

            var targetId = button.data("target");

            _jq("#"+targetId).remove();

            // Count the left over rows
            var rows = _jq("#form-link-wrapper table tbody tr").not(".no-results");

            // No other rows anymore?
            if(rows.length == 0){
                _jq("#form-link-wrapper table tbody tr.no-results").show();
            }
        },

        eventClickOtherLink: function(target){

            var _jq = $;
            var Controller = this;

            var button = $(target);

            if(!button.is("button")){
                button = button.parent();
            }

            var targetId = button.data("target");

            Controller.otherLinkCounter++;

            var link = _jq("#"+targetId).val().replace("http://","");

            Controller.addLinkToList("other-link-"+Controller.otherLinkCounter, "http://"+link);

            // Empty link text field
            _jq(".jsAddOtherLink").focusout().val("");
        },

        eventClickSubmit: function(target){

            var _jq = $;
            var Controller = this;

            if(_jq("#project").val() == "-"){
                Toast.show(mapStatic.lang.bugtracker.errorProject);
                return;
            }
            if(_jq("#form-title").val() == ""){
                Toast.show("Bitte trage vor dem Abschicken einen Titel ein.");
                return;
            }
            if(_jq("#form-desc").val() == ""){
                Toast.show("Bitte trage eine kurze Beschreibung des Problems ein.");
                return;
            }

            _jq("#bugtrackerCreateForm").submit();

        },

        changeProject: function(){
            debug.debug("BugtrackerController.changeProject");

            var _jq = $;
            var Controller = this;

            // Refresh Controller.category
            var category = Controller.getCategory() * 1;
            var topCategory = Controller.getTopCategory(category) * 1;

            debug.debug("Category: "+category);
            debug.debug("TopCategory: "+topCategory);

            if(topCategory == 3){
                debug.debug("Website Bug");
                // First show all generic fields
                _jq(".jsProjectFirst").show();

                // Hide specific
                _jq("#alert-project").hide();
                _jq(".jsServerOnly").hide();

                // Highest priority, show website only again
                _jq(".jsWebsiteOnly").show();
            }
            else if(category > 0){
                debug.debug("Show jsProjectFirst Wrappers");

                var realmId = Controller.setRealmByCategory();

                _jq(".jsProjectFirst").show();
                _jq("#alert-project").hide();
                _jq(".jsWebsiteOnly").hide();
                _jq(".jsServerOnly").show();

                if(_jq.inArray(category, fixBugShitCategories) > -1)
                {
                    _jq(".jsFixBugShitOnly").show();
                }
                else
                {
                    _jq(".jsFixBugShitOnly").hide();
                }

                if(realmId == 1 || realmId == 2){
                    _jq("#ac-search-wrapper").show();
                }
                else{
                    _jq("#ac-search-wrapper").hide();
                }
            }
            else{
                debug.debug("Hide Wrappers");
                _jq("#alert-project").show();
                _jq(".jsProjectFirst").hide();
                _jq("#ac-search-wrapper").hide();
            }
        },

        autocompleteSource: function(term, response){

            var _jq = $;
            var Controller = this;

            var searchType = _jq("#ac-search-type").val();

            var url = null;

            var realmId = Controller.setRealmByCategory();

            _jq("#ac-loader").html('<i class="ajax-loading"></i>');

            if(searchType == "quest"){
                url = Config.URL + 'ajax/search/quest/'+realmId+'/'+term;
            }
            else if(searchType == "npc"){
                url = Config.URL + 'ajax/search/npc/'+realmId+'/'+term;
            }
            else if(searchType == "zone"){
                url = Config.URL + 'ajax/search/zone/'+realmId+'/'+term;
            }

            _jq.ajax({
                url: url,
                success: function(data){
                    response(data.results);
                },
                dataType: 'json'
                })
                .complete(function(){
                    _jq("#ac-loader").html("");
                });
        },

        autocompleteSelectRow: function(item){

            var _jq = $;
            var Controller = this;

            var searchType = _jq("#ac-search-type").val();

            var wowId = item.value;
            var wowLabel = item.label;

            var uid = Controller.realmId+"-"+searchType+"-"+wowId;
            var link = Controller.openwowPrefix+searchType+"="+wowId;

            // Add the label to the Bug Title
            if(_jq("#form-title").val().length == 0){
                _jq("#form-title").val(wowLabel);
            }
            else{
                _jq("#form-title").val(_jq("#form-title").val()+", "+wowLabel);
            }

            // Add the link to the list of links
            Controller.addLinkToList(uid, link);

            // Empty search field
            _jq("#ac-search-field").val("");

            // Search for Reports about the same Quest
            Controller.getSimilarBugs(searchType, wowId);
        },

        addLinkToList: function(uid, link){

            var _jq = $;
            var Controller = this;

            var rows = _jq("#form-link-wrapper table tbody tr").not(".no-results");
            var lastRow = rows.last();
            var css = "row1";

            _jq("#form-link-wrapper table tbody tr.no-results").hide();
            if(rows.length > 0){
                css = (lastRow.hasClass("row1")) ? "row2" : "row1";
            }

            var template = Controller.getTemplate("bugtracker_linklist");
            var listHtml = template({
                uid: uid,
                link: link,
                css: css,
                lang: Controller.lang.bugtracker
            });

            _jq("#form-link-wrapper table tbody").append(listHtml);
        },

        /**
         * Refresh selected category/project
         * @returns {null}
         */
        getCategory: function(){
            this.category = $("#project").val();
            return this.category;
        },

        /**
         *
         * @returns {number}
         */
        getTopCategory: function(category){
            var baseCategory = 0;

            if(typeof bugtrackerProjectPaths != "undefined" &&
                typeof bugtrackerProjectPaths[category] != "undefined" &&
                typeof bugtrackerProjectPaths[category][0] != "undefined")
            {
                baseCategory = bugtrackerProjectPaths[category][0] * 1;
            }

            return baseCategory;
        },

        setRealmByCategory: function(){

            var _jq = $;
            var Controller = this;

            /**
             * The currently selected category
             * @type {*|jQuery}
             */
            var category = Controller.category;

            /**
             * Top (Base) Category of the selected category
             * @type {number}
             */
            var baseCategory = Controller.getTopCategory(category);

            var realmId = '1';

            // Main Project 1 is Norganon, Realm ID: 1
            if(baseCategory == 1){
                realmId = '1';
                Controller.openwowPrefix = 'http://wotlk.openwow.com/';
            }
            // Main Project 2 is Cata, Realm ID: 2
            else if(baseCategory == 2){
                realmId = '2';
                Controller.openwowPrefix = 'http://cata.openwow.com/';
            }

            Controller.realmId = realmId;

            return realmId;
        },

        /**
         * Search for Bugs to the same ID
         * @param questId
         */
        getSimilarBugs: function(type,wowId){

            var _jq = $;
            var Controller = this;

            var template = null;
            var html = "";

            _jq.ajax({
                    url: Config.URL+'ajax/search/bugs/'+type+'/'+wowId,
                    dataType: "json",
                    success: function(data) {

                        if(data.results.length > 0){
                            template = Controller.getTemplate("bugtracker_similar_bugs");
                            html = template({
                                results: data.results,
                                lang: Controller.lang
                            });
                            _jq('#form-similar-bugs-wrapper .controls').html(html);
                        }
                        else{
                            template = Controller.getTemplate("alert");
                            html = template({
                                type: "success",
                                header: Controller.lang.alright,
                                message: " - "+Controller.lang.noSimilarBugs
                            });
                            _jq('#form-similar-bugs-wrapper .controls').html(html);
                        }
                    }
                })
                .complete({

                });

        }


    });

    return BugtrackerController;
});
