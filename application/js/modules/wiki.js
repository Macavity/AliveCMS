
define('Wiki', function(){
    var Wiki = {

        /**
         * Related content object instances.
         */
        related: {},

        /**
         * URL of the page we are on.
         */
        pageUrl: '',

        /**
         * Currently active related tab.
         */
        tab: '',

        /**
         * Hash tag query params.
         */
        query: {},

        /**
         * Auto load the appropriate related tab.
         *
         * @constructor
         */
        initialize: function() {
            var tabs = $('#related-tabs'),
                hash = Core.getHash();

            if (tabs.length <= 0)
                return;

            tabs.find('a').click(function() {
                Wiki.loadRelated(this);
                return false;
            });

            // Load comments tab
            if (hash && /^c-([0-9]+)$/.test(hash)) {
                Wiki.loadRelated($('#tab-comments'));

                if (!Core.isIE()) {
                    Filter.reset();
                    Core.scrollTo('#related-tabs');
                }

                // Else determine which tab
            } else {
                Filter.initialize(function(query) {
                    Wiki.query = query;

                    if (query.tab) {
                        $('#tab-'+ query.tab).click();

                        if (!Core.isIE())
                            Core.scrollTo('#related-tabs');
                    } else {
                        tabs.find('a:first').click();
                    }
                });
            }
        },

        /**
         * Load related content pages. Save a cache of the content.
         *
         * @param node
         * @param reload
         * @return bool
         */
        loadRelated: function(node, reload) {
            node = $(node);

            // Generate url key
            var key = node.data('key'),
                wrapper = $('#related-content');

            $('#related-tabs a').removeClass('tab-active');

            node.addClass('tab-active');
            wrapper.find('.related-content').hide();

            // Set filter
            if (Wiki.tab !== '') {
                Filter.addParam('tab', key);
                Filter.addParam('page', '');
                Filter.applyQuery();
            }

            // Check cache
            if (Wiki.related[key] && !reload) {
                $('#related-'+ key).show();
                wrapper.removeClass('loading');

                Wiki.tab = key;
                Wiki.query = {};

                return false;
            }

            $.ajax({
                type: 'GET',
                url: Wiki.pageUrl + key,
                dataType: 'html',
                global: false,
                cache: (key != 'comments'),
                beforeSend: function() {
                    wrapper.addClass('loading');
                },
                success: function(data) {
                    if (data) {
                        Wiki.tab = key;
                        wrapper.removeClass('loading').append(data);
                        Wiki.query = {};

                        Core.fixTableHeaders('#related-'+ key);
                    }
                }
            });

            return false;
        },

        /**
         * Callback for posting a comment, will reload the tab.
         */
        postComment: function() {
            var tab = $('#tab-comments'),
                count = tab.find('em'),
                no = parseInt(count.html(), null);

            if (!no || no <= 0)
                no = 0;

            count.html(no + 1);

            delete Wiki.related.comments;
            Wiki.loadRelated(tab, true);
        }

    };

    return Wiki;
});
