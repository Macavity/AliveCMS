
define(['modules/core', 'modules/page'], function(Core, Page){

    /**
     * Creates a full page blackout.
     */
    var Blackout = {

        /**
         * Has the blackout been opened before?
         */
        initialized: false,

        /**
         * The DOM element.
         */
        element: null,

        /**
         * Create the div to be used.
         *
         * @constructor
         */
        initialize: function() {
            Blackout.element = $('<div/>', { id: 'blackout' });

            $("body").append(Blackout.element);

            Blackout.initialized = true;
        },

        /*
         * Shows the blackout
         *
         * @param callback (optional) - function that gets called after blackout shows
         * @param onClick  (optional) - function binds onclick functionality to blackout
         */
        show: function(callback, onClick) {
            if (!Blackout.initialized)
                Blackout.initialize();

            // Ie fix
            if (Core.isIE()) {
                Blackout.element
                    .css("width", Page.dimensions.width)
                    .css("height", $(document).height());
            }

            // Show blackout
            Blackout.element.show();

            // Call optional functions
            if (Core.isCallback(callback))
                callback();

            if (Core.isCallback(onClick))
                Blackout.element.click(onClick);
        },

        /*
         * Hides blackout
         *
         * @param callback (optional) - function that gets called after blackout hides
         */
        hide: function(callback) {
            Blackout.element.hide();

            if (Core.isCallback(callback))
                callback();

            Blackout.element.unbind("click");
        }
    };
    return Blackout;
});