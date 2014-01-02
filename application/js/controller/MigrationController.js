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
                            var selectedRace = $("#race").val();

                            var selectedFaction = 0;
                            var itemFaction = item.faction;
                            var itemCounterpart = item.counterpart;

                            if(itemFaction !== ""){

                                if(selectedRace == 2 || selectedRace == 5 || selectedRace == 6 || selectedRace == 8 || selectedRace == 10){
                                    selectedFaction = 1;
                                }

                                if(selectedFaction != itemFaction && itemCounterpart !== "" && itemCounterpart > 0){
                                    Controller.helperCounterWarning(controlGroup, helper, item);
                                    return;
                                }
                            }

                            if(item.ItemLevel > 251){
                                Controller.helperItemWarning(controlGroup, helper, item);
                            }
                            else{
                                var raceError = true;
                                var races = jsonData.races;
                                var length = races.length;

                                if(races && length > 0){

                                    for(var i = 0, l = races.length; i < l; i++){

                                        if(selectedRace == races[i]){
                                            raceError = false;
                                        }
                                    }
                                }
                                else{
                                    raceError = false;
                                }

                                if(raceError){
                                    Controller.helperRaceWarning(controlGroup, helper, item);
                                }
                                else{
                                    Controller.helperLink(controlGroup, helper, item);
                                }

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

        helperCounterWarning: function(controlGroup, helper, item){
            controlGroup.addClass("error");
            helper.html('<i class="icon-white icon-warning-sign"></i> <a href="/item/1/'+item.entry+'" target="_blank">'+item.name+'</a> ist für die falsche Fraktion, <br>Benutze stattdessen: <a href="/item/1/'+item.counterpart+'" target="_blank">'+item.counterpart+'</a>');
        },

        helperRaceWarning: function(controlGroup, helper, item){
            controlGroup.addClass("error");
            helper.html('<i class="icon-white icon-warning-sign"></i> ['+item.ItemLevel+'] <a href="/item/1/'+item.entry+'" target="_blank">'+item.name+'</a> <br>Dieses Item passt nicht zu deiner gewählten Rasse!');
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
