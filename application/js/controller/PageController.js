
define(['./BaseController'], function (BaseController) {


    var PageController = BaseController.extend({
        init: function(){
            this._super();
            debug.debug("PageController.initialize");
        },

        loadUserplate: function(user_id){

            var _jq = $;
            var Controller = this;

            $.ajax({
                url: "http://www.senzaii.net/ajax/ajax_character/userplate",
                type: 'POST',
                data: {
                    user: user_id,
                    is_json_ajax: 1,
                    action: "get"
                },
                dataType: "json",
                success: function(data){

                    if(typeof data.activeChar.name != "undefined")
                    {

                        var template = Controller.getTemplate("userplate");

                        var html = template({
                            username: data.username,
                            activeChar: data.activeChar,
                            charList: [],
                            lang: mapStatic.lang
                        });

                        _jq(".user-plate.ajax-update").replaceWith(html);
                    }
                }
            });
        },

        getTemplate: function(templateName){
            return Handlebars.templates[templateName];
        }

    });

    return PageController;
});
