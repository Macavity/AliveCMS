

define(function(){
    var WikiDirectory = {

        /**
         * Current selected expansion.
         */
        expansion: 0,

        /**
         * Initialize the selected expansion.
         *
         * @param id
         */
        initialize: function(id) {
            WikiDirectory.expansion = id;

            Filter.initialize(function(query) {
                if (query.expansion) {
                    if (query.expansion > 3 || query.expansion < 0) {
                        query.expansion = 0;
                    }

                    WikiDirectory.view($('#nav-'+ query.expansion), query.expansion);
                }
            });
        },

        /**
         * Select an expansion.
         *
         * @param node
         * @param id
         */
        view: function(node, id) {
            node = $(node);
            node.parent().parent().find('a').removeClass('nav-active');
            node.addClass('nav-active');

            $('body')
                .removeClass('expansion-'+ WikiDirectory.expansion)
                .addClass('expansion-'+ id);

            $('#wiki')
                .find('.groups .group').hide().end()
                .find('#expansion-'+ id).show().end();

            Filter.addParam('expansion', id.toString());
            Filter.applyQuery();

            WikiDirectory.expansion = id;
        }

    };

    return WikiDirectory;
});
