/**
 * @package FusionCMS
 * @version 6.X
 * @author Jesper Lindström
 * @author Xavier Geernick
 * @link http://raxezdev.com/fusioncms
 */

function UI()
{
	/**
	 * Initializing actions
	 */
	this.initialize = function()
	{
		// Is the image slider enabled?
		if($("#slider").length > 0)
		{
			UI.slider();
		}

		// Is the vote reminder enabled?
		if(Config.voteReminder)
		{
			UI.voteReminder();
		}

		// Give older browsers some html5-placeholder love!
		$('input[placeholder], textarea[placeholder]').placeholder();

		if(Config.cookieLaw)
		{
			var allowCookies = getCookie("allowCookies");

			if(!allowCookies)
			{
				UI.confirm("This website makes use of cookies to be able to keep track of who is who, and we are due to the new <a style='margin:0px;float:none;display:inline;color:green;padding:0px;' href='http://www.youtube.com/watch?v=arWJA0jVPAc' target='_blank'>EU cookie law</a> forced to ask for the visitors permission to use it", "Accept", function()
				{
					setCookie("allowCookies", 1, 365);
				});
			}
		}
	}

	/**
	 * Display the vote reminder popup
	 */
	this.voteReminder = function()
	{
		// Show box
		$("#popup_bg").fadeTo(200, 0.5);
		$("#vote_reminder").fadeTo(200, 1);

		// Assign hide-function to background
		$("#popup_bg").bind('click', function()
		{
			UI.hidePopup();
		});
	}

	/**
	 * Initialize the image slider
	 */
	this.slider = function()
	{
		var config = {
			autoplay: true,
			controls: true,
			captions: true,
			delay: Config.Slider.interval
		};

		if(Config.Slider.effect.length > 0)
		{
			config.transitions = new Array(Config.Slider.effect);
		}

		window.myFlux = new flux.slider('#slider', config);
	}

	/**
	 * Shows an alert box
	 * @param String message
	 */
	this.alert = function(question, time)
	{
		if(question.length == 0)
		{
			question = '<img src="http://img-cache.cdn.gaiaonline.com/c57f77cb596aae50b0725174b806e3ee/http://i1243.photobucket.com/albums/gg544/luzcyfer/Meme/okay-meme-1.jpg" />';
		}
		
		// Put question and button text
		$("#alert_message").html(question);

		// Show box
		$("#popup_bg").fadeTo(200, 0.5);
		$("#alert").fadeTo(200, 1);

		if(typeof time == "undefined")
		{
			$("#alert_message").css({marginBottom:"10px"});
			$(".popup_links").show();

			// Assign click event
			$("#alert_button").bind('click', function()
			{
				UI.hidePopup();	
			});
		}
		else
		{
			$("#alert_message").css({marginBottom:"0px"});
			$(".popup_links").hide();

			setTimeout(function()
			{
				UI.hidePopup();
			}, time);
		}

		// Assign hide-function to background
		$("#popup_bg").bind('click', function()
		{
			UI.hidePopup();
		});

		// Assign key events
		$(document).keypress(function(event)
		{
			// If "enter"
			if(event.which == 13)
			{
				UI.hidePopup();
			}
		});
	}

	/**
	 * Shows a confirm box
	 * @param String question
	 * @param String button
	 * @param Function callback
	 */
	this.confirm = function(question, button, callback, callback_cancel)
	{
		$(".popup_links").show();
		
		// Put question and button text
		$("#confirm_question").html(question);
		$("#confirm_button").html(button);

		// Show box
		$("#popup_bg").fadeTo(200, 0.5);
		$("#confirm").fadeTo(200, 1);

		// Assign click event
		$("#confirm_button").bind('click', function()
		{
			callback();
			UI.hidePopup();	
		});

		// Assign hide-function to background
		$("#popup_bg").bind('click', function()
		{
			UI.hidePopup();
		});

		// Assign key events
		$(document).keypress(function(event)
		{
			// If "enter"
			if(event.which == 13)
			{
				callback();
				UI.hidePopup();
			}
		});
	}

	/**
	 * Hides the current popup box
	 */
	this.hidePopup = function()
	{
		// Hide box
		$("#popup_bg").hide();
		$("#confirm").hide();
		$("#alert").hide();
		$("#vote_reminder").hide();

		// Remove events
		$("#confirm_button").unbind('click');
		$("#alert_button").unbind('click');
		$(document).unbind('keypress');

		if(!getCookie('allowCookies') && Config.cookieLaw)
		{
			window.location = "http://google.com";
		}
	}

	/**
	 * Display the amount of remaining characters
	 * @param Object field
	 * @param Object indicator
	 */
	this.limitCharacters = function(field, indicator)
	{
		// Get the values
		var max = field.maxLength;
		var length = field.value.length;

		// Change the indicator
		document.getElementById(indicator).innerHTML = length + " / " + max;
	}
}

/**
 * Tooltip related functions
 */
function FTooltip()
{
	/**
	 * Add event-listeners
	 */
	this.initialize = function()
	{
		// Add the tooltip element
		$("body").prepend('<div id="tooltip"></div>');

		// Add mouse-over event listeners
		this.addEvents();

		// Add mouse listener
		$(document).mousemove(function(e)
		{
		    if(typeof fusionTooltip != "undefined" && fusionTooltip != 0){
	            fusionTooltip.move(e.pageX, e.pageY);		        
		    }
		});
	}

	/**
	 * Used to support Ajax content
	 * Reloads the tooltip elements
	 */
	this.refresh = function()
	{
		// Remove all
		$("[data-tip]").unbind('hover');

		// Re-add
		this.addEvents();
	}

	this.addEvents = function()
	{
		// Add mouse-over event listeners
		$("[data-tip]").hover(
			function()
			{
			    fusionTooltip.show($(this).attr("data-tip"));
			},
			function()
			{
				$("#tooltip").hide();
			}
		);

		if(Config.UseFusionTooltip)
		{
			$("[rel]").hover(
				function()
				{
					if(/^item=[0-9]*$/.test($(this).attr("rel")))
					{
					    fusionTooltip.Item.get(this, function(data)
						{
					        fusionTooltip.show(data);
						});
					}
				},
				function()
				{
					$("#tooltip").hide();
				}
			);
		}
	}

	/**
	 * Moves tooltip
	 * @param Int x
	 * @param Int y
	 */
	this.move = function(x, y)
	{
		// Get half of the width
		var width = ($("#tooltip").css("width").replace("px", "") / 2);

		// Position it at the mouse, and center
		$("#tooltip").css("left", x - width).css("top", y + 25);
	}

	/**
	 * Displays the tooltip
	 * @param Object element
	 */
	this.show = function(data)
	{
		$("#tooltip").html(data).show();
	}

	/**
	 * Item tooltip object
	 */
	 this.Item = new function()
	 {
	 	/**
	 	 * Loading HTML
	 	 */
	 	this.loading = "Loading...";

	 	/**
	 	 * Runtime cache
	 	 */
	 	this.cache = new Array();

	 	/**
	 	 * The currently displayed item ID
	 	 */
	 	this.currentId = false;

	 	/**
	 	 * Load an item and display it in the tooltip
	 	 * @param Object element
	 	 * @param Function callback
	 	 */
	 	this.get = function(element, callback)
	 	{
	 		var obj = $(element);
	 		var realm = obj.attr("data-realm");
	 		var id = obj.attr("rel").replace("item=", "");
	 		fusionTooltip.Item.currentId = id;

	 		if(id in this.cache)
	 		{
	 			callback(this.cache[id])
	 		}
	 		else
	 		{
	 			var cache = fusionTooltip.Item.CacheObj.get("item_" + realm + "_" + id);

		 		if(cache !== false)
		 		{
		 			callback(cache);
		 		}
		 		else
		 		{
		 			callback(this.loading);

			 		$.get(Config.URL + "tooltip/" + realm + "/" + id, function(data)
			 		{
			 			// Cache it this visit
			 		   fusionTooltip.Item.cache[id] = data;
			 			fusionTooltip.Item.CacheObj.save("item_" + realm + "_" + id, data);

			 			// Make sure it's still visible
			 			if($("#tooltip").is(":visible") && fusionTooltip.Item.currentId == id)
			 			{
			 				callback(data);
			 			}
			 		});
			 	}
		 	}
	 	}

	 	this.CacheObj = new function()
	 	{
	 		/**
	 		 * Get cache from localStorage
	 		 * @param String name
	 		 * @return Mixed
	 		 */
	 		this.get = function(name)
	 		{
	 			if(typeof localStorage != "undefined")
	 			{
	 				var cache = localStorage.getItem(name);
	 				
		 			if(cache)
		 			{
		 				cache = JSON.parse(cache);

		 				// If it hasn't expired
		 				if(cache.expiration > Math.round((new Date()).getTime() / 1000))
		 				{
		 					return cache.data;
		 				}
		 				else
		 				{
		 					return false;
		 				}
		 			}
		 			else
		 			{
		 				return false;
		 			}
		 		}
		 		else
		 		{
		 			return false;
		 		}
	 		}

	 		/**
	 		 * Save data to localStorage
	 		 * @param String name
	 		 * @param String data
	 		 * @param Int expiration
	 		 */
	 		this.save = function(name, data)
	 		{
	 			if(typeof localStorage != "undefined")
	 			{
	 				var time = Math.round((new Date()).getTime() / 1000);
	 				var expiration = time + 60*60*24;

		 			localStorage.setItem(name, JSON.stringify({"data": data, "expiration": expiration}));
	 			}
	 		}
	 	}
	 }
}

var UI = new UI();
var fusionTooltip = new FTooltip();

//Enable tooltip
$(document).ready(function(){
    if(fusionTooltip != 0){
        fusionTooltip.initialize();        
    }
});
