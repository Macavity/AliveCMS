
define(['modules/table','modules/filter', 'modules/wiki'], function(Table, Filter, Wiki){
    var WikiRelated = Class.extend({
        object: null,
        table: null,
        page: null,

        /**
         * Initialize table and events.
         *
         * @param page
         * @param config
         */
        init: function(page, config) {
            this.page = page;
            this.object = $('#related-'+ page);

            if (this.object.find('table').length) {
                this.table = new Table(this.object, config);

                if (Wiki.tab == page && Wiki.query.page)
                    this.table.paginate(Wiki.query.page);
            }

            // Advanced toggle
            this.object.find('.advanced-toggle').click($.proxy(this.toggleAdvanced, this));

            if (!this.table)
                return;

            // Setup filters
            var filters = this.object.find('.filters');

            if (filters.length) {
                Filter.bindInputs(filters, $.proxy(this.filter, this));

                filters.find('.filter-name').bind('focus blur', this.inputBehavior);
            }

            // Setup keyword
            var keyword = this.object.find('.keyword');

            if (keyword.length) {
                keyword
                    .find('.reset').click($.proxy(this.keywordReset, this)).end()
                    .find('input').keyup($.proxy(this.keywordClick, this));
            }
        },

        /**
         * Event for keyword input click.
         *
         * @param e
         */
        keywordClick: function(e) {
            var node = $(e.currentTarget || e.target),
                view = node.siblings('.view'),
                reset = node.siblings('.reset');

            if (node.val() !== '') {
                view.hide();
                reset.show();
            } else {
                view.show();
                reset.hide();
            }
        },

        /**
         * Event for keyword reset.
         *
         * @param e
         */
        keywordReset: function(e) {
            var node = $(e.currentTarget || e.target),
                view = node.siblings('.view'),
                input = node.siblings('input');

            view.show();
            node.hide();
            input.val('').trigger('keyup').trigger('blur');
        },

        /**
         * Run the filters no the table.
         *
         * @param data
         */
        filter: function(data) {
            if (data.tag == 'a') {
                this.object.find('.filter-tabs a').removeClass('tab-active');

                data.node.addClass('tab-active');

                this.table.filter(data.filter, data.column, data.value, 'contains');

            } else {
                if (data.filter == 'column') {
                    this.table.filter('column', data.column, data.value);
                } else {
                    this.table.filter(data.filter, data.name, data.value);
                }
            }
        },

        /**
         * Filter down the table based on the selected tab.
         *
         * @param e
         */
        filterTabs: function(e) {
            var node = $(e.currentTarget || e.target);

            this.object.find('.filter-tabs a').removeClass('tab-active');
            node.addClass('tab-active');

            this.table.filter(node.data('filter'), node.data('column'), node.data('value'), 'contains');
        },

        /**
         * Auto select the hide non-equippable checkbox.
         */
        hideNonEquipment: function() {
            var select = this.object.find('.filter-class'),
                equip = this.object.find('.filter-isEquippable'),
                value = select.val();

            if (equip.length && (equip.is(':checked') && value === '' || !equip.is(':checked') && value !== '')) {
                equip.click();
                this.table.filter('class', 'isEquippable', 'is-equipment');
            }
        },

        /**
         * Default behavior for input fields.
         */
        inputBehavior: function() {
            if (this.value == this.title) {
                this.value = '';
                $(this).addClass('focus');

            } else if ($.trim(this.value) === '') {
                this.value = this.title;
                $(this).removeClass('focus');
            }
        },

        /**
         * Open or close the advanced filters.
         *
         * @param e
         */
        toggleAdvanced: function(e) {
            var node = $(e.currentTarget || e.target);

            if (node.hasClass('opened')) {
                node.removeClass('opened');
                this.object.find('.advanced-filters').hide();

            } else {
                node.addClass('opened');
                this.object.find('.advanced-filters').show();
            }
        }

    });
    return WikiRelated;
});
