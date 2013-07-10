/*
 * jQuery Tooltip plugin 1.3
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-tooltip/
 * http://docs.jquery.com/Plugins/Tooltip
 *
 * Copyright (c) 2006 - 2008 Jörn Zaefferer
 *
 * $Id: jquery.tooltip.js 5741 2008-06-21 15:22:16Z joern.zaefferer $
 * 
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

define(['jquery'], function($) {

  // the tooltip element
  var helper = {},
  // the current tooltipped element
    current,
  // the title of the current element, used for restoring
    title,
  // timeout id for delayed tooltips
    tID,
  // flag for mouse tracking
    track = true;

  $.tooltip = {
    blocked: false,
    defaults: {
      delay: 200,
      duration: 400,
      track: true,
      showURL: false,
      extraClass: "",
      top: 10,
      left: 10,
      id: "tooltip",
      maxURLLetters: 50
    },
    block: function() {
      $.tooltip.blocked = !$.tooltip.blocked;
    }
  };

  $.fn.extend({
    tooltip: function(settings) {
      settings = $.extend({}, $.tooltip.defaults, settings);
      createHelper(settings);
      return this.each(function() {
        $.data(this, "tooltip", settings);
        this.tOpacity = helper.parent.css("opacity");
        // copy tooltip into its own expando and remove the title
        this.tooltipText = (settings.tooltipText) ? settings.tooltipText : this.title;
        $.data(this, 'wefiTitle', this.title);
        $(this).removeAttr('title').removeAttr('alt');
      })
        .hover((save),(hide))
        .click(hide);
    },
    hideWhenEmpty: function() {
      return this.each(function() {
        $(this)[ $(this).html() ? "show" : "hide" ]();
      });
    },
    url: function() {
      return this.attr('href') || this.attr('src');
    }
  });

  function createHelper(settings) {
    // there can be only one tooltip helper
    if( helper.parent )
      return;
    // create the helper, h3 for title, div for url
    helper.parent = $('<div id="' + settings.id + '"><h3></h3><div class="body"></div><div class="url"></div></div>')
      // add to document
      .appendTo(document.body)
      // hide it at first
      .hide();

    // save references to title and url elements
    helper.title = $('h3', helper.parent);
    helper.body = $('div.body', helper.parent);
    helper.url = $('div.url', helper.parent);
  }

  function settings(element) {
    return $.data(element, "tooltip");
  }

  // main event handler to start showing tooltips
  function handle(event) {
    // show helper, either with timeout or on instant
    if( settings(this).delay )
      tID = setTimeout(showTooltip, settings(this).delay);
    else
      showTooltip();

    // if selected, update the helper position when the mouse moves
    track = !!settings(this).track;
    $(document.body).bind('mousemove', update);

    // update at least once
    update(event);
  }

  // save elements title before the tooltip is displayed
  function save() {
    // if this is the current source, or it has no title (occurs with click event), stop
    if ( $.tooltip.blocked || this == current || (!this.tooltipText && !settings(this).bodyHandler) )
      return;

    // save current
    current = this;
    title = this.tooltipText;

    if ( settings(this).bodyHandler ) {
      helper.title.hide();
      //console.log(settings(this).bodyHandler.call(this));
      var bodyContent = settings(this).bodyHandler.call(this);
      if (bodyContent.nodeType || bodyContent.jquery) {
        helper.body.empty().append(bodyContent)
      } else {
        helper.body.html( bodyContent );
      }
      helper.body.show();
    } else if ( settings(this).showBody ) {
      var parts = title.split(settings(this).showBody);
      helper.title.html(parts.shift()).show();
      helper.body.empty();
      for(var i = 0, part; (part = parts[i]); i++) {
        if(i > 0)
          helper.body.append("<br/>");
        helper.body.append(part);
      }
      helper.body.hideWhenEmpty();
    } else {
      helper.title.html(title).show();
      helper.body.hide();
    }

    // if element has href or src, add and show it, otherwise hide it
    if( settings(this).showURL && $(this).url() ){
      var urlString = $(this).url().replace('http://', '');
      if(urlString.length >= settings(this).maxURLLetters && settings(this).maxURLLetters != 0){
        urlString = urlString.substring(0,settings(this).maxURLLetters) + '...';
      };
      helper.url.html( urlString ).show();
    }	else {
      helper.url.hide();
    }

    // add an optional class for this tip
    helper.parent.addClass(settings(this).extraClass);

    handle.apply(this, arguments);
  }

  // delete timeout and show helper
  function showTooltip() {
    tID = null;
    helper.parent.show();
    update();
    helper.parent.hide();
    helper.parent.fadeIn(settings(current).duration,'swing',update);
  }

  /**
   * callback for mousemove
   * updates the helper position
   * removes itself when no current element
   */
  function update(event)	{
    if($.tooltip.blocked || (event && event.target.tagName == "OPTION")) return;

    // stop updating when tracking is disabled and the tooltip is visible
    if ( !track && helper.parent.is(":visible"))  $(document.body).unbind('mousemove', update);

    // if no current element is available, remove this listener
    if( current == null ) {
      $(document.body).unbind('mousemove', update);
      return;
    }

    // remove position helper classes
    helper.parent.removeClass("viewport-right").removeClass("viewport-bottom");

    var left = helper.parent[0].offsetLeft;
    var top = helper.parent[0].offsetTop;
    if (event) {
      // position the helper 15 pixel to bottom right, starting from mouse position
      left = event.pageX + settings(current).left;
      top = event.pageY + settings(current).top;
      var right='auto';
      if (settings(current).positionLeft) {
        right = $(window).width() - left;
        left = 'auto';
      }
      helper.parent.css({
        left: left,
        right: right,
        top: top,
        bottom: 'auto'
      });
    }

    var v = viewport(),
      h = helper.parent[0];
    // check horizontal position Right
    if (v.x + v.cx < h.offsetLeft + h.offsetWidth + 20) {
      var right = (v.cx - left - (settings(current).left * -2)) - 20;
      if(right < 20){
        right = 20;
      }
      helper.parent.css({left: 'auto',right: right + 'px'}).addClass("viewport-right");
    }
    // check horizontal position Left
    if (v.x + 20 > left && left != 0) {
      left = v.x + 20;
      helper.parent.css({left: left + 'px'});
    }
    // check vertical position
    if (v.y + v.cy < h.offsetTop + h.offsetHeight) {
      top -= h.offsetHeight + 5 + settings(current).top;
      helper.parent.css({top: top + 'px'}).addClass("viewport-bottom");
    }
  }

  function viewport() {
    return {
      x: $(window).scrollLeft(),
      y: $(window).scrollTop(),
      cx: $(window).width(),
      cy: $(window).height()
    };
  }

  // hide helper and restore added classes and the title
  function hide(event) {
    if($.tooltip.blocked)
      return;
    // clear timeout if possible
    if(tID)
      clearTimeout(tID);
    // no more current element
    current = null;

    var tsettings = settings(this);
    function complete() {
      helper.parent.removeClass( tsettings.extraClass ).hide().css("opacity", "");
    }
    complete();
  }
});