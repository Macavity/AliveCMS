
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

            this.initBindings();

            this.updateActiveChar();
        },

        initBindings: function(){

            var Controller = this;

            $("#related-content").on("click", ".jsPutToCart", function(event){
                debug.debug("jsPutToCart Event");

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
                    setTimeout(function(){button.popover('destroy')}, 4000);
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
                    setTimeout(function(){button.popover('destroy')}, 4000);
                    return;
                }

                var itemObject = {
                    id: button.data("id"),
                    vp_price: button.data("price"),
                    itemid: button.data("itemid"),
                    icon: button.data("icon"),
                    name: button.data("name"),
                    type: "vp"
                };

                if(Controller.isInCart(itemObject.id)){
                    Controller.increaseCount(itemObject.id);
                }
                else{
                    Controller.addToCart(itemObject);
                }

                Controller.updateCartPrice();

            });

            $("#cart_items").on("click", ".jsDeleteFromCart", function(event){

                var button = $(event.target);

                var removeId = button.data("item");

                var newCart = [];

                var cart = Controller.shoppingCart;
                for(var id in cart){
                    if (cart.hasOwnProperty(id)) {
                        if(id != removeId){
                            newCart[id] = cart[id];
                        }
                    }
                }

                Controller.shoppingCart = newCart;

                $("#cart-item-"+removeId).remove();

                Controller.updateCartPrice();
            });

            $("#cart_price").on("click", ".jsStorePay", function(event){

                event.preventDefault();

                var button = $(event.target).parent().parent();

                if(button.hasClass("disabled")){
                    return;
                }

                var previousButtonHtml = button.html();

                button
                    .addClass("disabled")
                    .html("<span><span>"+mapStatic.lang.loading+"</span></span>");

                var cartList = JSON.stringify(Controller.shoppingCart);

                $.post(Config.URL + "store/checkout", {
                    cart: cartList,
                    csrf_token_name: Config.CSRF
                }, function(data){

                    // Restore the button
                    button
                        .removeClass("disabled")
                        .html(previousButtonHtml);

                    if(data.type == "error"){
                        var template = Controller.getTemplate("alert");
                        var alertHtml = template({
                            type: "danger",
                            message: data.msg
                        });
                        $("#checkout").html(alertHtml).fadeIn(150);

                    }
                    else if(data.type == "success"){
                        $("#store").fadeOut(150);
                        $("#checkout").html(data.content).fadeIn(150);
                    }

                }, "json");

            });
        },

        initWikiRelated: function(wrapperId, options){
            debug.debug("PageController.initWiki");
            Wiki.related[wrapperId] = new WikiRelated(wrapperId, options);
        },

        addToCart: function(itemObject){
            this.updateActiveChar();
            this.shoppingCart[itemObject.id] = itemObject;
            this.shoppingCart[itemObject.id].count = 1;

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

            if(activeChar.length != 0){
                this.activeChar = {
                    name: activeChar.data("name"),
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

        isInCart: function(id){
            return (typeof this.shoppingCart[id] == "undefined")
                ? false
                : true;
        },

        increaseCount: function(id){
            this.shoppingCart[id].count++;
            $("#cart-quantity-"+id+" span").html("x"+this.shoppingCart[id].count);
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
