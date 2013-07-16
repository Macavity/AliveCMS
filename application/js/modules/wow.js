/*jshint -W065 */

define(function(){
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
                    if (this.href == 'javascript:;' || this.href.indexOf('#') === 0)
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
                if (this.href == 'javascript:;' || this.href.indexOf('#') === 0 || this.rel == 'np' || this.href.indexOf('/vault/') != -1)
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
        },

        Icon: {

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

        }

    };
    return Wow;
});

