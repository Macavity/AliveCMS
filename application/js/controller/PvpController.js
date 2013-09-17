/*jshint -W041*/
define(['./BaseController', 'modules/dynamic_menu'], function (BaseController, DynamicMenu) {

    var PvpController = BaseController.extend({

        realmId: 1,

        openwowPrefix: 'http://wotlk.openwow.com',

        category: null,

        otherLinkCounter: 0,

        lang: null,


        init: function(){
            this._super();


        }



    });

    return PvpController;
});
