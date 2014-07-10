
define(['./BaseController','modules/wiki','modules/wiki_related', 'modules/core'], function (BaseController, Wiki, WikiRelated, Core) {

    var StoreController;
    StoreController = BaseController.extend({

        /**
         * Vote Points
         */
        vp: 0,

        shoppingCart: [],

        activeChar: null,

        /**
         * @constructor
         */
        init: function (vp) {
            this._super();
            debug.debug("PageController.initialize");

            this.vp = vp;

            this.updateActiveChar();

            this.initBindings();

            this.loadActiveStoreContent();

        },

        loadActiveStoreContent: function(){

            var StoreController = this;

            var storeRealms = $("#store_realms");
            var findActiveStore = storeRealms.find(".active a");
            var realmId;

            if(!findActiveStore){
                var first = storeRealms.find("a").first();
                if(first){
                    realmId = first.data("key");
                    first.parent().addClass("active");
                    $("#store"+realmId).addClass("active");

                    findActiveStore = first;
                }
            }

            if(findActiveStore){
                realmId = findActiveStore.data("key");
                StoreController.loadStoreContents(realmId);
            }

        },

        loadStoreContents: function (realmId) {

            var wrapper = $("#store" + realmId);

            $.ajax({
                type: 'GET',
                url: '/store/realm/' + realmId,
                dataType: 'html',
                global: false,
                beforeSend: function () {
                    wrapper.addClass('loading');
                },
                success: function (data) {
                    if (data) {
                        $("#tab"+realmId).data("jsrealmstoreinit", 1);

                        wrapper.removeClass('loading').html(data);
                        var total = $("#store" + realmId + " .table tbody tr").length - 1;

                        Wiki.tab = realmId;
                        Wiki.query = {};
                        Wiki.related[realmId] = new WikiRelated(realmId, {
                            paging: true,
                            totalResults: total,
                            column: 2,
                            method: 'numeric',
                            type: 'asc'
                        }, Wiki);

                        Core.fixTableHeaders('#store' + realmId);
                    }

                }
            });
        },

        initBindings: function () {

            var Controller = this;

            var storeRealms = $("#store_realms");

            /**
             * Load Realm Store Contents on click
             */
            storeRealms.find(".nav-tabs").on("click", "a", function (event) {
                var link = $(event.target);
                var realmId = link.data("key");
                var realmTab = $("#tab"+realmId);

                var initialized = realmTab.data("jsrealmstoreinit");

                var storeRealms = $("#store_realms");

                if(typeof initialized === "undefined"){
                    Controller.loadStoreContents(realmId);
                }

                storeRealms.find(".active").removeClass("active");
                storeRealms.find(".activeTab").removeClass("activeTab");

                $("#store"+realmId).addClass("active");
                realmTab.addClass("active activeTab");
            });

            /**
             * Cart - Put Item in the Cart
             */
            storeRealms.on("click", ".jsPutToCart", function (event) {
                debug.debug("jsPutToCart Event");

                Controller.updateActiveChar();

                var button = $(event.target);

                /**
                 * Error if no character has been selected
                 */
                if (Controller.activeChar == null) {
                    button
                        .popover('destroy')
                        .popover({
                            title: 'Fehler',
                            content: 'Bitte log dich zuerst ein und wähle einen Charakter oben rechts neben dem Hauptmenü aus.',
                            trigger: 'manual'
                        })
                        .popover('show');
                    setTimeout(function () {
                        button.popover('destroy');
                    }, 4000);
                    return;
                }

                /**
                 * Error if the selected shop item is for another realm
                 */
                if (button.data("realm") != Controller.activeChar.realm.id) {
                    button
                        .popover('destroy')
                        .popover({
                            title: 'Fehler',
                            content: 'Du kannst nur für den Realm shoppen auf dem dein aktiver Charakter ist.',
                            trigger: 'manual'
                        })
                        .popover('show');
                    setTimeout(function () {
                        button.popover('destroy');
                    }, 4000);
                    return;
                }

                var itemObject = {
                    id: button.data("id"),
                    vp_price: button.data("price"),
                    itemid: button.data("itemid"),
                    icon: button.data("icon"),
                    name: button.data("name"),
                    count: 1,
                    character: Controller.activeChar.name,
                    charGuid: Controller.activeChar.guid,
                    uniqueKey: button.data("id") + "-" + Controller.activeChar.guid,
                    realm: Controller.activeChar.realm.id,
                    type: "vp"
                };

                if (Controller.isInCart(itemObject)) {
                    Controller.increaseCount(itemObject);
                }
                else {
                    Controller.addToCart(itemObject);
                }

                Controller.updateCartPrice();

            });

            /**
             * Cart - Remove item from cart
             */
            $("#cart_items").on("click", ".jsDeleteFromCart", function (event) {

                event.preventDefault();

                var button = $(event.target);

                var removeKey = button.data("itemkey");

                var newCart = [];

                var cart = Controller.shoppingCart;
                var cartItem;
                for (var n in cart) {
                    if (cart.hasOwnProperty(n)) {
                        cartItem = cart[n];
                        if (cartItem.uniqueKey != removeKey) {
                            newCart.push(cart[n]);
                        }
                    }
                }

                Controller.shoppingCart = newCart;

                $("#cart-item-" + removeKey).remove();

                Controller.updateCartPrice();
            });

            /**
             * Cart - Show modal dialog if the user really wants to buy
             */
            $("#cart_price").on("click", ".jsStoreCheckout", function (event) {

                event.preventDefault();

                Controller.updateCartPrice();

                var modalTemplate = Controller.getTemplate("store_checkout");

                var sumPrice = $("#vp_price").html() * 1;

                var hasError = false;

                if (sumPrice > Controller.vp) {
                    hasError = true;
                }

                var modalHtml = modalTemplate({
                    url: Config.URL,
                    error: hasError,
                    vp_sum: sumPrice,
                    lang: mapStatic.lang.store
                });

                $("#modalCheckout").html(modalHtml)
                    .modal('show').removeClass("hide");

            });

            /**
             * Store - Confirm Buy
             *
             * found in store_checkout.handlebars
             */
            $("#modalCheckout").on("click", ".jsStorePay", function (event) {

                event.preventDefault();

                var modal = $("#modalCheckout");
                var modalBody = modal.find(".modal-body");
                var modalFooter = modal.find(".modal-footer");

                if (modal.hasClass("disabled")) {
                    return;
                }

                // Track the current cart items
                this.shoppingCart.each(function(index, cartItem){
                    _paq.push(['trackEvent', 'VoteShop', 'Buy Item', cartItem.name+"("+cartItem.id+")", cartItem.count]);
                });

                modal.addClass("disabled");
                modalFooter.hide();
                modalBody.html("<h3>" + mapStatic.lang.loading + "</h3>" + '<br><img src="/application/themes/shattered/images/uber-loading.gif">');

                var cartList = JSON.stringify(Controller.shoppingCart);

                $.post(Config.URL + "store/pay", {
                        data: cartList,
                        csrf_token_name: Config.CSRF
                    },
                    function (data) {

                        modal.modal("hide");
                        modal.removeClass("disabled");

                        var template = null;
                        var alertHtml = '';

                        if (data.type == "error") {
                            template = Controller.getTemplate("alert");
                            alertHtml = template({
                                type: "danger",
                                message: data.msg
                            });
                            $("#checkout").html(alertHtml).fadeIn(150);

                        }
                        else if (data.type == "success") {
                            modal.modal("hide");
                            template = Controller.getTemplate("alert");
                            alertHtml = template({
                                type: "success",
                                message: data.msg
                            });
                            $("#checkout").html(alertHtml).fadeIn(150);
                            $("#store_realms, #cart").fadeOut(300);
                        }
                        else {
                            modal.html(data);
                        }
                    }, "json")
                    .fail(function (data) {
                        modal.modal("hide");
                        modal.removeClass("disabled");
                        $("#checkout").html(data.responseText).fadeIn(150);
                    });
            });

        },


        clickRemoveItem: function (button) {

        },

        initWikiRelated: function (wrapperId, options) {
            debug.debug("PageController.initWiki");
            Wiki.related[wrapperId] = new WikiRelated(wrapperId, options);
        },

        addToCart: function (itemObject) {
            this.updateActiveChar();
            this.shoppingCart.push(itemObject);

            var compiledTemplate = this.getTemplate('store_article');

            var itemHTML = compiledTemplate({
                item: itemObject,
                realm: this.activeChar.realm,
                recipient: this.activeChar.name,
                url: Config.URL
            });

            $("#cart_items").append(itemHTML);

        },

        updateActiveChar: function () {
            var activeChar = $("#selected-character");

            if (activeChar.length !== 0) {
                this.activeChar = {
                    name: activeChar.data("name"),
                    guid: activeChar.data("charid"),
                    realm: {
                        id: activeChar.data("realmid"),
                        name: activeChar.data("realmname")
                    }
                };
            }
        },

        updateCartPrice: function () {
            var sum = 0;
            var countAll = 0;
            var cart = this.shoppingCart;
            for (var id in cart) {
                if (cart.hasOwnProperty(id)) {
                    var item = cart[id];
                    countAll += item.count;
                    sum += item.vp_price * item.count;
                }
            }
            $("#vp_price").html(sum);
            $("#cart_item_count").html(countAll);

            if (countAll > 0) {
                $("#empty_cart").fadeOut(300);
                $("#cart_price").fadeIn(300);
            }
            else {
                $("#cart_price").fadeOut(300);
                $("#empty_cart").fadeIn(300);
            }
        },

        /**
         * Checks if a Store Entry is already in the cart for the specified Character
         * @param itemObject
         * @returns {boolean}
         */
        isInCart: function (itemObject) {

            var cartItem;
            var cart = this.shoppingCart;

            for (var n in cart) {
                if (cart.hasOwnProperty(n)) {
                    cartItem = cart[n];
                    // Realm needs no check because the storeEntryId is unique for each realm
                    if (cartItem.uniqueKey == itemObject.uniqueKey) {
                        return true;
                    }
                }
            }

            return false;
        },

        /**
         * Increase the count for this item
         * @param itemObject
         * @returns {boolean}
         */
        increaseCount: function (itemObject) {

            var cartItem;
            var cart = this.shoppingCart;

            for (var n in cart) {
                if (cart.hasOwnProperty(n)) {
                    cartItem = cart[n];
                    // Realm needs no check because the storeEntryId is unique for each realm
                    if (cartItem.uniqueKey == itemObject.uniqueKey) {

                        cartItem.count++;
                        $("#cart-quantity-" + cartItem.uniqueKey + " span").html("x" + cartItem.count);
                        return true;
                    }
                }
            }

            return false;
        },

        getTemplate: function (templateName) {
            if (typeof Handlebars.templates[templateName] == "undefined") {
                debug.error("Template " + templateName + " not found.");
                return {};
            }
            return Handlebars.templates[templateName];
        }

    });

    return StoreController;
});
