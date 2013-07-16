
define(function(){

    /* Show a custom contextual menu at the desired location */
    var ContextMenu = {

        DELAY_HIDE: 333,

        // DOM
        object: null,
        node: null,
        parentNode: null,
        cb: null,

        initialize: function() {

            if(ContextMenu.object !== null) {
                return;
            }

            ContextMenu.object = $('<div/>')
                .attr('id', 'context-menu')
                .addClass('flyout-menu')
                .appendTo('body')
                .mouseenter(ContextMenu.onMouseOver)
                .mouseleave(ContextMenu.onMouseOut);
        },

        show: function(node, contents) {

            if(ContextMenu.parentNode !== null) {
                ContextMenu.parentNode.removeClass('hover');
            }
            clearTimeout(ContextMenu.timer);

            node = $(node);

            ContextMenu.node = node;
            ContextMenu.parentNode = node.parent();

            ContextMenu.initialize();
            ContextMenu.object.html(contents);
            ContextMenu.position(node);

            ContextMenu.parentNode.addClass('hover');
        },

        onMouseOver: function() {
            clearTimeout(ContextMenu.timer);
        },

        onMouseOut: function() {
            ContextMenu.hide();
        },

        delayedHide: function() {
            clearTimeout(ContextMenu.timer);
            ContextMenu.timer = setTimeout(ContextMenu.hide, ContextMenu.DELAY_HIDE);
        },

        /**
         * Hide the menu.
         */
        hide: function() {

            ContextMenu.object.hide();

            if(ContextMenu.parentNode !== null) {
                ContextMenu.parentNode.removeClass('hover');
            }

            ContextMenu.node = null;
            ContextMenu.parentNode = null;
        },

        /**
         * Position the menu at the middle right.
         *
         * @param node
         */
        position: function(node) {
            var offset = node.offset(),
                nodeWidth = node.outerWidth(),
                nodeHeight = node.outerHeight(),
                winWidth = ($(window).width() / 3),
                width = ContextMenu.object.outerWidth(),
                height = ContextMenu.object.outerHeight(),
                y = (offset.top + (nodeHeight / 2)) - (height / 2),
                x;

            if (offset.left > (winWidth * 2))
                x = (offset.left - width) - 10;
            else
                x = offset.left + nodeWidth;

            ContextMenu.object.css({
                top: y,
                left: x + 5
            }).fadeIn('fast');
        }

    };
    return ContextMenu;

});