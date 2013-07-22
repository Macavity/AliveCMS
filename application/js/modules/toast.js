
define(['modules/core'], function(Core){

    /**
     * Pop up toasts at the bottom left of the browser, or at a certain location.
     */
    var Toast = {

        /**
         * DOM object.
         */
        container: null,

        /**
         * Has the class been initialized?
         */
        initialized: false,

        /**
         * Max toasts to display.
         */
        max: 5,

        /**
         * Default options.
         */
        options: {
            timer: 15000,
            autoClose: true,
            onClick: null
        },

        /**
         * Build the container.
         *
         * @constructor
         */
        initialize: function() {
            Toast.container = $('<div/>')
                .attr('id', 'toast-container')
                .show()
                .appendTo('body');

            Toast.initialized = true;
        },

        /**
         * Create the toast element.
         *
         * @param content
         * @return object
         */
        create: function(content) {
            var toast = $('<div/>')
                .addClass('ui-toast')
                .hide()
                .appendTo(Toast.container);

            $('<div/>').addClass('toast-arrow').appendTo(toast);
            $('<div/>').addClass('toast-top').appendTo(toast);
            $('<div/>').addClass('toast-content').appendTo(toast).html(content);
            $('<div/>').addClass('toast-bot').appendTo(toast);

            $('<a/>')
                .addClass('toast-close')
                .attr('href', 'javascript:;')
                .appendTo(toast)
                .click(function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    $(this).parent('.ui-toast').fadeOut('normal', function() {
                        $(this).remove();
                    });
                });

            return toast;
        },

        /**
         * Pop up a toast.
         *
         * @param content
         * @param options	timer, autoClose, onClick
         */
        show: function(content, options) {
            if (!Toast.initialized)
                Toast.initialize();

            Toast.truncate();

            var toast = Toast.create(content);

            options = $.extend({}, Toast.options, options);

            if (options.autoClose) {
                window.setTimeout(function() {
                    toast.fadeOut('normal', function() {
                        toast.remove();
                    });
                }, options.timer);

            } else {
                toast.click(function() {
                    toast.fadeOut('normal', function() {
                        toast.remove();
                    });
                }).css('cursor', 'pointer');
            }

            if (Core.isCallback(options.onClick))
                toast.click(options.onClick).css('cursor', 'pointer');

            toast.fadeIn();
        },

        /**
         * Truncate toasts if it exceeds the max limit.
         */
        truncate: function() {
            var total = Toast.container.find('.ui-toast');

            if (total.length > Toast.max)
                Toast.container.find('.ui-toast:lt('+ Math.round(total.length - Toast.max) +')').fadeOut();
        }
    };
    return Toast;
});