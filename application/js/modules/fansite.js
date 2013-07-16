
define(function(){

    /**
     * 3rd-party fansite integration.
     */
    var Fansite = {

        /**
         * Map of sites and available URLs.
         */
        sites: {
            arsenal: {
                name: 'Alte Armory',
                site: 'http://arsenal.wow-alive.de/',
                regions: ['eu'],
                locales: ['de', 'es', 'fr', 'ru'],
                urls: {
                    achievement: ['achievements', 'achievement={0}'],
                    character: ['profiles', function(params) {

                        var region = params[1].toLowerCase();
                        var realm = params[3].toLowerCase();
                        realm = realm.replace(/( )+/g, '-');
                        realm = realm.replace(/^A-Z/ig, '');
                        var name = params[2].toLowerCase();

                        return 'character-sheet.php?r=' + encodeURIComponent(realm) + '&cn=' + encodeURIComponent(name);
                    }],
                    faction: ['factions', 'faction={0}'],
                    'class': ['classes', 'class={0}'],
                    object: ['objects', 'object={0}'],
                    skill: ['skills', 'skill={0}'],
                    race: ['races', 'race={0}'],
                    quest: ['quests', 'quest={0}'],
                    spell: ['spells', 'spell={0}'],
                    event: ['events', 'event={0}'],
                    title: ['titles', 'title={0}'],
                    zone: ['zones', 'zone={0}'],
                    item: ['items', 'item={0}'],
                    npc: ['npcs', 'npc={0}'],
                    pet: ['pets', 'pet={0}']
                }
            },
            wowhead: {
                name: 'Wowhead',
                site: 'http://www.wowhead.com/',
                regions: ['us', 'eu'],
                locales: ['de', 'es', 'fr', 'ru'],
                urls: {
                    achievement: ['achievements', 'achievement={0}'],
                    character: ['profiles', function(params) {

                        var region = params[1].toLowerCase();
                        var realm = params[3].toLowerCase();
                        realm = realm.replace(/( )+/g, '-');
                        realm = realm.replace(/^A-Z/ig, '');
                        var name = params[2].toLowerCase();

                        return 'profile='+ encodeURIComponent(region) + '.' + encodeURIComponent(realm) + '.' + encodeURIComponent(name);
                    }],
                    faction: ['factions', 'faction={0}'],
                    'class': ['classes', 'class={0}'],
                    object: ['objects', 'object={0}'],
                    skill: ['skills', 'skill={0}'],
                    race: ['races', 'race={0}'],
                    quest: ['quests', 'quest={0}'],
                    spell: ['spells', 'spell={0}'],
                    event: ['events', 'event={0}'],
                    title: ['titles', 'title={0}'],
                    zone: ['zones', 'zone={0}'],
                    item: ['items', 'item={0}'],
                    npc: ['npcs', 'npc={0}'],
                    pet: ['pets', 'pet={0}']
                }
            },
            wowpedia: {
                name: 'Wowpedia',
                site: 'http://www.wowpedia.org/',
                regions: ['us', 'eu'],
                locales: ['fr', 'es', 'de', 'ru', 'it'],
                domains: {
                    ru: 'http://wowpedia.ru/wiki/',
                    de: 'http://de.wow.wikia.com/wiki/',
                    it: 'http://it.wow.wikia.com/wiki/'
                },
                urls: {
                    faction: ['Factions', '{1}'],
                    'class': ['Classes', '{1}'],
                    skill: ['Professions', '{1}'],
                    race: ['Races', '{1}'],
                    zone: ['Zones', '{1}'],
                    item: ['Items', '{1}'],
                    pet: ['Pets', '{1}'],
                    npc: ['NPCs', '{1}']
                },
                buildUrl: function(params) {
                    return params[2].replace(/\s+/g, '_').replace(/"/ig, '&quot;');
                }
            },
            buffed: {
                name: 'Buffed.de',
                site: 'http://wowdata.buffed.de/',
                regions: ['eu'],
                locales: ['de'],
                urls: {
                    achievement: ['', '?a={0}'],
                    faction: ['faction/', '?faction={0}'],
                    'class': ['class/portal', 'class/portal/{0}'],
                    skill: ['', 'spell/profession/{0}'],
                    spell: ['', '?s={0}'],
                    title: ['title/list', 'title/list'],
                    quest: ['quest/list/1/', '?q={0}'],
                    item: ['item/list', '?i={0}'],
                    zone: ['zone/list/1/', '?zone={0}'],
                    npc: ['', '?n={0}']
                }
            }
        },

        /**
         * Map of content types and available sites for that type.
         */
        map: {
            achievement: ['wowhead', 'buffed'],
            character: ['arsenal'],
            faction: ['wowhead', 'wowpedia', 'buffed'],
            'class': ['wowhead', 'wowpedia', 'buffed'],
            object: ['wowhead'],
            skill: ['wowhead', 'wowpedia', 'buffed'],
            quest: ['wowhead', 'buffed'],
            spell: ['wowhead', 'buffed'],
            event: ['wowhead'],
            title: ['wowhead', 'buffed'],
            arena: [],
            guild: [],
            zone: ['wowhead', 'wowpedia', 'buffed'],
            item: ['wowhead', 'wowpedia', 'buffed'],
            race: ['wowhead', 'wowpedia'],
            npc: ['wowhead', 'wowpedia', 'buffed'],
            pet: ['wowhead', 'wowpedia'],
            pvp: []
        },

        /**
         * Create the menu HTML and delegate link events.
         *
         * @constructor
         */
        initialize: function() {
            if (Fansite.initialized) {
                return;
            }

            Fansite.initialized = true;

            $(document)
                .delegate('a[data-fansite]', 'mouseenter.fansite', Fansite.onMouseOver)
                .delegate('a[data-fansite]', 'mouseleave.fansite', ContextMenu.delayedHide);
        },

        onMouseOver: function() {
            var node = $(this),
                params = Fansite.read(node.data('fansite'));

            Fansite.openMenu(node, params);
            return false;
        },

        /**
         * Split params the awesome way!
         *
         * @param data
         * @return array
         */
        read: function(data) {
            return data.split('|');
        },

        /**
         * Generate links from params.
         *
         * @param params
         * @return array
         */
        createLinks: function(params) {
            var type = params[0],
                map = Fansite.map[type],
                links = [],
                lang = Core.getLanguage();

            if (map.length > 0) {
                var site, url, urls;

                for (var i = 0, len = map.length; i < len; ++i) {
                    if (!Fansite.sites[map[i]])
                        continue;

                    site = Fansite.sites[map[i]];

                    if (
                        ((lang != 'en') && ($.inArray(lang, site.locales) < 0)) ||
                            ($.inArray(Core.buildRegion, site.regions) < 0) ||
                            !site.urls[type]
                        ) {
                        continue;
                    }

                    url = Fansite.createUrl(site),
                        urls = site.urls[type];

                    if (params.length <= 1) {
                        url += urls[0];
                    } else {
                        if (typeof site.buildUrl == 'function') {
                            url += site.buildUrl(params);
                        } else {
                            var urlPattern = urls[1];

                            if (typeof urlPattern == 'function') {
                                url += urlPattern(params);
                            } else {
                                for (var j = 1; j < params.length; ++j) {
                                    urlPattern = urlPattern.replace('{' + (j - 1) + '}', encodeURIComponent(params[j]));
                                }
                                url += urlPattern;
                            }
                        }
                    }

                    links.push('<a href="'+ url +'" target="_blank">'+ site.name +'</a>');
                }
            }

            return links;
        },

        /**
         * Create the URL based on locale.
         *
         * @param site
         * @return string
         */
        createUrl: function(site) {
            var url = site.site,
                lang = Core.getLanguage();

            if ($.inArray(lang, site.locales) >= 0) {
                if (site.domains && site.domains[lang])
                    url = site.domains[lang];
                else
                    url = url.replace('www', lang);
            }

            return url;
        },

        /**
         * Open up the menu and show the available sites for that type.
         *
         * @param node
         * @param params
         */
        openMenu: function(node, params) {
            Fansite.node = node;

            var list = $('<ul/>');
            var links = Fansite.createLinks(params);

            var title = '';

            if (links.length === 0) {
                title = Msg.ui.fansiteNone;
            } else {
                if (Msg.fansite[params[0]]) {
                    title = Msg.ui.fansiteFindType.replace('{0}', Msg.fansite[params[0]]);
                } else {
                    title = Msg.ui.fansiteFind;
                }
            }

            $('<li/>')
                .addClass('divider')
                .html('<span>' + title + '</span>')
                .appendTo(list);

            if (links.length > 0) {
                for (var i = 0, length = links.length; i < length; ++i) {
                    $('<li/>').append(links[i]).appendTo(list);
                }

                // Also linkify the button itself if there's only 1 fansite
                if (links.length == 1) {
                    node.attr('href', $(links[0]).attr('href'));
                    node.attr('target', '_blank');
                }
            }

            ContextMenu.show(node, list);
        },

        /**
         * Generate links for inline display.
         *
         * @param target
         * @param data
         */
        generate: function(target, data) {
            var links = Fansite.createLinks(Fansite.read(data));

            $(target).html(links.join(' ')).addClass('fansite-group');
        }

    };
    return Fansite;
});