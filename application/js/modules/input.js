
define(function(){


    /**
     * Input field helper. Shows default text on blur and hides on focus.
     */
    var Input = {

        /**
         * Initialize binds for search form.
         */
        initialize: function() {
            $('#search-form')
                .attr('autocomplete', 'off')
                .submit(function() {
                    return Input.submit('#search-field');
                });

            // Ensure alt text is displayed after empty search is submitted.
            Input.bind('#search-field');
        },

        /**
         * Bind the events to a target.
         *
         * @param target
         */
        bind: function(target) {
            Input.reset(target);

            $(target)
                .focus(function() {
                    Input.activate(this);
                })
                .blur(function() {
                    Input.reset(this);
                });
        },

        /**
         * Save the current placeholder to the cache and remove.
         *
         * @param node
         */
        activate: function(node) {
            node = $(node);

            if (node.val() == node.attr('alt'))
                node.val("");

            node.addClass("active");
        },

        /**
         * Display placeholder if value is empty.
         *
         * @param node
         */
        reset: function(node) {
            node = $(node);

            if (node.val() === "")
                node.removeClass("active").val(node.attr('alt'));
            else if (node.val() != node.attr('alt'))
                node.addClass("active");
        },

        /**
         * Clear field when submitting.
         *
         * @param node
         */
        submit: function(node) {
            node = $(node);

            if (node.val() == node.attr('alt'))
                node.val("");

            if (node.val().length < 2){
                Overlay.open(Msg.cms.shortQuery);
                return false;
            }

            return true;
        }
    };
    return Input;
});