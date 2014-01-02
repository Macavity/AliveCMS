
define(['./BaseController','modules/wiki','modules/wiki_related'], function (BaseController, Wiki, WikiRelated) {

    var StoreController = BaseController.extend({

        /**
         * Vote Points
         */
        vp: 0,

        shoppingCart: [],

        activeChar: null,

        /**
         * @constructor
         */
        init: function(vp){
            this._super();
            debug.debug("PageController.initialize");
            Wiki.pageUrl = "/store/realm/";
            Wiki.initialize();

            this.vp = vp;

            this.updateActiveChar();

            this.initBindings();

        },

        initBindings: function(){

            var Controller = this;

            /**
             * Cart - Put Item in the Cart
             */
            $("#related-content").on("click", ".jsPutToCart", function(event){
                debug.debug("jsPutToCart Event");

                Controller.updateActiveChar();

                var button = $(event.target);

                /**
                 * Error if no character has been selected
                 */
                if(Controller.activeChar == null){
                    button
                        .popover('destroy')
                        .popover({
                            title: 'Fehler',
                            content: 'Bitte log dich zuerst ein und wähle einen Charakter oben rechts neben dem Hauptmenü aus.',
                            trigger: 'manual'
                        })
                        .popover('show');
                    setTimeout(function(){ button.popover('destroy'); }, 4000);
                    return;
                }

                /**
                 * Error if the selected shop item is for another realm
                 */
                if(button.data("realm") != Controller.activeChar.realm.id){
                    button
                        .popover('destroy')
                        .popover({
                            title: 'Fehler',
                            content: 'Du kannst nur für den Realm shoppen auf dem dein aktiver Charakter ist.',
                            trigger: 'manual'
                        })
                        .popover('show');
                    setTimeout(function(){ button.popover('destroy'); }, 4000);
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
                    uniqueKey: button.data("id")+"-"+Controller.activeChar.guid,
                    realm: Controller.activeChar.realm.id,
                    type: "vp"
                };

                if(Controller.isInCart(itemObject)){
                    Controller.increaseCount(itemObject);
                }
                else{
                    Controller.addToCart(itemObject);
                }

                Controller.updateCartPrice();

            });

            /**
             * Cart - Remove item from cart
             */
            $("#cart_items").on("click", ".jsDeleteFromCart", function(event){

                var button = $(event.target);

                var removeKey = button.data("itemkey");

                var newCart = [];

                var cart = Controller.shoppingCart;
                var cartItem;
                for(var n in cart){
                    if (cart.hasOwnProperty(n)) {
                        cartItem = cart[n];
                        if(cartItem.uniqueKey != removeKey){
                            newCart.push(cart[n]);
                        }
                    }
                }

                Controller.shoppingCart = newCart;

                $("#cart-item-"+removeKey).remove();

                Controller.updateCartPrice();
            });

            /**
             * Cart - Show modal dialog if the user really wants to buy
             */
            $("#cart_price").on("click", ".jsStoreCheckout", function(event){

                event.preventDefault();

                Controller.updateCartPrice();

                var modalTemplate = Controller.getTemplate("store_checkout");

                var sumPrice = $("#vp_price").html()*1;

                var hasError = false;

                if(sumPrice > Controller.vp){
                    hasError = true;
                }

                var modalHtml = modalTemplate({
                    url: Config.URL,
                    error: hasError,
                    vp_sum: sumPrice,
                    lang: mapStatic.lang.store
                });

                $("#modalCheckout").html(modalHtml)
                    .modal('show');

            });

            /**
             * Store - Confirm Buy
             *
             * found in store_checkout.handlebars
             */
            $("#store_wrapper").on("click", ".jsStorePay", function(event){

                event.preventDefault();

                var modal = $("#modalCheckout");
                var modalBody = modal.find(".modal-body");
                var modalFooter = modal.find(".modal-footer");

                if(modal.hasClass("disabled")){
                    return;
                }

                modal.addClass("disabled");
                modalFooter.hide();
                modalBody.html("<h3>"+mapStatic.lang.loading+"</h3>"+'<br><img src="/application/themes/shattered/images/uber-loading.gif">');

                var cartList = JSON.stringify(Controller.shoppingCart);

                $.post(Config.URL + "store/pay", {
                        data: cartList,
                        csrf_token_name: Config.CSRF
                    },
                    function(data){

                        modal.modal("hide");
                        modal.removeClass("disabled");

                        var template = null;
                        var alertHtml = '';

                        if(data.type == "error"){
                            template = Controller.getTemplate("alert");
                            alertHtml = template({
                                type: "danger",
                                message: data.msg
                            });
                            $("#checkout").html(alertHtml).fadeIn(150);

                        }
                        else if(data.type == "success"){
                            modal.modal("hide");
                            template = Controller.getTemplate("alert");
                            alertHtml = template({
                                type: "success",
                                message: data.msg
                            });
                            $("#checkout").html(alertHtml).fadeIn(150);
                            $("#store_realms, #cart").fadeOut(300);
                        }
                        else{
                            modal.html(data);
                        }
                    }, "json")
                    .fail(function(data) {
                        modal.modal("hide");
                        modal.removeClass("disabled");
                        $("#checkout").html(data.responseText).fadeIn(150);
                    });
            });

        },


        clickRemoveItem: function(button){

        },

        initWikiRelated: function(wrapperId, options){
            debug.debug("PageController.initWiki");
            Wiki.related[wrapperId] = new WikiRelated(wrapperId, options);
        },

        addToCart: function(itemObject){
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

        updateActiveChar: function(){
            var activeChar = $("#selected-character");

            if(activeChar.length !== 0){
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

        updateCartPrice: function(){
            var sum = 0;
            var countAll = 0;
            var cart = this.shoppingCart;
            for(var id in cart){
                if (cart.hasOwnProperty(id)) {
                    var item = cart[id];
                    countAll += item.count;
                    sum += item.vp_price * item.count;
                }
            }
            $("#vp_price").html(sum);
            $("#cart_item_count").html(countAll);

            if(countAll > 0){
                $("#empty_cart").fadeOut(300);
                $("#cart_price").fadeIn(300);
            }
            else{
                $("#cart_price").fadeOut(300);
                $("#empty_cart").fadeIn(300);
            }
        },

        /**
         * Checks if a Store Entry is already in the cart for the specified Character
         * @param itemObject
         * @returns {boolean}
         */
        isInCart: function(itemObject){

            var cartItem;
            var cart = this.shoppingCart;

            for(var n in cart){
                if (cart.hasOwnProperty(n)) {
                    cartItem = cart[n];
                    // Realm needs no check because the storeEntryId is unique for each realm
                    if(cartItem.uniqueKey == itemObject.uniqueKey){
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
        increaseCount: function(itemObject){

            var cartItem;
            var cart = this.shoppingCart;

            for(var n in cart){
                if (cart.hasOwnProperty(n)) {
                    cartItem = cart[n];
                    // Realm needs no check because the storeEntryId is unique for each realm
                    if(cartItem.uniqueKey == itemObject.uniqueKey){

                        cartItem.count++;
                        $("#cart-quantity-"+cartItem.uniqueKey+" span").html("x"+cartItem.count);
                        return true;
                    }
                }
            }

            return false;
        },

        getTemplate: function(templateName){
            if(typeof Handlebars.templates[templateName] == "undefined"){
                debug.error("Template "+templateName+" not found.");
                return {};
            }
            return Handlebars.templates[templateName];
        }

    });

    return StoreController;
});
