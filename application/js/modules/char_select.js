/*jshint -W004 */
define(['modules/core', 'modules/toggle', 'modules/input', 'modules/tooltip'], function(Core, Toggle, Input, Tooltip){

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

            $('.user-plate .character-select .close').on("click", function(event){
                event.preventDefault();
                CharSelect.close(this);
            });

            $(".user-plate .character-list .char").not(".pinned").on("click", function(event){
                event.preventDefault();
                var object = $(this);
                var guid = object.data("guid");
                var realm = object.data("realm");
                CharSelect.pin(guid, realm, this);
            });

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

            var switchUrl = mapStatic.urls.changeCharacter;

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
                    /*if ($(".module-ucp"))
                        refreshUrl = '/ucp';
                    else if($(".module-news"))
                        refreshUrl = '/news';

                    // Request new content or replace
                    if (refreshUrl != switchUrl)
                        CharSelect.pageUpdate(refreshUrl);
                    else*/
                        CharSelect.replace(content);

                    /**
                     * Store Name updates
                     * @type {*|jQuery|HTMLElement}
                     */
                    var activeChar = $("#selected-character");

                    if(activeChar.length != 0){
                        $("#cart_items .item-nick").html(activeChar.data("name")+" @ "+activeChar.data("realmname"));
                    }
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
    return CharSelect;
});