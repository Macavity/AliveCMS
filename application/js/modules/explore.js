
define(['modules/app', 'modules/toggle'], function(App, Toggle){


    /**
     * Explore menu.
     */
    var Explore = {

        /**
         * Enable the explore links.
         *
         * @constructor
         */
        initialize: function() {
            var links = $('a[rel="javascript"]');

            if (links.length) {
                links
                    .removeAttr('onclick')
                    .removeAttr('onmouseover')
                    .removeAttr('title')
                    .css('cursor', 'pointer');
            }

            var exploreLink = $('#explore-link');
            var newsLink = $('#breaking-link');

            if (exploreLink.length > 0) {
                exploreLink.unbind().click(function() {
                    Toggle.open(this, 'active', '#explore-menu');
                    return false;
                });
            }

            if (newsLink.length > 0) {
                newsLink.unbind().click(function() {
                    App.breakingNews();
                    return false;
                });
            }
        }
    };
    return Explore;
});