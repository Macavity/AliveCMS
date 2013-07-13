/**
 * All global and core class objects.
 */
define(function(){
    var Core = {

        /**
         * Base context URL for the project.
         */
        baseUrl: '/',

        /**
         * The cached string for the browser.
         */
        browser: null,

        /**
         * Dynamic load queue.
         */
        deferredLoadQueue: [],

        /**
         * Current locale.
         */
        locale: 'en-us',

        /**
         * The current project.
         */
        project: '',

        /**
         * Path to static content.
         */
        staticUrl: '/',

        /**
         * The current host and protocol.
         */
        host: '',

        /**
         * Initialize the script.
         *
         * @constructor
         */
        initialize: function() {
            Core.processLoadQueue();
            Core.ui();
            Core.host = location.protocol +'//'+ (location.host || location.hostname);
        },

        /**
         * Return letter (alphabet) values only within a string.
         *
         * @param string
         * @return string
         */
        alpha: function(string) {
            return string.replace(/[^a-zA-Z]/gi, '');
        },

        /**
         * Create a frame within the document.
         *
         * @param url
         * @param width
         * @param height
         * @param parent
         */
        appendFrame: function(url, width, height, parent) {
            if (url === undefined)
                return;

            if (width === undefined)
                width = 1;

            if (height === undefined)
                height = 1;

            if (parent === undefined)
                parent = $('body');

            if (Core.isIE())
                parent.append('<iframe src="' + url + '" width="' + width + '" height="' + height + '" scrolling="no" frameborder="0" allowTransparency="true"></iframe>');
            else
                parent.append('<object type="text/html" data="' + url + '" width="' + width + '" height="' + height + '"></object>');
        },

        /**
         * Fix column headers if multiple lines.
         *
         * @param query
         * @param baseHeight
         */
        fixTableHeaders: function(query, baseHeight) {
            $(query).each(function() {
                baseHeight = baseHeight || 18;

                var table = $(this);
                var height = baseHeight;

                table.find('.sort-link').each(function() {
                    var linkHeight = $(this).height();

                    if (linkHeight > height)
                        height = linkHeight;
                });

                if (height > baseHeight)
                    table.find('.sort-link, .sort-tab').css('height', height);
            });
        },

        /**
         * Format a locale to a specific structure.
         *
         * @param format
         * @param divider
         * @return string
         */
        formatLocale: function(format, divider) {
            divider = divider || '-';
            format = format || 1;

            switch (format) {
                case 1:
                default:
                    return Core.locale.replace('-', divider);
                    break;
                case 2:
                    var parts = Core.locale.split('-');
                    return parts[0] + divider + parts[1].toUpperCase();
                    break;
                case 3:
                    return Core.locale.toUpperCase().replace('-', divider);
                    break;
            }
        },

        /**
         * Convert a datetime string to a users local time zone and return as a string using the specified format.
         *
         * http://www.w3.org/TR/html5/common-microsyntaxes.html#valid-global-date-and-time-string
         *
         * @param format
         * @param datetime (2010-07-22T07:41-07:00)
         * @return string
         */
        formatDatetime: function(format, datetime) {
            var localDate;

            if (!datetime) {
                localDate = new Date();
            } else {
                // gecko
                localDate = new Date(datetime);

                // webkit
                if (isNaN(localDate.getTime())) { // 2010-07-22 07:41 GMT-0700
                    datetime = datetime.substring(0,10) + ' ' + datetime.substring(11,16) + ':00 GMT' + datetime.substring(16,19) + datetime.substring(20,22);
                    localDate = new Date(datetime);
                }

                // trident
                if (isNaN(localDate.getTime())) { // 07-22 07:41 GMT-0700 2010
                    datetime = datetime.substring(5,10) + ' ' + datetime.substring(11,16) + ' GMT' + datetime.substring(23,28) + ' ' + datetime.substring(0,4);
                    localDate = new Date(datetime);
                }

                if (isNaN(localDate.getTime())) {
                    return false;
                }
            }

            if (!format)
                format = 'yyyy-MM-ddThh:mmZ';

            var hr = localDate.getHours(),
                meridiem = 'AM';

            if (hr > 12) {
                hr -= 12;
                meridiem = 'PM';

            } else if (hr === 12) {
                meridiem = 'PM';

            } else if (hr === 0) {
                hr = 12;
            }

            var tz = parseInt(localDate.getTimezoneOffset() / 60 * -1, 10);

            if (tz < 0)
                tz = '-' + Core.zeroFill(Math.abs(tz), 2) + ':00';
            else
                tz = '+' + Core.zeroFill(Math.abs(tz), 2) + ':00';

            format = format.replace('yyyy', localDate.getFullYear());
            format = format.replace('MM', Core.zeroFill(localDate.getMonth() + 1,2));
            format = format.replace('dd', Core.zeroFill(localDate.getDate(),2));
            format = format.replace('HH', Core.zeroFill(localDate.getHours(),2));
            format = format.replace('hh', Core.zeroFill(hr,2));
            format = format.replace('mm', Core.zeroFill(localDate.getMinutes(),2));
            format = format.replace('a', meridiem);
            format = format.replace('Z', tz);

            return format;
        },

        /**
         * Detect the browser type, based on feature detection and not user agent.
         *
         * @return string
         */
        getBrowser: function() {
            if (Core.browser)
                return Core.browser;

            var s = $.support;

            if (!s.hrefNormalized && !s.tbody && !s.style && !s.opacity) {
                if ((typeof document.body.style.maxHeight != "undefined") || (window.XMLHttpRequest))
                    Core.browser = 'ie7';
                else
                    Core.browser = 'ie6';
            } else if (s.hrefNormalized && s.tbody && s.style && !s.opacity) {
                Core.browser = 'ie8';
            } else {
                Core.browser = 'other';
            }

            return Core.browser;
        },

        /**
         * Get the hash from the URL.
         *
         * @return string
         */
        getHash: function() {
            var hash = location.hash || "";

            return hash.substr(1, hash.length);
        },

        /**
         * Return the language based off locale.
         *
         * @return string
         */
        getLanguage: function() {
            return Core.locale.split('-')[0];
        },

        /**
         * Return the region based off locale.
         *
         * @return string
         */
        getRegion: function() {
            return Core.locale.split('-')[1];
        },

        /**
         * Conveniently jump to a page.
         *
         * @param url
         * @param base
         */
        goTo: function(url, base) {
            window.location.href = (base ? Core.baseUrl : '') + url;
            window.event.returnValue = false;
        },

        /**
         * Include a JavaScript file via XHR.
         *
         * @param url
         * @param success
         * @param cache - defaults to true
         */
        include: function(url, success, cache) {
            $.ajax({
                url: url,
                dataType: 'script',
                success: success,
                cache: cache !== false
            });
        },

        /**
         * Checks to see if the argument is a function/callback.
         *
         * @param callback
         * @return boolean
         */
        isCallback: function(callback) {
            return (callback && typeof callback === 'function');
        },

        /**
         * Is the browser using IE?
         *
         * @param version
         * @return boolean
         */
        isIE: function(version) {
            var browser = Core.getBrowser();

            if (version)
                return ('ie'+ version == browser);
            else
                return ((browser == 'ie6') || (browser == 'ie7') || (browser == 'ie8'));
        },

        /**
         * Loads either a JavaScript or CSS file, by default deferring the load until after other
         * content has loaded. The file type is determined by using the file extension.
         *
         * @param path
         * @param deferred - true by default
         * @param callback
         */
        load: function(path, deferred, callback) {
            deferred = deferred !== false;

            if (Page.loaded || !deferred)
                Core.loadDeferred(path);
            else
                Core.deferredLoadQueue.push(path);

            if (Core.isCallback(callback))
                callback();
        },

        /**
         * Determine which type to load.
         *
         * @param path
         */
        loadDeferred: function(path) {
            var queryIndex = path.indexOf("?");
            var extIndex = path.lastIndexOf(".") + 1;
            var ext = path.substring(extIndex, queryIndex == -1 ? path.length : queryIndex);

            switch (ext) {
                case 'js':
                    Core.loadDeferredScript(path);
                    break;
                case "css":
                    Core.loadDeferredStyle(path);
                    break;
            }
        },

        /**
         * Include JS file.
         *
         * @param path
         */
        loadDeferredScript: function(path) {
            $("<script/>", {
                type: "text/javascript",
                src: path
            }).appendTo("head");
        },

        /**
         * Include CSS file; must be done this way because of IE (of course).
         *
         * @param path
         * @param media
         */
        loadDeferredStyle: function(path, media) {
            $('head').append('<link rel="stylesheet" href="'+ path +'" type="text/css" media="'+ (media || "all") +'" />');
        },

        /**
         * Replace {0}, {1}, etc. with the passed arguments.
         *
         * @param str
         * @return string
         */
        msg: function(str) {
            for (var i = 1, len = arguments.length; i < len; ++i) {
                str = str.replace("{" + (i - 1) + "}", arguments[i]);
            }

            return str;
        },

        /**
         * This version can handle multiple occurences of the same token, but is slower due to the use of a RegExp. Only use if needed.
         *
         * @param str
         * @return string
         */
        msgAll: function(str) {
            for (var i = 1, len = arguments.length; i < len; ++i) {
                str = str.replace(new RegExp("\\{" + (i - 1) + "\\}", "g"), arguments[i]);
            }

            return str;
        },

        /**
         * Return numeric values only within a string.
         *
         * @param string
         * @return int
         */
        numeric: function(string) {
            string = string.replace(/[^0-9]/gi, '');

            if (!string || isNaN(string)) string = 0;

            return string;
        },

        /**
         * Open the link in a new window.
         *
         * @param node
         * @return false
         */
        open: function(node) {
            if (node.href)
                window.open(node.href);

            return false;
        },

        /**
         * Run on page load!
         */
        processLoadQueue: function() {
            if (Core.deferredLoadQueue.length > 0) {
                for (var i = 0, path; path = Core.deferredLoadQueue[i]; i++) {
                    Core.load(path);
                }
            }
        },

        /**
         * Scroll to a specific part of the page.
         *
         * @param target
         * @param duration
         * @param callback
         */
        scrollTo: function(target, duration, callback) {
            target = $(target);

            if (target.length <= 0)
                return;

            var win = $(window),
                top = target.offset().top;

            if (top >= win.scrollTop() && top <= win.scrollTop() + win.height())
                return;

            $($.browser.webkit ? 'body' : 'html').animate({
                    scrollTop: top
                },
                duration || 350,
                callback || null);
        },

        /**
         * Trims specific characters off the end of a string.
         *
         * @param string
         * @param c
         * @return string
         */
        trimChar: function(string, c) {
            if (string.substr(0, 1) === c)
                string = string.substr(1, (string.length - 1));

            if (string.substr((string.length - 1), string.length) === c)
                string = string.substr(0, (string.length - 1));

            return string;
        },

        /**
         * Apply global functionality to certain UI elements.
         *
         * @param context
         */
        ui: function(context) {
            context = context || document;

            if (Core.isIE(6)) {
                $('button.ui-button', context).hover(
                    function() {
                        var self = $(this);

                        if ((self.attr('disabled') != 'disabled') || (self.attr('disabled') != false))
                            self.addClass('hover');
                    },
                    function() {
                        $(this).removeClass('hover');
                    }
                );
            }

            if (Core.project != 'bam') {
                $('button.ui-button', context).click(function(e) {
                    var self = $(this);
                    var alt = self.attr('data-text');

                    if (alt == undefined)
                        alt = "";

                    if (this.tagName.toLowerCase() == 'button' && alt != "") {
                        if (self.attr('type') == 'submit') {
                            e.preventDefault();
                            e.stopPropagation();

                            self.find('span span').html(alt);
                            self.removeClass('hover')
                                .addClass('processing')
                                .attr('disabled', 'disabled');

                            // Manually submit
                            self.parents('form').submit();
                        }
                    }

                    return true;
                });
            }
        },

        /**
         * Zero-fills a number to the specified length (works on floats and negatives, too).
         *
         * @param number
         * @param width
         * @param includeDecimal
         * @return string
         */
        zeroFill: function(number, width, includeDecimal) {
            if (includeDecimal === undefined)
                includeDecimal = false;

            var result = parseFloat(number),
                negative = false,
                length = width - result.toString().length,
                i = length - 1;

            if (result < 0) {
                result = Math.abs(result);
                negative = true;
                length++;
                i = length - 1;
            }

            if (width > 0) {
                if (result.toString().indexOf('.') > 0) {
                    if (!includeDecimal)
                        length += result.toString().split('.')[1].length;

                    length++;
                    i = length - 1;
                }

                if (i >= 0) {
                    do {
                        result = '0' + result;
                    } while (i--);
                }
            }

            if (negative)
                return '-' + result;

            return result;
        }

    };

    return Core;
});
