
define(function(){

    /**
     * Application related functionality.
     */
    var App = {

        /**
         * Hide the service bar warnings.
         *
         * @param target
         * @param cookie
         */
        closeWarning: function(target, cookie) {
            $(target).hide();

            if (cookie)
                App.saveCookie(cookie);
        },

        /**
         * Open and close the breaking news.
         *
         * @param lastId
         */
        breakingNews: function(lastId) {
            var node = $("#breaking-news");
            var news = $("#announcement-warning");

            if (news.is(':visible')) {
                news.hide();
                node.removeClass('opened');
            } else {
                news.show();
                node.addClass('opened');
            }

            if (lastId)
                Cookie.create('serviceBar.breakingNews', lastId);
        },

        /**
         * Save a cookie.
         *
         * @param name
         */
        saveCookie: function(name) {
            Cookie.create('serviceBar.'+ name, 1, {
                expires: 8760, // 1 year of hours
                path: '/'
            });
        },

        /**
         * Reset a cookie.
         *
         * @param name
         */
        resetCookie: function(name) {
            Cookie.create('serviceBar.'+ name, 0, {
                expires: 8760, // 1 year of hours
                path: '/'
            });
        },

        /**
         * Hide service bar elements depending on cookies.
         */
        serviceBar: function() {
            var browser = Cookie.read('serviceBar.browserWarning');
            var locale = Cookie.read('serviceBar.localeWarning');

            if (browser == 1)
                $('#browser-warning').hide();

            if (locale == 1)
                $('#i18n-warning').hide();
        },

        /**
         * Dynamically load more than one sidebar module at a time.
         *
         * @param modules
         */
        sidebar: function(modules) {
            if (modules) {
                for (var i = 0; i <= (modules.length - 1); ++i) {
                    App.loadModule(modules[i]);
                }
            }
        },

        /**
         * Load the content of a sidebar module through AJAX.
         *
         * @param key
         */
        loadModule: function(key) {
            var module = $('#sidebar-'+ key);

            if (module.length > 0) {
                $.ajax({
                    url: Core.baseUrl +'/sidebar/'+ key,
                    type: 'GET',
                    dataType: 'html',
                    cache: false,
                    global: false,
                    success: function(data) {
                        if (data)
                            module.html(data);
                        else
                            module.remove();
                    },
                    error: function() {
                        module.remove();
                    }
                });
            }
        }
    };
    return App;
});