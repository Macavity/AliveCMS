
define(function(){


    /*
     * Simple open/hide toggle system.
     */
    var Toggle = {

        /**
         * Node cache.
         */
        cache: {},

        /**
         * Custom defined callback function.
         */
        callback: null,

        /**
         * Timeout to close the menu automatically.
         */
        timeout: 800,

        /**
         * Determines whether or not to persist menu open.
         */
        keepOpen: false,

        /**
         * Opens a menu / dropdown element.
         *
         * @param triggerNode
         * @param activeClass
         * @param targetPath
         * @param delay
         */
        open: function(triggerNode, activeClass, targetPath, delay) {
            if (delay)
                Toggle.timeout = delay;

            //keep menu open
            Toggle.keepOpen = true;

            var key = Toggle.key(targetPath);

            //bind events and cache
            if (!Toggle.cache[key]) {
                //bind events and toggle the class
                $(triggerNode)
                    .mouseleave(function() {
                        Toggle.keepOpen = false;
                        Toggle.close(triggerNode, activeClass, targetPath, Toggle.timeout);
                    })
                    .mouseenter(function() {
                        Toggle.keepOpen = true;
                        window.clearTimeout(Toggle.cache[key].timer);
                    });

                //bind events and toggle display of the target
                $(targetPath)
                    .mouseleave(function() {
                        Toggle.keepOpen = false;
                        Toggle.close(triggerNode, activeClass, targetPath, Toggle.timeout);
                    })
                    .mouseenter(function() {
                        Toggle.keepOpen = true;
                        window.clearTimeout(Toggle.cache[key].timer);
                    });

                //cache properties
                Toggle.cache[key] = {
                    trigger: triggerNode,
                    target: targetPath,
                    activeClass: activeClass,
                    key: key,
                    timer: null
                };
            }

            //toggle class/display
            $(triggerNode).toggleClass(activeClass);
            $(targetPath).toggle();

            window.clearTimeout(Toggle.cache[key].timer);
        },

        /**
         * Close the menu and clear any cached timers.
         *
         * @param triggerNode
         * @param activeClass
         * @param targetPath
         * @param delay
         */
        close: function(triggerNode, activeClass, targetPath, delay) {
            var key = Toggle.key(targetPath);

            window.clearTimeout(Toggle.cache[key].timer);

            Toggle.cache[key].timer = setTimeout(function() {
                if (Toggle.keepOpen)
                    return;

                $(targetPath).hide();
                $(triggerNode).removeClass(activeClass);
                Toggle.triggerCallback();
            }, delay);
        },

        /**
         * Generate the key.
         *
         * @param targetPath
         * @return string
         */
        key: function(targetPath) {
            return (typeof targetPath == 'string') ? targetPath : '#'+ $(targetPath).attr('id');
        },

        /*
         * Trigger a callback if defined
         */
        triggerCallback: function() {
            if (Core.isCallback(Toggle.callback))
                Toggle.callback();
        }
    };
    return Toggle;
});