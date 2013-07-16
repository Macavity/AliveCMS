/**
 * All global and core class objects.
 *
 * @copyright	2010, Blizzard Entertainment, Inc
 * @class		Core
 * @version     2.2
 */

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

/**
 * Methods for creating, reading, and deleting cookies.
 */
var Cookie = {

	/**
	 * Cached cookies.
	 */
	cache: {},

	/**
	 * Create a cookie. Can accept a third parameter as a literal object of options.
	 *
	 * @param key
	 * @param value
	 * @param options
	 */
	create: function(key, value, options) {
		options = $.extend({}, options);
		options.expires = options.expires || 1; // 1 hour

		if (typeof options.expires == 'number') {
			var hours = options.expires;
			options.expires = new Date();
			options.expires.setTime(options.expires.getTime() + (hours * 3600000));
		}

		var cookie = [
			encodeURIComponent(key) +'=',
			options.escape ? encodeURIComponent(value) : value,
			options.expires ? '; expires=' + options.expires.toUTCString() : '',
			options.path ? '; path=' + options.path : '',
			options.domain ? '; domain=' + options.domain : '',
			options.secure ? '; secure' : ''
		];

		document.cookie = cookie.join('');

		if (Cookie.cache) {
			if (options.expires == -1)
				delete Cookie.cache[key];
			else
				Cookie.cache[key] = value;
		}
	},

	/**
	 * Read a cookie.
	 *
	 * @param key
	 * @return string
	 */
	read: function(key) {
		// Use cache when available
		if (Cookie.cache[key])
			return Cookie.cache[key];

		var cache = {};
		var cookies = document.cookie.split(';');

		if (cookies.length > 0) {
			for (var i = 0; i < cookies.length; i++) {
				var parts = cookies[i].split('=');

				if (parts.length >= 2)
					cache[$.trim(parts[0])] = parts[1];
			}
		}

		Cookie.cache = cache;
		return cache[key] || null;
	},

	/**
	 * Delete a cookie.
	 *
	 * @param key
	 */
	erase: function(key) {
		Cookie.create(key, true, {
			expires: -1
		});
	}
};

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

		if (node.val() == "")
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

/**
 * Utility to record window scroll / dimensions.
 */
var Page = {

	/**
	 * Window object.
	 */
	object: null,

	/**
	 * Initialized?
	 */
	loaded: false,

	/**
	 * Window dimensions.
	 */
	dimensions: {
		width: 0,
		height: 0
	},

	/**
	 * Window scroll.
	 */
	scroll: {
		top: 0,
		width: 0
	},

	/**
	 * Initialized and grab window properties.
	 *
	 * @constructor
	 */
	initialize: function() {
		if (Page.loaded)
			return;

		if (!Page.object)
			Page.object = $(window);

		Page.object
			.resize(Page.getDimensions)
			.scroll(Page.getScrollValues);

		Page.getScrollValues();
		Page.getDimensions();
		Page.loaded = true;
	},

	/**
	 * Get window scroll values.
	 */
	getScrollValues: function() {
		Page.scroll.top  = Page.object.scrollTop();
		Page.scroll.left = Page.object.scrollLeft();
	},

	/**
	 * Get window dimensions.
	 */
	getDimensions: function() {
		Page.dimensions.width  = Page.object.width();
		Page.dimensions.height = Page.object.height();
	}
};

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

/**
 * Manage the context / character selection menu.
 */
var CharSelect = {

	/**
	 * Initialize the class.
	 *
	 * @constructor
	 */
	initialize: function() {
		$(document).undelegate('a.context-link', 'click', CharSelect.toggle);
		$(document).delegate('a.context-link', 'click', CharSelect.toggle);

		$('div.scrollbar-wrapper').css('overflow', 'hidden');
		$('input.character-filter')
			.blur(function() { Toggle.keepOpen = false; })
			.keyup(CharSelect.filter);

		Input.bind('.character-filter');
	},

	/**
	 * Pin a character to the top.
	 *
	 * @param index
	 * @param link
	 */
	pin: function(index, realm, link) {
	    debug.debug("pin character: "+index);
		Tooltip.hide();
		$('div.character-list').html("").addClass('loading-chars');

		var switchUrl = Core.baseUrl + mapStatic.urls.changeCharacter;
		debug.debug("switchUrl: "+switchUrl);
		
		$.ajax({
			type: 'POST',
			url: switchUrl,
			data: {
				index: index,
                realm: realm,
                csrf_token_name: Config.CSRF,
				is_json_ajax: 1
			},
			global: false,
			success: function(content) {
				var refreshUrl = switchUrl;

				// Take the user directly to the newly-selected character, don't wait for card update
				if (location.pathname.indexOf('/character/') != -1) {
					if (location.pathname.indexOf('/vault/') != -1)
						location.reload(true);
					else
						CharSelect.redirectTo(link.href);

					return;
				}

				// If homepage or account status, use those pages since they are unique
				if (location.pathname.indexOf('/ucp') >= 0)
					refreshUrl = Core.baseUrl +'/ucp';
				else if (location.pathname == Core.baseUrl +'/')
					refreshUrl = Core.baseUrl +'/';

				// Request new content or replace
				if (refreshUrl != switchUrl)
					CharSelect.pageUpdate(refreshUrl);
				else
					CharSelect.replace(content);
				$("#vote-active-char").html($(".context .context-user strong").html());	
			}
		});
		return;
	},

	/**
	 * Replace elements in the current page with fetched elements.
	 *
	 * @param content
	 */
	replace: function(content) {
	    var pageData = $((typeof content == 'string') ? content : content.content);
	    
        $('.ajax-update').each(function() {
            var self = $(this),
                target;
            
            if (self.attr('id')) {
                target = '#' + self.attr('id');
            } else {
                target = self.attr('class').replace('ajax-update', '').trim();
                target = '.' + target.split(' ')[0];
            }
            
            var clone = pageData.find(target + '.ajax-update').clone(),
                textarea = self.find('textarea');
            
            if (textarea.length && textarea.val().length) {
                CharSelect.textareaContent = textarea.val();
            }
            
            clone.find('textarea').val(CharSelect.textareaContent);
            self.replaceWith(clone);
        });
	    
		CharSelect.initialize();
		CharSelect.afterPageUpdate();
	},

	/**
	 * Update all the elements on the page after char selection.
	 *
	 * @param refreshUrl
	 * @param fallbackUrl
	 */
	pageUpdate: function(refreshUrl, fallbackUrl) {
		var ck = Date.parse(new Date()),
			refreshUrl = refreshUrl || location.href;

		if (Core.isIE() && refreshUrl == Core.baseUrl +'/') {
			location.href = location.pathname +'?reload='+ ck;
			return;
		}

		refreshUrl = refreshUrl + ((refreshUrl.indexOf('?') > -1) ? '&' : '?') +"cachekill="+ ck;

		$.ajax({
			url: refreshUrl,
			global: false,
			error: function(xhr) {
				if (fallbackUrl) {
					location.href = fallbackUrl;
				} else if (xhr.status == 404 && refreshUrl == null) {
					CharSelect.pageUpdate(Core.baseUrl + "/", fallbackUrl); // Attempt to get data from homepage
				} else {
					location.reload(true);
				}
			},
			success: function(data) {
				CharSelect.replace(data);
			}
		});
	},

	/**
	 * Trigger code after page update.
	 */
	afterPageUpdate: function() {

		// Redirect to the newly-selected character or guild
		var redirectTo;

		if (location.href.indexOf('/character/') != -1) {
			redirectTo = $('#user-plate a.character-name').attr('href');

		} else if (location.href.indexOf('/guild/') != -1) {
			redirectTo = $('#user-plate a.guild-name').attr('href');

			// Deal with guildless characters
			if (!redirectTo) {
				location.href = $('#user-plate a.character-name').attr('href');
				return;
			}
		}

		if (redirectTo)
			CharSelect.redirectTo(redirectTo);
	},

	/**
	 * Redirect to a URL.
	 *
	 * @param url
	 */
	redirectTo: function(url) {
		// Vault-secured pages only need to be refreshed
		if (url.indexOf('/vault/') != -1) {
			location.reload();
			return;
		}

		// Preserve current page
		var page = '';

		if (location.href.match(/\/(character|guild)\/.+?\/.+?\/(.+)$/)) {
			page = RegExp.$2;

			// Ignore pages that aren't always available
			$.each(['pet'], function() {
				if (page.indexOf(this) != -1) {
					page = '';
					return;
				}
			});
		}

		location.href = url + page;
	},

	/**
	 * Open and close the context menu.
	 *
	 * @param e
	 */
	toggle: function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		Toggle.open(e.currentTarget, "context-open", $(e.currentTarget).siblings('.ui-context'));
		return false;
	},

	/**
	 * Close the context menu.
	 *
	 * @param node
	 * @return boolean
	 */
	close: function(node) {
		$(node)
			.parents('.ui-context').hide()
			.siblings('.context-link').removeClass('context-open');

		return false;
	},

	/**
	 * Swipe between the char select panes.
	 *
	 * @param direction
	 * @param target
	 */
	swipe: function(direction, target) {
		var parent = $(target).parents('.chars-pane'),
			inDirection = (direction == 'in') ? 'left' : 'right',
			outDirection = (direction == 'in') ? 'right' : 'left';

		parent.hide('slide', { direction: inDirection }, 150, function() {
			parent.siblings('.chars-pane').show('slide', { direction: outDirection }, 150, function() {
				var scroll = $(this).find('.scrollbar-wrapper');

				if (scroll.length > 0)
					scroll.tinyscrollbar();
			});
		});
	},

	/**
	 * Filter down the character list.
	 *
	 * @param e
	 */
	filter: function(e) {
		Toggle.keepOpen = true;

		var target = $(e.srcElement || e.currentTarget),
			filterVal = target.val().toLowerCase(),
			filterTable = target.parents('.chars-pane').find('.overview');

		if (e.keyCode == KeyCode.enter)
			return;

		if (e.keyCode == KeyCode.esc)
			target.val('');

		if (target.val().length < 1) {
			filterTable.children('a').removeClass('filtered');
		} else {
			filterTable.children('a').each(
				function() {
				 	$(this)[($(this).text().toLowerCase().indexOf(filterVal) > -1) ? "removeClass" : "addClass"]('filtered');
				}
			);

			var allHidden = filterTable.children('a.filtered').length >= filterTable.children('a').length;
			filterTable.children('.no-results')[(allHidden) ? "show" : "hide"]();
		}

		var scroll = target.parents('.chars-pane:first').find('.scrollbar-wrapper');

		if (scroll.length > 0)
			scroll.tinyscrollbar();
	}
};

/**
 * Variables and functions for flash
 */
var Flash = {

    /**
     * Video player for this project
     */
    videoPlayer: '',

    /**
     * The flash base of the videos for this project
     */
    videoBase:   '',

    /**
     * Rating image based on locale
     */
    ratingImage: '',

    /**
     * Express install location
     */
    expressInstall: '/common/static/flash/expressInstall.swf',

    /**
     * Store values populated after load
     */
    initialize: function() {
         //set flash base and rating image
         Flash.defaultVideoParams.base          = Flash.videoBase;
         Flash.defaultVideoFlashVars.ratingpath = Flash.ratingImage;
    },

    /**
     * Default video params for the video player
     */
    defaultVideoParams: {
         allowFullScreen:   "true",
         bgcolor:           "#000000",
         allowScriptAccess: "always",
         wmode:             "opaque",
         menu:              "false"
    },

    /**
     * Default flash vars for videos
     */
    defaultVideoFlashVars: {
        ratingfadetime: "2",
        ratingshowtime: "1"
    }
};

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

/**
 * Mappings of keyboard key codes for all supported regions.
 *
 * @link http://unixpapa.com/js/key.html
 */
var KeyCode = {

	/**
	 * Convenience codes.
	 */
	backspace: 8,
	enter: 13,
	esc: 27,
	space: 32,
	tab: 9,
	arrowLeft: 37,
	arrowUp: 38,
	arrowRight: 39,
	arrowDown: 40,

	/**
	 * A map of all key codes.
	 *
	 * Supported: en, es, de, ru, ko (no changes), fr
	 */
	map: {
		global: {
			// 0-9 numbers (48-57) and numpad numbers (96-105)
			numbers: [48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105],

			// A-Z letters
			letters: [65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90],

			// Backspace, tab, enter, shift, ctrl, alt, caps, esc, num, space pup, pdown, end, home, ins, del
			controls: [8, 9, 13, 16, 17, 18, 20, 27, 33, 32, 34, 35, 36, 45, 46, 144],

			// Function (F keys)
			functions: [112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123],

			// Left, right, up, down, arrows
			arrows: [37, 38, 39, 40],

			// Windows, Mac specific buttons
			os: [17, 91, 92, 93, 219, 224]
		},
		de: {
			letters: [59, 192, 219, 222]
		},
		es: {
			letters: [59, 192]
		},
		ru: {
			letters: [59, 188, 190, 192, 219, 221, 222]
		},
		fr: {
			letters: [191]
		}
	},

	/**
	 * Get all the arrows codes.
	 *
	 * @param lang
	 * @return array
	 */
	arrows: function(lang) {
		return KeyCode.get('arrows', lang);
	},

	/**
	 * Get all the control codes.
	 *
	 * @param lang
	 * @return array
	 */
	controls: function(lang) {
		return KeyCode.get('controls', lang);
	},

	/**
	 * Get all the functions codes.
	 *
	 * @param lang
	 * @return array
	 */
	functions: function(lang) {
		return KeyCode.get('functions', lang);
	},

	/**
	 * Return a key code map.
	 *
	 * @param type
	 * @param lang
	 * @return mixed
	 */
	get: function(type, lang) {
		var map = [],
			types = [],
			lang = lang || Core.getLanguage();

		if (typeof type == 'string')
			types = [type];
		else
			types = type;

		for (var i = 0, l = types.length; i < l; ++i) {
			var t = types[i];

			if (!KeyCode.map.global[t])
				continue;

			map = map.concat(KeyCode.map.global[t]);

			if (KeyCode.map[lang] && KeyCode.map[lang][t])
				map = map.concat(KeyCode.map[lang][t]);
		}

		return map;
	},

	/**
	 * Validates an input to only accept letters and controls.
	 *
	 * @param code
	 * @param lang
	 * @return bool
	 */
	isAlpha: function(code, lang) {
		return ($.inArray(code, KeyCode.get(['letters', 'controls'], lang)) >= 0);
	},

	/**
	 * Validates an input to only accept letters, numbers and controls.
	 *
	 * @param code
	 * @param lang
	 * @return bool
	 */
	isAlnum: function(code, lang) {
		return ($.inArray(code, KeyCode.get(['letters', 'numbers', 'controls'], lang)) >= 0);
	},

	/**
	 * Validates an input to only accept numbers and controls.
	 *
	 * @param code
	 * @param lang
	 * @return bool
	 */
	isNumeric: function(code, lang) {
		return ($.inArray(code, KeyCode.get(['numbers', 'controls'], lang)) >= 0);
	},

	/**
	 * Get all the letter codes.
	 *
	 * @param lang
	 * @return array
	 */
	letters: function(lang) {
		return KeyCode.get('letters', lang);
	},

	/**
	 * Get all the number codes.
	 *
	 * @param lang
	 * @return array
	 */
	numbers: function(lang) {
		return KeyCode.get('numbers', lang);
	}

};

var BnetAds = {

	/**
	 * Load an ad from the marketing API.
	 *
	 * @param target
	 * @param size
	 */
	init: function(target, size){
		$.ajax({
			url: '/marketing/',
			data: {
				showText: true,
				locale: Core.formatLocale(2, '_'),
				size: size
			},
			dataType: 'html',
			success: function(data) {
				var dataBody = data.substring(data.indexOf('<body>'), data.indexOf('</body>')+7);

				$(target).find('.sidebar-content').html($(dataBody).html()).removeClass('loading');
			},
			error: function() {
				$(target).remove();
			},
			cache: false,
			global: false
		});
	},

	/**
	 * Bind ad tracking.
	 *
	 * @param query
	 * @param category
	 * @param action
	 */
	bindTracking: function(query, category, action) {
		$(query).click(function() {
			try {
				_gaq.push([
					'_trackEvent',
					category,
					action,
					$(this).data('ad') +' ['+ Core.locale +']'
				]);
			} catch (e) {}
		});
	},

	/**
	 * Track a page impression / view.
	 *
	 * @param category
	 * @param action
	 * @param label
	 */
	trackImpression: function(category, action, label) {
		try {
			_gaq.push([
				'_trackEvent',
				category,
				action,
				label +' ['+ Core.locale +']'
			]);
		} catch (e) {}
	},

	/**
	 * Track a loaded battle.net ad.
	 *
	 * @param id
	 * @param title
	 * @param ref
	 * @param clickEvent
	 */
	trackEvent: function(id, title, ref, clickEvent) {
		try {
			ref = (ref) ? ref +' - ' : '';

			_gaq.push([
				'_trackEvent',
				'Ad Service',
				(clickEvent) ? 'Ad Click-Throughs' : 'Ad Impressions',
				'Ad '+ encodeURIComponent(title.replace(' ', '_')) +' - '+ ref + Core.locale +' - '+ id
			]);
		} catch (e) {}
	}
};

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

/**
 * Load asynchronously.
 */
UserAgent.initialize();

/**
 * Prototype overwrites.
 */
String.prototype.trim = function() {
	return $.trim(this);
};

/**
 * Simple JavaScript Inheritance
 * By John Resig http://ejohn.org/
 * MIT Licensed.
 */
(function() {
	var initializing = false,
		fnTest = /xyz/.test(function(){xyz;}) ? /\b_super\b/ : /.*/;

	// The base Class implementation (does nothing)
	this.Class = function() {};

	// Create a new Class that inherits from this class
	Class.extend = function(prop) {
		var _super = this.prototype;

		// Instantiate a base class (but only create the instance, don't run the init constructor)
		initializing = true;
		var prototype = new this();
		initializing = false;

		// Copy the properties over onto the new prototype
		for (var name in prop) {
			// Check if we're overwriting an existing function
			prototype[name] =
				(typeof prop[name] == "function" && typeof _super[name] == "function" && fnTest.test(prop[name]))
			?
				(function(name, fn) {
					return function() {
						var tmp = this._super;

						// Add a new ._super() method that is the same method
						// but on the super-class
						this._super = _super[name];

						// The method only need to be bound temporarily, so we
						// remove it when we're done executing
						var ret = fn.apply(this, arguments);
						this._super = tmp;

						return ret;
					};
				})(name, prop[name])
			:
				prop[name];
		}

		// The dummy class constructor
		function Class() {
			// All construction is actually done in the init method
			if (!initializing && this.init)
				this.init.apply(this, arguments);
		}

		// Populate our constructed prototype object
		Class.prototype = prototype;

		// Enforce the constructor to be what we expect
		Class.constructor = Class;

		// And make this class extendable
		Class.extend = arguments.callee;

		return Class;
	};
})();

/**
 * Setup ajax calls.
 */
$.ajaxSetup({
	error: function(xhr) {
		if (xhr.readyState != 4)
			return false;

		if (xhr.getResponseHeader("X-App") == "login") {
			location.reload(true);
			return false;
		}

		if (xhr.status) {
			switch (xhr.status) {
				case 301:
				case 302:
				case 307:
				case 403:
				case 404:
				case 500:
				case 503:
					//location.reload(true);
					return false;
				break;
			}
		}

		return true;
	}
});

$(function() {
	Wow.initialize();
	//Fansite.initialize();
});

var Wow = {

	/**
	 * Initialize all wow tooltips.
	 *
	 * @constructor
	 */
	initialize: function() {
		setTimeout(function() {
		/*
		    Wow.bindTooltips('achievement');
    		Wow.bindTooltips('spell');
    		Wow.bindTooltips('quest');
    		Wow.bindTooltips('currency');
    		Wow.bindTooltips('zone');
    		Wow.bindTooltips('faction');
    		Wow.bindTooltips('npc');
    		Wow.bindItemTooltips();
    		Wow.bindCharacterTooltips();
    		Wow.initNewFeatureTip();
		*/
		}, 1);
	},

	/**
	 * Display or hide the video.
	 */
	toggleInterceptVideo: function() {
		$("#video, #blackout, #play-trailer").toggle();
		return false;
	},

	/**
	 * Bind item tooltips to links.
	 * Gathers the item ID from the href, and the optional params from the data-item attribute.
	 */
	bindItemTooltips: function() {
		Tooltip.bind('a[href*="/item/"], [data-item]', false, function() {
			if (this.rel == 'np')
				return;

			var self = $(this),
				id,
				query;

			if (this.href !== null) {
				if (this.href == 'javascript:;' || this.href.indexOf('#') == 0)
					return;

				var data = self.data('item');
				var	href = self.attr('href');
				href = href.replace(Core.baseUrl,"").split('/item/');

				id = parseInt(href[1]);
				query = (data) ? '/?'+ data : "";

			} else {
				id = parseInt(self.data('item'));
				query = '';
			}

			if (id && id > 0)
				Tooltip.show(this, '/tooltip/item/'+ id + query, true);
		});
	},

	/**
	 * Bind character tooltips to links.
	 * Add rel="np" to disable character tooltips on links.
	 */
	bindCharacterTooltips: function() {
		Tooltip.bind('a[href*="/character/"]', false, function() {
			if (this.href == 'javascript:;' || this.href.indexOf('#') == 0 || this.rel == 'np' || this.href.indexOf('/vault/') != -1)
				return;

			var href = $(this).attr('href').replace(Core.baseUrl +'/character/', "").split('/');

			if (location.href.toLowerCase().indexOf('/'+ href[1].toLowerCase() +'/') != -1 && this.rel != 'allow')
				return;

			Tooltip.show(this, '/tooltip/character/'+ encodeURIComponent(href[2])+'/'+ encodeURIComponent(href[3])+'/', true);
		});
	},

	/**
	 * Bind a tooltip to a specific wiki type.
	 *
	 * @param type
	 */
	bindTooltips: function(type) {
		Tooltip.bind('[data-'+ type +']', false, function() {
			if (this.rel == 'np')
				return;

			var data = $(this).data(type);

			if (typeof data != 'undefined')
				Tooltip.show(this, '/tooltip/'+ type +'/'+ data, true);
		});
	},

	/**
	 * Update the events within the sidebar.
	 *
	 * @param id
	 * @param status
	 */
	updateEvent: function(id, status) {
		$('#event-'+ id +' .actions').fadeOut('fast');

		$.ajax({
			url: $('.profile-link').attr('href') +'event/'+ status,
			data: { eventId: id },
			dataType: "json",
			success: function(data) {
				$('#event-'+ id).fadeOut('fast', function() {
					$(this).remove();
				});
			}
		});

		return false;
	},

	/**
	 * Load the browse.json data and display the dropdown menu.
	 *
	 * @param node
	 * @param url
	 */
	browseArmory: function(node, url) {
		if ($('#menu-tier-browse').is(':visible'))
			return;

		Menu.load('browse', url);
		Menu.show(node, '/', { set: 'browse' });
	},

	/**
	 * Creates the html nodes for basic tooltips.
	 *
	 * @param title
	 * @param description
	 * @param icon
	 */
	createSimpleTooltip: function(title, description, icon) {

		var $tooltip = $('<ul/>');

		if (icon) {
			$('<li/>').append(Wow.Icon.framedIcon(icon, 56)).appendTo($tooltip);
		}

		if (title) {
			$('<li/>').append($('<h3/>').text(title)).appendTo($tooltip);
		}

		if (description) {
			$('<li/>').addClass('color-tooltip-yellow').html(description).appendTo($tooltip);
		}

		return $tooltip;
	},

	/**
	 * Add new BML commands to the editor.
	 */
	addBmlCommands: function() {
		BML.addCommands([
			{
				type: 'item',
				tag: 'item',
				filter: true,
				selfClose: true,
				prompt: Msg.bml.itemPrompt,
				pattern: [
					'\\[item="([0-9]{1,5})"\\s*/\\]'
				],
				result: [
					'<a href="'+ Core.baseUrl +'/item/$1">'+ Core.host + Core.baseUrl +'/item/$1</a>'
				]
			}
		]);
	},

	initNewFeatureTip: function() {
		Core.showUntilClosed('#feature-tip', 'wow-feature-mop', {
			endDate: '2011/11/02',
			fadeIn: 333,
			trackingCategory: 'New Feature Tip',
			trackingAction: 'Mists of Pandaria'
		});
	}

};

Wow.Icon = {

	/**
	 * Generate icon path.
	 *
	 * @param name
	 * @param size
	 */
	getUrl: function(name, size) {
		return Core.cdnUrl +'/images/icons/'+ size +'/'+ name +'.jpg';
	},

	/**
	 * Create frame icon markup.
	 *
	 * @param name
	 * @param size
	 */
	framedIcon: function(name, size) {
		var iconSize = 56;

		if (size <= 18)
			iconSize = 18;
		else if (size <= 36)
			iconSize = 36;

		var $icon = $('<span/>').addClass('icon-frame frame-' + size);

		if (size == 18 || size == 36 || size == 56) {
			$icon.css('background-image', 'url(' + Wow.Icon.getUrl(name, iconSize) + ')');
		} else {
			$icon.append($('<img/>').attr({
				width: size,
				height: size,
				src: Wow.Icon.getUrl(name, iconSize)
			}));
		}

		return $icon;
	}

};

/**
 * 3rd-party fansite integration.
 */
var Fansite = {

	/**
	 * Map of sites and available URLs.
	 */
	sites: {
		arsenal: {
			name: 'Alte Armory',
			site: 'http://arsenal.wow-alive.de/',
			regions: ['eu'],
			locales: ['de', 'es', 'fr', 'ru'],
			urls: {
				achievement: ['achievements', 'achievement={0}'],
				character: ['profiles', function(params) {

					var region = params[1].toLowerCase();
					var realm = params[3].toLowerCase();
					realm = realm.replace(/( )+/g, '-')
					realm = realm.replace(/^A-Z/ig, '')
					var name = params[2].toLowerCase();

					return 'character-sheet.php?r=' + encodeURIComponent(realm) + '&cn=' + encodeURIComponent(name);
				}],
				faction: ['factions', 'faction={0}'],
				'class': ['classes', 'class={0}'],
				object: ['objects', 'object={0}'],
				skill: ['skills', 'skill={0}'],
				race: ['races', 'race={0}'],
				quest: ['quests', 'quest={0}'],
				spell: ['spells', 'spell={0}'],
				event: ['events', 'event={0}'],
				title: ['titles', 'title={0}'],
				zone: ['zones', 'zone={0}'],
				item: ['items', 'item={0}'],
				npc: ['npcs', 'npc={0}'],
				pet: ['pets', 'pet={0}']
			}
		},
		wowhead: {
			name: 'Wowhead',
			site: 'http://www.wowhead.com/',
			regions: ['us', 'eu'],
			locales: ['de', 'es', 'fr', 'ru'],
			urls: {
				achievement: ['achievements', 'achievement={0}'],
				character: ['profiles', function(params) {

					var region = params[1].toLowerCase();
					var realm = params[3].toLowerCase();
					realm = realm.replace(/( )+/g, '-')
					realm = realm.replace(/^A-Z/ig, '')
					var name = params[2].toLowerCase();

					return 'profile='+ encodeURIComponent(region) + '.' + encodeURIComponent(realm) + '.' + encodeURIComponent(name);
				}],
				faction: ['factions', 'faction={0}'],
				'class': ['classes', 'class={0}'],
				object: ['objects', 'object={0}'],
				skill: ['skills', 'skill={0}'],
				race: ['races', 'race={0}'],
				quest: ['quests', 'quest={0}'],
				spell: ['spells', 'spell={0}'],
				event: ['events', 'event={0}'],
				title: ['titles', 'title={0}'],
				zone: ['zones', 'zone={0}'],
				item: ['items', 'item={0}'],
				npc: ['npcs', 'npc={0}'],
				pet: ['pets', 'pet={0}']
			}
		},
		wowpedia: {
			name: 'Wowpedia',
			site: 'http://www.wowpedia.org/',
			regions: ['us', 'eu'],
			locales: ['fr', 'es', 'de', 'ru', 'it'],
			domains: {
				ru: 'http://wowpedia.ru/wiki/',
				de: 'http://de.wow.wikia.com/wiki/',
				it: 'http://it.wow.wikia.com/wiki/'
			},
			urls: {
				faction: ['Factions', '{1}'],
				'class': ['Classes', '{1}'],
				skill: ['Professions', '{1}'],
				race: ['Races', '{1}'],
				zone: ['Zones', '{1}'],
				item: ['Items', '{1}'],
				pet: ['Pets', '{1}'],
				npc: ['NPCs', '{1}']
			},
			buildUrl: function(params) {
				return params[2].replace(/\s+/g, '_').replace(/"/ig, '&quot;');
			}
		},
		buffed: {
			name: 'Buffed.de',
			site: 'http://wowdata.buffed.de/',
			regions: ['eu'],
			locales: ['de'],
			urls: {
				achievement: ['', '?a={0}'],
				faction: ['faction/', '?faction={0}'],
				'class': ['class/portal', 'class/portal/{0}'],
				skill: ['', 'spell/profession/{0}'],
				spell: ['', '?s={0}'],
				title: ['title/list', 'title/list'],
				quest: ['quest/list/1/', '?q={0}'],
				item: ['item/list', '?i={0}'],
				zone: ['zone/list/1/', '?zone={0}'],
				npc: ['', '?n={0}']
			}
		},
	},

	/**
	 * Map of content types and available sites for that type.
	 */
	map: {
		achievement: ['wowhead', 'buffed'],
		character: ['arsenal'],
		faction: ['wowhead', 'wowpedia', 'buffed'],
		'class': ['wowhead', 'wowpedia', 'buffed'],
		object: ['wowhead'],
		skill: ['wowhead', 'wowpedia', 'buffed'],
		quest: ['wowhead', 'buffed'],
		spell: ['wowhead', 'buffed'],
		event: ['wowhead'],
		title: ['wowhead', 'buffed'],
		arena: [],
		guild: [],
		zone: ['wowhead', 'wowpedia', 'buffed'],
		item: ['wowhead', 'wowpedia', 'buffed'],
		race: ['wowhead', 'wowpedia'],
		npc: ['wowhead', 'wowpedia', 'buffed'],
		pet: ['wowhead', 'wowpedia'],
		pvp: []
	},

	/**
	 * Create the menu HTML and delegate link events.
	 *
	 * @constructor
	 */
	initialize: function() {
		if (Fansite.initialized) {
			return;
		}

		Fansite.initialized = true;

		$(document)
			.delegate('a[data-fansite]', 'mouseenter.fansite', Fansite.onMouseOver)
			.delegate('a[data-fansite]', 'mouseleave.fansite', ContextMenu.delayedHide);
	},

	onMouseOver: function() {
		var node = $(this),
			params = Fansite.read(node.data('fansite'));

		Fansite.openMenu(node, params);
			return false;
	},

	/**
	 * Split params the awesome way!
	 *
	 * @param data
	 * @return array
	 */
	read: function(data) {
		return data.split('|');
	},

	/**
	 * Generate links from params.
	 *
	 * @param params
	 * @return array
	 */
	createLinks: function(params) {
		var type = params[0],
			map = Fansite.map[type],
			links = [],
			lang = Core.getLanguage();

		if (map.length > 0) {
			var site, url, urls;

			for (var i = 0, len = map.length; i < len; ++i) {
				if (!Fansite.sites[map[i]])
					continue;

				site = Fansite.sites[map[i]];

				if (
					((lang != 'en') && ($.inArray(lang, site.locales) < 0)) ||
					($.inArray(Core.buildRegion, site.regions) < 0) || 
					!site.urls[type]
				) {
					continue;
				}

				url = Fansite.createUrl(site),
				urls = site.urls[type];

				if (params.length <= 1) {
					url += urls[0];
				} else {
					if (typeof site.buildUrl == 'function') {
						url += site.buildUrl(params);
					} else {
						var urlPattern = urls[1];
						
						if (typeof urlPattern == 'function') {
							url += urlPattern(params);
						} else {
							for (var j = 1; j < params.length; ++j) {
								urlPattern = urlPattern.replace('{' + (j - 1) + '}', encodeURIComponent(params[j]));
							}
							url += urlPattern;
						}
					}
				}

				links.push('<a href="'+ url +'" target="_blank">'+ site.name +'</a>');
			}
		}

		return links;
	},

	/**
	 * Create the URL based on locale.
	 *
	 * @param site
	 * @return string
	 */
	createUrl: function(site) {
		var url = site.site,
			lang = Core.getLanguage();

		if ($.inArray(lang, site.locales) >= 0) {
			if (site.domains && site.domains[lang])
				url = site.domains[lang];
			else
			url = url.replace('www', lang);
		}

		return url;
	},

	/**
	 * Open up the menu and show the available sites for that type.
	 *
	 * @param node
	 * @param params
	 */
	openMenu: function(node, params) {
		Fansite.node = node;

		var list = $('<ul/>');
		var links = Fansite.createLinks(params);

		var title = '';

		if (links.length == 0) {
			title = Msg.ui.fansiteNone;
		} else {
			if (Msg.fansite[params[0]]) {
				title = Msg.ui.fansiteFindType.replace('{0}', Msg.fansite[params[0]]);
			} else {
				title = Msg.ui.fansiteFind;
			}
		}

		$('<li/>')
			.addClass('divider')
			.html('<span>' + title + '</span>')
			.appendTo(list);

		if (links.length > 0) {
			for (var i = 0, length = links.length; i < length; ++i) {
				$('<li/>').append(links[i]).appendTo(list);
			}

			// Also linkify the button itself if there's only 1 fansite
			if (links.length == 1) {
				node.attr('href', $(links[0]).attr('href'));
				node.attr('target', '_blank');
			}
		}

		ContextMenu.show(node, list);
	},

	/**
	 * Generate links for inline display.
	 *
	 * @param target
	 * @param data
	 */
	generate: function(target, data) {
		var links = Fansite.createLinks(Fansite.read(data));

		$(target).html(links.join(' ')).addClass('fansite-group');
	}

};

/* Show a custom contextual menu at the desired location */
var ContextMenu = {

	DELAY_HIDE: 333,

	// DOM
	object: null,
	node: null,
	parentNode: null,
	cb: null,

	initialize: function() {

		if(ContextMenu.object != null) {
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

		if(ContextMenu.parentNode != null) {
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

		if(ContextMenu.parentNode != null) {
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
/**
 * Dynamically create tooltips, append specific content from different medians, and display at certain positions.
 *
 * @copyright   2010, Blizzard Entertainment, Inc
 * @class       Tooltip
 * @requires    Page
 * @example
 *
 *      onmouseover="Tooltip.show(this, 'This is the tooltip text!');"
 *
 */

var Tooltip = {

    /**
     * The current tooltip object and its markup
     */
    wrapper: null,

    /**
     * Content within the tooltip
     */
    contentCell: null,

    /**
     * Cached results from the AJAX responses
     */
    cache: {},

    /**
     * Flag storing intialization status of tooltip
     */
    initialized: false,

	/**
	 * Is the mouse currently hovering over the node?
	 */
	currentNode: null,

	/**
	 * Is the tooltip visible?
	 */
	visible: false,

	/**
	 * Default options
	 */
	options: {
		ajax: false,
		className: false,
		location: 'topRight',
		useTable: false
	},

    /**
	 * Max tooltip width for IE6.
	 */
	maxWidth: 250,

    /**
     * Initialize the tooltip markup and append it to document.
     *
     * @constructor
     */
    initialize: function() {
		var tooltipDiv = $('<div/>').addClass('ui-tooltip').appendTo("body");

		if (Core.isIE(6) && document.location.protocol === 'http:') {
			$('<iframe/>', {
				src: 'javascript:void(0);',
				frameborder: 0,
				scrolling: 'no',
				marginwidth: 0,
				marginheight: 0
			}).addClass('tooltip-frame').appendTo('body');
		}

		if (!Tooltip.options.useTable) {
			Tooltip.contentCell = $('<div/>').addClass('tooltip-content').appendTo(tooltipDiv);

		} else {
			var tooltipTable = $("<table>", {
				cellspacing: 0,
				cellpadding: 0
			}).appendTo(tooltipDiv);

			var emptyCell = $('<td>').attr("valign", "top").text(" "),
				emptyRow = $('<tr>'),
				contentCell = emptyCell.clone();

			tooltipTable
				.append(
					emptyRow.clone()
						.append(emptyCell.clone().addClass("top-left"))
						.append(emptyCell.clone().addClass("top-center"))
						.append(emptyCell.clone().addClass("top-right"))
				)
				.append(
					emptyRow.clone()
						.append(emptyCell.clone().addClass("middle-left"))
						.append(contentCell.addClass("middle-center"))
						.append(emptyCell.clone().addClass("middle-right"))
				)
				.append(
					emptyRow.clone()
						.append(emptyCell.clone().addClass("bottom-left"))
						.append(emptyCell.clone().addClass("bottom-center"))
						.append(emptyCell.clone().addClass("bottom-right"))
				);

			Tooltip.contentCell = contentCell;
		}

        // Assign to reference later
        Tooltip.wrapper = tooltipDiv;
        Tooltip.initialized = true;
    },

	/**
	 * Bind a mouse over to all tooltips in the page. Will only display the title of the element.
	 * Will first detect data-tooltip and then the tooltip attribute.
	 *
	 * @param query
	 * @param options
	 * @param callback
	 */
	bind: function(query, options, callback) {
		var doc = $(document),
			func;

		if (Core.isCallback(callback)) {
			func = callback;
		} else {
			func = function() {
				var self = $(this),
					title = self.data('tooltip') || this.title;

				if (title && self.attr('rel') != 'np') {
					Tooltip.show(this, title, self.data('tooltip-options') || options);
				}
			};
		}

		doc.undelegate(query, 'mouseover.tooltip', func);
		doc.delegate(query, 'mouseover.tooltip', func);
	},

    /**
     * Grab the content for the tooltip, then pass it on to be positioned.
     *
     * @param node
     * @param content
     * @param options - className, ajax, location
     */
    show: function(node, content, options) {
		if (!Tooltip.wrapper)
			Tooltip.initialize();

		if (options === true)
			options = { ajax: true };
		else
			options = options || {};

		options = $.extend({}, Tooltip.options, options);

		Tooltip.currentNode = node = $(node);

		// Update trigger node
        node.mouseout(function() {
        	Tooltip.hide();

			if (options.className)
				Tooltip.wrapper.removeClass(options.className);
        });

		// Update values
		if (!Tooltip['_'+ options.location])
			options.location = Tooltip.options.location;

		// Left align tooltips in the right half of the screen
		if (options.location == Tooltip.options.location && node.offset().left > $(window).width() / 2)
			options.location = 'topLeft';

		if (options.className)
			Tooltip.wrapper.addClass(options.className);

		// Content: DOM node created w/ jQuery
		if (typeof content === 'object') {
			Tooltip.position(node, content, options.location);

		} else if (typeof content === 'string') {

			// Content: AJAX
			if (options.ajax) {
				if (Tooltip.cache[content]) {
					Tooltip.position(node, Tooltip.cache[content], options.location);
				} else {
					var url = content;
					
					if(url.indexOf("arsenal.wow-alive.de") > 0){
					}
					else if (url.indexOf(Core.projectUrl) != 0) { // Add base URL when provided URL doesn't begin with project URL (e.g. /d3)
						url = Core.baseUrl + content;
					}

					$.ajax({
						type: "GET",
						url: url,
						dataType: "html",
						global: false,
						beforeSend: function() {
							// Show "Loading..." tooltip when request is being slow
							setTimeout(function() {
								if (!Tooltip.visible)
									Tooltip.position(node, Msg.ui.loading, options.location);
							}, 500);
						},
						success: function(data) {
							if (Tooltip.currentNode == node) {
								Tooltip.cache[content] = data;
								Tooltip.position(node, data, options.location);
							}
						},
						error: function(xhr) {
							if (xhr.status != 200)
								Tooltip.hide();
						}
					});
				}

			// Content: Copy content from the specified DOM node (referenced by ID)
			} else if (content.substr(0, 1) === '#') {
				Tooltip.position(node, $(content).html(), options.location);

			// Content: Text
			} else {
				Tooltip.position(node, content, options.location);
			}
		}
    },

    /**
     * Hide the tooltip.
     */
	hide: function() {
		if (!Tooltip.wrapper)
			return;

		if (Core.isIE(6)) {
			$('.tooltip-frame').hide();
			Tooltip.wrapper.removeAttr('style');
		}

		Tooltip.wrapper.hide();
		Tooltip.wrapper.unbind('mousemove.tooltip');

		Tooltip.currentNode = null;
		Tooltip.visible = false;
	},

    /**
     * Position the tooltip at specific coodinates.
     *
     * @param node
     * @param content
	 * @param location
     */
    position: function(node, content, location) {
		if (!Tooltip.currentNode)
			return;

		if (typeof content == 'string')
	        Tooltip.contentCell.html(content);
		else
			Tooltip.contentCell.empty().append(content);

        var width = Tooltip.wrapper.outerWidth(),
			height = Tooltip.wrapper.outerHeight();

		if (Core.isIE(6) && width > Tooltip.maxWidth)
			width = Tooltip.maxWidth;

		var coords = Tooltip['_' + location](width, height, node);

		if (coords)
			Tooltip.move(coords.x, coords.y, width, height);
    },

	/**
	 * Move the tooltip around.
	 *
	 * @param x
	 * @param y
	 * @param w
	 * @param h
	 */
	move: function(x, y, w, h) {
		Tooltip.wrapper
			.css("left", x +"px")
			.css("top",  y +"px")
			.show();

		Tooltip.visible = true;

		if (Core.isIE(6)) {
			$('.tooltip-frame').css({
				width: w + 60,
				height: h,
				left: (x - 60) +"px",
				top: y +"px"
			}).fadeTo(0, 0).show();

			Tooltip.wrapper.css('width', w);
		}
	},

	/**
	 * Position at the mouse cursor.
	 *
	 * @param width
	 * @param height
	 * @param node
	 */
	_mouse: function(width, height, node) {
		node.unbind('mousemove.tooltip').bind('mousemove.tooltip', function(e) {
			Tooltip.move((e.pageX + 10), (e.pageY + 10), width, height);
		});
	},

	/**
	 * Position at the top left.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_topLeft: function(width, height, node) {
		var offset = node.offset(),
			x = offset.left - width,
			y = offset.top - height;

		return Tooltip._checkViewport(x, y, width, height, node);
	},

	/**
	 * Position at the top center.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_topCenter: function(width, height, node) {
		var offset = node.offset(),
			nodeWidth = node.outerWidth(),
			x = offset.left + ((nodeWidth / 2) - (width / 2)),
			y = offset.top - height - 5;

		return Tooltip._checkViewport(x, y, width, height, node);
	},

	/**
	 * Position at the top right.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_topRight: function(width, height, node) {
		var offset = node.offset(),
			nodeWidth = node.outerWidth(),
			x = offset.left + nodeWidth,
			y = offset.top - height;

		return Tooltip._checkViewport(x, y, width, height, node);
	},

	/**
	 * Position at the middle left.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_middleLeft: function(width, height, node) {
		var offset = node.offset(),
			nodeHeight = node.outerHeight(),
			x = offset.left - width,
			y = (offset.top + (nodeHeight / 2)) - (height / 2);

		return Tooltip._checkViewport(x, y, width, height, node);
	},

	/**
	 * Position at the middle right.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_middleRight: function(width, height, node) {
		var offset = node.offset(),
			nodeWidth = node.outerWidth(),
			nodeHeight = node.outerHeight(),
			x = offset.left + nodeWidth,
			y = (offset.top + (nodeHeight / 2)) - (height / 2);

		return Tooltip._checkViewport(x, y, width, height, node);
	},

	/**
	 * Position at the bottom left.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_bottomLeft: function(width, height, node) {
		var offset = node.offset(),
			nodeHeight = node.outerHeight(),
			x = offset.left - width,
			y = offset.top + nodeHeight;

		return Tooltip._checkViewport(x, y, width, height, node);
	},

	/**
	 * Position at the bottom center.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_bottomCenter: function(width, height, node) {
		var offset = node.offset(),
			nodeWidth = node.outerWidth(),
			nodeHeight = node.outerHeight(),
			x = offset.left + ((nodeWidth / 2) - (width / 2)),
			y = offset.top + nodeHeight + 5;

		return Tooltip._checkViewport(x, y, width, height, node);
	},

	/**
	 * Position at the bottom right.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_bottomRight: function(width, height, node) {
		var offset = node.offset(),
			nodeWidth = node.outerWidth(),
			nodeHeight = node.outerHeight(),
			x = offset.left + nodeWidth,
			y = offset.top + nodeHeight;

		return Tooltip._checkViewport(x, y, width, height, node);
	},

	/**
	 * Makes sure the tooltip appears within the viewport.
	 *
	 * @param x
	 * @param y
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_checkViewport: function(x, y, width, height, node) {
		var offset = node.offset();

		// Greater than x viewport
		if ((x + width) > Page.dimensions.width)
			x = Page.dimensions.width - width;
			//x = (offset.left - width);

		// Less than x viewport
		if (x < 0)
			x = 15;

		// Greater than y viewport
		if ((y + height) > (Page.scroll.top + Page.dimensions.height))
			y = y - ((y + height) - (Page.scroll.top + Page.dimensions.height));

		// Node on top of viewport scroll
		else if ((offset.top - 100) < Page.scroll.top)
			y = offset.top + node.outerHeight();

		// Less than y viewport scrolled
		else if (y < Page.scroll.top)
			y = Page.scroll.top + 15;

		// Less than y viewport
		if (y < 0)
			y = 15;

		return {
			x: x,
			y: y
		};
	}

};

// Set data-tooltip binds globally
$(function() {
	Tooltip.bind('[data-tooltip]');
});