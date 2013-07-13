
define(function(){

    /**
     * Determines the browser and version based off the user agent.
     */
    var UserAgent = {

        /**
         * User agent header.
         */
        header: navigator.userAgent.toLowerCase(),

        /**
         * The current browser.
         */
        browser: 'other',

        /**
         * The current version, single number.
         */
        version: null,

        /**
         * Extracte the browser and version.
         *
         * @constructor
         */
        initialize: function() {
            var userAgent = UserAgent.header,
                version = "",
                browser = "";

            // Browser
            if (userAgent.indexOf('firefox') != -1)
                browser = 'ff';

            else if (userAgent.indexOf('msie') != -1)
                browser = 'ie';

            else if (userAgent.indexOf('chrome') != -1)
                browser = 'chrome';

            else if (userAgent.indexOf('opera') != -1)
                browser = 'opera';

            else if (userAgent.indexOf('safari') != -1)
                browser = 'safari';

            // Version
            if (browser == 'ff')
                version = /firefox\/([-.0-9]+)/.exec(userAgent);

            else if (browser == 'ie')
                version = /msie ([-.0-9]+)/.exec(userAgent);

            else if (browser == 'chrome')
                version = /chrome\/([-.0-9]+)/.exec(userAgent);

            else if (browser == 'opera')
                version = /opera\/([-.0-9]+)/.exec(userAgent);

            else if (browser == 'safari')
                version = /safari\/([-.0-9]+)/.exec(userAgent);

            UserAgent.browser = browser;
            UserAgent.version = version[1].substring(0, 1);

            var className = browser;

            if (UserAgent.version)
                className += ' '+ browser + UserAgent.version;

            if (browser == 'ie' && (UserAgent.version == 6 || UserAgent.version == 7))
                className += ' ie67';

            $('html').addClass(className);
        }
    };
});