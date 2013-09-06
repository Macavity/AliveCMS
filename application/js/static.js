
// The build will inline common dependencies into this file.
// For any third party dependencies, like jQuery, place them in the lib folder.
// Configure loading modules from the lib directory,
// except for 'app' ones, which are in a sibling
// directory.

requirejs.config({
    baseUrl: '/application/js',

    // Disable internal caching of the files (development only)
    //urlArgs: "bust=" + (new Date()).getTime(),
    urlArgs: "rev=617.4",

    paths: {
    }
});

var mapStatic = {

    urls: {
        base: 'http://dev.wow-alive.de',
        changeCharacter: '/ucp/changeCharacter/',
        ts3viewer: "http://tsviewer.com/ts3viewer.php?ID=1014260&text=e6c210&text_size=11&text_family=1&js=1&text_s_weight=bold&text_s_style=normal&text_s_variant=normal&text_s_decoration=none&text_s_color_h=525284&text_s_weight_h=bold&text_s_style_h=normal&text_s_variant_h=normal&text_s_decoration_h=underline&text_i_weight=normal&text_i_style=normal&text_i_variant=normal&text_i_decoration=none&text_i_color_h=525284&text_i_weight_h=normal&text_i_style_h=normal&text_i_variant_h=normal&text_i_decoration_h=underline&text_c_weight=normal&text_c_style=normal&text_c_variant=normal&text_c_decoration=none&text_c_color_h=525284&text_c_weight_h=normal&text_c_style_h=normal&text_c_variant_h=normal&text_c_decoration_h=underline&text_u_weight=bold&text_u_style=normal&text_u_variant=normal&text_u_decoration=none&text_u_color_h=525284&text_u_weight_h=bold&text_u_style_h=normal&text_u_variant_h=normal&text_u_decoration_h=none"
    },

    /**
     * List of initialization class pairings.
     * The first class is always the class a object needs to have, the second class is added when the binding is complete.
     * In further stages the DOM element can be cleansed of both classes after the binding for optimization,
     * the current way makes the reading of the DOM easier.
     */
    initClasses: {
    },

    selectors: {
    },

    lang: {
        first: 'Erster',
        last: 'Letzter',
        loading: 'Lade…',
        bugtracker: {
            deleteLink: 'Entfernen',
            attention: 'Achtung!',
            alright: 'Alles in Ordnung',
            similarBugsExist: 'Es gibt dazu schon Bug Reports:',
            noSimilarBugs: 'Keine anderen Bug Reports gefunden.',
            errorProject: 'Bitte wähle zuerst aus welche Kategorie der Bug hat.'
        },
        store: {
            checkout: "Einkauf bestätigen",
            buy: "Kaufen",
            cancel: "Abbrechen",
            cant_afford: "Du kannst dir dies nicht leisten!",
            want_to_buy: "Bist Du dir Sicher das Du diese Gegenstände kaufen möchtest?"
        }
    },

    /**
     * Will be filled by scripts for further use of generic values
     */
    "precompiled": {}
};
