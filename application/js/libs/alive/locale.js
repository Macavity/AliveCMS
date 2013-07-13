

define(function(){

    /**
     * Helper functions for switching language / region.
     */
    var Locale = {

        /**
         * Path to the data source.
         */
        dataPath: 'data/i18n.frag',

        /**
         * Initialize and bind "open menu" links.
         *
         * @constructor
         */
        initialize: function() {
            var path = location.pathname.replace(Core.baseUrl, "");
            path = path + (location.search || '?');

            $('#change-language, #service .service-language a').click(function() {
                return Locale.openMenu('#change-language', encodeURIComponent(path));
            });
        },

        /**

         * Open up the language selection menu at the target location.
         *
         * @param toggler
         * @param path
         * @param contextPath
         */
        openMenu: function(toggler, path, contextPath) {
            var node = $('#international');
            toggler = $(toggler);
            path = path || '';

            if (node.is(':visible')) {
                node.slideUp();
                toggler.toggleClass('open');

            } else {
                if (node.html() != "") {
                    Locale.display();
                    toggler.toggleClass('open');
                } else {
                    $.ajax({
                        url: Core.baseUrl +'/'+ Locale.dataPath +'?path='+ path,
                        dataType: 'html',
                        success: function(data, status) {
                            if (data) {
                                node.replaceWith(data);
                                toggler.toggleClass('open');
                                Locale.display();
                            }
                        }
                    });
                }
            }

            return false;
        },

        /**
         * Track language events.
         *
         * @param eventAction
         * @param eventLabel
         */
        trackEvent: function(eventAction, eventLabel) {
            try {
                _gaq.push(['_trackEvent', 'Battle.net Language Change Event', eventAction, eventLabel]);
            } catch(e) { }
        },

        /**
         * Display the international menu.
         */
        display: function() {
            var node = $('#international');
            node.slideDown('fast', function() {
                $(this).css('display', 'block');
            });

            // Opera doesn't animate on scroll down
            if (!$.browser.opera) {
                $('html, body').animate({
                    scrollTop: node.offset().top
                }, 1000);
            }
        }
    };
    return Locale;
});