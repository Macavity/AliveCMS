/*jshint -W041 */

define(['./BaseController', 'modules/tooltip'], function (BaseController, Tooltip) {

    var MigrationController = BaseController.extend({
        init: function(){
            this._super();
            debug.debug("MigrationController.init");

            this.initBindings();

        },

        initBindings: function(){
            debug.debug("MigrationController.initBindings");

            // Shortcuts cachen
            var _jq = $;

            /**
             * Gives access to the internal functions inside of function blocks
             */
            var Controller = this;

            _jq(".jsMigrationSubmit").unbind("click").bind("click", function(event){
                event.preventDefault();
                Controller.submitForm();
            });

            /*
            document.addEventListener("change", function(event){
                MigrationController.eventChangeController(event);
            }, false);

            document.addEventListener("click", function(event){
                MigrationController.eventClickController(event);
            }, false);*/

            _jq(".jsItemId").unbind("blur").on("blur",function(event){
                Controller.getItemInfo(event);
            });

            _jq(".jsItemId").each(function(object){
               Controller.getItemInfo({target: this});
            });

        },

        eventChangeController: function(event){

        },

        eventClickController: function(event){

        },

        getItemInfo: function(event){
            // Shortcuts cachen
            var _jq = $;
            var object = _jq(event.target);
            var value = object.val();

            /**
             * Gives access to the internal functions inside of function blocks
             */
            var Controller = this;


            if(value === ""){
                return;
            }


            var helper = object.parent().find(".help-inline");
            var controlGroup = object.parent().parent();
            controlGroup.removeClass("error");
            helper.html('<i class="icon-white icon-refresh"></i>');


            if(Controller.isNumber(value) === false){
                Controller.helperWarning(controlGroup ,helper, "Bitte nur Zahlen eingeben!");
            }
            else{
                _jq.ajax({
                    url: "/migration/item/1/"+value,
                    dataType: "json"
                })
                    .fail(function(error){
                        debug.warn("MigrationController.getItemInfo failed to load json");
                        debug.warn(error);
                    })
                    .success(function(jsonData){

                        if(jsonData.status == "error"){
                            Controller.helperWarning(controlGroup, helper, jsonData.message);
                        }
                        else{
                            var item = jsonData.item;

                            if(item.ItemLevel > 245){
                                Controller.helperItemWarning(controlGroup, helper, item);
                            }
                            else{
                                Controller.helperLink(controlGroup, helper, item);
                            }

                        }




                    });
            }
        },

        helperWarning: function(controlGroup, helper, text){
            controlGroup.addClass("error");
            helper.html('<i class="icon-white icon-warning-sign"></i> '+text);
        },

        helperItemWarning: function(controlGroup, helper, item){
            controlGroup.addClass("error");
            helper.html('<i class="icon-white icon-warning-sign"></i> ['+item.ItemLevel+'] <a href="/item/1/'+item.entry+'" target="_blank">'+item.name+'</a> <br>Itemlevel zu hoch!');
        },

        helperLink: function(controlGroup, helper, item){
            controlGroup.removeClass("error");
            helper.html('<i class="icon-white icon-ok"></i> ['+item.ItemLevel+'] <a href="/item/1/'+item.entry+'" class="color-q'+item.Quality+'" target="_blank">'+item.name+'</a>');
        },

        isNumber: function(value){
            return value === 0 || (value)>>>0 != 0;
        },

        submitForm: function(){
            debug.debug("MigrationController.submitForm");
            $("#migrationForm").submit();
        }
    });

    return MigrationController;
});
