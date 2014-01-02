define([
    'modules/core',
    'modules/page',
    'modules/char_select',
    'modules/login',
    'modules/tooltip',
    'modules/wow',
    'modules/explore',
    'modules/dynamic_menu'
],

    function (Core, Page, CharSelect, Login, Tooltip, Wow, Explore, DynamicMenu) {

    var BaseController = Class.extend({

        init: function(){
            debug.debug("BaseController.init");

            this.initTS3Viewer();
            this.initUserplate();

            this.initFormBehaviour();
            this.initDynamicMenu();

            /*
             Input.initialize();
             Flash.initialize();
             Locale.initialize();
             */

            Page.initialize();
            Core.initialize();
            CharSelect.initialize();
            Wow.initialize();
            Explore.initialize();

            // Set data-tooltip binds globally
            //

        },

        initUserplate: function(){
            $(".plate-logged-out").on("click",function(e){
                e.preventDefault();
                Login.open();
            });
        },

        initFormBehaviour: function(){
            debug.debug("BaseController.initFormBehaviour");

            // Shortcuts cachen
            var _jq = $;

            _jq("[data-toggle=buttons-radio] .btn").each(function(){
                _jq(this).bind('click', function(){
                    var object = _jq(this);
                    if(object.data("target")){
                        object.parent().parent().find('[name="'+object.data("target")+'"]').val(object.val());
                    }
                    else{
                        object.parent().parent().find("input.hidden").value = object.val();
                    }
                });
            });
        },

        initDynamicMenu: function(){
            var _jq = $;

            var dynamicMenues = _jq(".dynamic-menu");
            if(dynamicMenues.length > 0){
                debug.debug("BaseController.initDynamicMenu")
                dynamicMenues.each(function(){
                    var menu = new DynamicMenu(_jq(this));
                });
            }
        },

        initTS3Viewer: function(){
            debug.debug("Base.initTS3Viewer");
            $("#ts_button").mouseover(function(){ $("#sb_passive_large").addClass("sb_open"); });
            $("#ts_button").mouseout(function(){ $("#sb_passive_large").removeClass("sb_open"); });
            $("#ts_button").click(function(){
                // open slide
                if(!$(this).hasClass("sb_active")){
                    $("#ts_label, #sb_passive_large, #ts_button").addClass("sb_active");
                    $("#ts_control").addClass("sb_open");
                    $("#ts_control").addClass("sb_n_load");

                    if($("#ts3viewer_1014260").length === 0){
                        $("#ts_overlay").addClass("sb_n_load");
                        $("#ts_overlay").append('<div id="ts3viewer_1014260" style="margin-left:3px; margin-top:8px"> </div>');
                        ts3v_display.init(mapStatic.urls.ts3viewer, 1014260, 100);
                    }
                    $("#ts_overlay").show("slide", { to: { width: 319 } }, 500, callback_sb_open );
                    $("#ts_control").animate({left:"320px"}, 499);
                }
                // close slide
                else{
                    $("#ts_overlay").hide("slide", { to: { width: 0 } }, 500, callback_sb_close );
                    $("#ts_control").animate({left:"0px"}, 499);
                }
            });

            function callback_sb_open(){
                $("#ts_control").css("left", "320px");
                $("#ts_control, #ts_overlay").removeClass("sb_n_load");
            }

            function callback_sb_close(){
                $("#ts_label, #sb_passive_large, #ts_button").removeClass("sb_active");
                $("#ts_control").removeClass("sb_open");
                $("#ts_control").css("left", "0px");

            }
        },

        /**
         * Returns a localized string based on its language key.
         */
        l: function(string){
            if(typeof mapStatic.precompiled.lang === undefined){
                this.initLanguage();
            }

            var lang = mapStatic.precompiled.lang;

            if(lang.hasOwnProperty(string)){
                return lang[string];
            }

            debug.warn('Localized String for "'+string+'" not found.');
            return "";
        },

        /**
         * Checks if a template is already compiled in Handlebars,
         * if not the compilation is run.
         * @param templateName
         * @returns {*} The compiled template
         */
        getTemplate: function(templateName) {
            return Handlebars.templates[templateName];
        }

    });

    return BaseController;
});
