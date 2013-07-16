
define(function(){

    /**
     * Variables and functions for flash
     */
    var Flash = {

        /**
         * Video player for this project
         */
        videoPlayer: '',

        /**
         * The flash base of the videos for this project
         */
        videoBase:   '',

        /**
         * Rating image based on locale
         */
        ratingImage: '',

        /**
         * Express install location
         */
        expressInstall: '/common/static/flash/expressInstall.swf',

        /**
         * Store values populated after load
         */
        initialize: function() {
            //set flash base and rating image
            Flash.defaultVideoParams.base          = Flash.videoBase;
            Flash.defaultVideoFlashVars.ratingpath = Flash.ratingImage;
        },

        /**
         * Default video params for the video player
         */
        defaultVideoParams: {
            allowFullScreen:   "true",
            bgcolor:           "#000000",
            allowScriptAccess: "always",
            wmode:             "opaque",
            menu:              "false"
        },

        /**
         * Default flash vars for videos
         */
        defaultVideoFlashVars: {
            ratingfadetime: "2",
            ratingshowtime: "1"
        }
    };

    return Flash;
});