
define(['alive/core', 'jquery'], function(Core, $){
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
});