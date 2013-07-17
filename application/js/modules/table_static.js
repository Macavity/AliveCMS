/*jshint -W084 */

define(function(){

    /**
     * Static functions used by the table class.
     */
    var TableStatic = {

        /**
         * Detect if the string/array is within an array.
         *
         * @param text
         * @param match
         */
        contains: function(text, match) {
            text = text.split(' ');

            var valid = false,
                isArray = !(typeof match === 'string' || typeof match === 'number');

            for (var i = 0, test; test = text[i]; ++i) {
                if ((isArray && $.inArray(test, match) >= 0) || (!isArray && test.indexOf(match) >= 0)) {
                    valid = true;
                    break;
                }
            }

            return valid;
        },

        /**
         * Detect if the string contains a character.
         *
         * @param text
         * @param match
         */
        matches: function(text, match) {
            if (typeof match === 'number') {
                return (match == text);
            }

            if (typeof match === 'string') {
                return (text.indexOf(match.toLowerCase()) >= 0);

            } else {
                var valid = true;

                for (var i = 0, test; test = match[i]; ++i) {
                    if (text.indexOf(test.toLowerCase()) === -1) {
                        valid = false;
                        break;
                    }
                }

                return valid;
            }
        },

        /**
         * Values are equal.
         *
         * @param text
         * @param match
         */
        equals: function(text, match) {
            return (match == text);
        },

        /**
         * Values are not equal.
         *
         * @param text
         * @param match
         */
        notEquals: function(text, match) {
            return (match != text);
        },

        /**
         * Values and type are exact.
         *
         * @param text
         * @param match
         */
        exact: function(text, match) {
            return (match === text);
        },

        /**
         * Value is within a specific range.
         *
         * @param text
         * @param match
         */
        range: function(text, match) {
            return (parseInt(text, null) >= match[0] && parseInt(text, null) <= match[1]);
        },

        /**
         * Value is greater than a number.
         *
         * @param text
         * @param match
         */
        greaterThan: function(text, match) {
            return (parseInt(text, null) > match);
        },

        /**
         * Value is greater or equals to a number.
         *
         * @param text
         * @param match
         */
        greaterThanEquals: function(text, match) {
            return (parseInt(text, null) >= match);
        },

        /**
         * Value is less than a number.
         *
         * @param text
         * @param match
         */
        lessThan: function(text, match) {
            return (parseInt(text, null) < match);
        },

        /**
         * Value is less or equal to a number.
         *
         * @param text
         * @param match
         */
        lessThanEquals: function(text, match) {
            return (parseInt(text, null) <= match);
        },

        /**
         * Value starts with a specific character(s).
         *
         * @param text
         * @param match
         */
        startsWith: function(text, match) {
            return (text.substr(0, match.length) === match);
        },

        /**
         * Value ends with a specific character(s).
         *
         * @param text
         * @param match
         */
        endsWith: function(text, match) {
            return (text.substr(-match.length) === match);
        },

        /**
         * The column to sort against.
         */
        column: 0,

        /**
         * Sort the data numerical.
         *
         * @param a
         * @param b
         */
        sortNumeric: function(a, b) {
            var x = a[0][TableStatic.column],
                y = b[0][TableStatic.column];

            return parseFloat(x) - parseFloat(y);
        },

        /**
         * Sort the data by date.
         *
         * @param a
         * @param b
         */
        sortDate: function(a, b) {
            var x = Date.parse(a[0][TableStatic.column]),
                y = Date.parse(b[0][TableStatic.column]);

            return parseFloat(x) - parseFloat(y);
        },

        /**
         * Sort the data natural.
         *
         * @param a
         * @param b
         */
        sortNatural: function(a, b) {
            var x = a[0][TableStatic.column],
                y = b[0][TableStatic.column];

            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        }

    };
    return TableStatic;
});