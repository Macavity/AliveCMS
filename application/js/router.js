var Router = {

	loadedJS: [],
	loadedCSS: [],
	first: true,
	page: false,

	/**
	 * Assign click events
	 */
	initialize: function()
	{
	    debug.debug("Router.initialize()");
	    
		// Check for pushState support
		if(history.pushState)
		{
			// Assign AJAX loading behavior to all our internal links
			$("a[href*='" + Config.URL + "']").each(function()
			{
				// Make sure it has not been assigned already
				if(typeof $(this).data('events') == "undefined" && $(this).attr("target") != "_blank")
				{
					// Add the event listener
					$(this).click(function(event)
					{
						// Indicate the loading
						$("body").css("cursor", "wait");

						// Get the link
						var href = $(this).attr("href");
						var direct = $(this).attr("direct");

						// Load it via AJAX
						Router.load(href, direct);

						// Add it to the history object
						history.pushState('', 'New URL: ' + href, href);

						// Prevent it from refreshing the whole page
						event.preventDefault();
					});
				}
			});
		}
	},

	/**
	 * Load the link into the content area
	 * @param String link
	 */
	load: function(link, direct)
	{
	    
		if(Router.first)
		{
			Router.first = false;

			// Make it load the page if they press back or forward
			$(window).bind('popstate', function()
			{
				Router.load(location.pathname, 0);
			});
		}
			
		Router.page = link;

		$("#tooltip").hide();

		if(/logout/.test(link))
		{
			window.location = link;
		}
		else if(/admin/.test(link))
		{
			window.location = link;
		}
		else if(direct == "1")
		{
			window.location = link;
		}
		else
		{
			// Load the page
			$.post(link, { is_json_ajax: "1", csrf_token_name: Config.CSRF }, function(data)
			{
				if(Router.page == link)
				{
					window.scrollTo(0, 0);
					
					try
					{
						data = JSON.parse(data);
					}
					catch(error)
					{
						data = {
							title: "Error",
							content: "Something went wrong!<br /><br /><b>Technical data:</b> " + data,
							js: null,
							css: null,
							slider: false
						};
					}

					// Change the cursor back to normal
					$("body").css("cursor", "default");

					// Change the content
					$("#content_ajax").html(data.content);
					
					Tooltip.refresh();

					// Change the title
					$("title").html(data.title);

					// Make sure to assign the router to all new internal links
					Router.initialize();
					
					// Initialize Slider elements
					if(typeof Slider != "undefined"){
					    Slider.initialize();
					}

					// Add the CSS if it exists and hasn't been loaded already
					if(data.css.length > 0 && $.inArray(data.css, Router.loadedCSS) == -1)
					{
						Router.loadedCSS.push(data.css);

						$("head").append('<link rel="stylesheet" type="text/css" href="' + Config.URL + 'application/' + data.css + '" />');
					}

					// Add the JS if it exists and hasn't been loaded already
					if(data.js.length > 0 && $.inArray(data.js, Router.loadedJS) == -1)
					{
						Router.loadedJS.push(data.js);

						require([Config.URL + "application/" + data.js]);
					}

					if(data.slider)
					{
						$("#" + Config.Slider.id).show();
					}
					else
					{
						$("#" + Config.Slider.id).hide();
					}
				}
			}).error(function()
			{
				if(Router.page == link)
				{
					$("body").css("cursor", "default");
					$("title").html("FusionCMS");
					UI.alert("The page could not be loaded");
				}
			});
		}
	}
}

$(document).ready(function() {
    /*Router.initialize();*/
});
