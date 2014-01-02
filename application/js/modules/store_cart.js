/*jshint -W041, -W083*/

define(function(){
    var Cart = {

        items: {},
        list: [],
        vpCost: 0,
        dpCost: 0,
        count: 0,

        /**
         * Add an item to the cart
         * @param Int id
         * @param Int itemId
         * @param String name
         * @param Int price
         * @param String priceType
         * @param String realm
         */
        add: function(id, itemId, name, price, priceType, realm, realmId, quality, tooltip)
        {
            name = name.replace(/\//, "");
            realm = realm.replace(/\//, "");

            Store.Customer.subtract(price, priceType, function()
            {
                if(priceType == "vp")
                {
                    Store.Cart.vpCost += price;
                }
                else if(priceType == "dp")
                {
                    Store.Cart.dpCost += price;
                }

                var itemObject = {
                    id: id,
                    price: price,
                    priceType: priceType
                };

                Store.Cart.list.push({id:id, type:priceType});

                Store.Cart.updatePrice();

                $("#cart_item_count").html(Store.Cart.list.length);

                var isInCart = Store.Cart.isInCart(id, priceType);

                if(isInCart)
                {
                    var countField = $("#cart_item_count_" + isInCart),
                        itemCount;

                    if(countField.html().length == 0)
                    {
                        itemCount = 2;
                    }
                    else
                    {
                        itemCount = parseInt(countField.html().replace("x ", ""),10) + 1;
                    }

                    countField.html("x " + itemCount);
                    Store.Cart.count++;
                }
                else
                {
                    Store.Cart.items[Store.Cart.count] = itemObject;

                    $("#empty_cart").fadeOut(50, function()
                    {
                        var tooltipHTML = (tooltip) ? 'data-realm="' + realmId +'" rel="item=' + itemId +'"' : '';

                        var itemHTML = '<article class="store_item" id="cart_item_' + Store.Cart.count + '">' +
                            '<div class="item_price">' +
                            '<img src="' + Config.URL + 'application/images/icons/' + ((priceType == "vp") ? "lightning" : "coins") + '.png" align="absmiddle" />' +
                            price + " " + ((priceType == "vp") ? lang("vp", "store") : lang("dp", "store")) +
                            '</div>' +
                            '<a href="javascript:void(0)" onClick="Store.Cart.remove(' + id + ', ' + Store.Cart.count + ')" class="delete_item">' +
                            '<img src="' + Config.URL + 'application/images/icons/delete.png" align="absmiddle" />' +
                            '</a>' +
                            '<a href="' + Config.URL + 'item/' + realmId + '/' + itemId + '" class="item_name q' + quality  +'" ' + tooltipHTML + '>' +
                            name + ' <span id="cart_item_count_' + Store.Cart.count + '"></span>' +
                            '</a>' +
                            '<div class="item_realm">' + realm + '</div>' +
                            '<div class="clear"></div>' +
                            '</article>';

                        $("#cart_items").append(itemHTML);
                        $("#cart_item_" + Store.Cart.count).slideDown(150, function()
                        {
                            Tooltip.refresh();
                        });

                        Store.Cart.count++;
                    });
                }
            });
        },

        /**
         * Check if the item exists in the cart already
         * @param Int id
         * @param String priceType
         * @return Boolean
         */
        isInCart: function(id, priceType)
        {
            for(var i in this.items)
            {
                try
                {
                    if(id == this.items[i].id &&
                        priceType == this.items[i].priceType)
                    {
                        return i;
                    }
                }
                catch(error)
                {
                    // ...
                }
            }

            return false;
        },

        /**
         * Remove an item from the cart
         * @param Int id
         * @param Int countId
         */
        remove: function(id, countId)
        {
            var priceType;
            var itemCount = 0;

            try
            {

                if($("#cart_item_count_" + countId).html().length != 0)
                {
                    itemCount = parseInt($("#cart_item_count_" + countId).html().replace("x ", ""),10);
                }
                else
                {
                    itemCount = 1;
                }

                this[this.items[countId].priceType + "Cost"] -= this.items[countId].price * itemCount;

                // Return the customer money
                Store.Customer.add(this.items[countId].price * itemCount, this.items[countId].priceType);

                priceType = this.items[countId].priceType;

                // Delete the object
                this.items[countId] = null;
            }
            catch(error)
            {
                // ...
            }

            // Remove it from the list array
            var n;

            for(var i = 0;i < itemCount;i++)
            {
                for(n in Store.Cart.list)
                {
                    if(Store.Cart.list[n].id == id &&
                        (Store.Cart.list[n].type == priceType || typeof priceType == "undefined"))
                    {
                        this.list.splice(n, 1);
                    }
                }
            }

            this.updatePrice();

            $("#cart_item_count").html(Store.Cart.list.length);

            // Remove the element
            $("#cart_item_" + countId).slideUp(150, function()
            {
                $(this).remove();

                if(Store.Cart.list.length == 0)
                {
                    setTimeout(function()
                    {
                        if(Store.Cart.list.length == 0)
                        {
                            $("#empty_cart").fadeIn(150);
                        }
                    }, 500);
                }
            });
        },

        /**
         * Submit the cart values to the back end
         * @param Element button
         */
        checkout: function(button)
        {
            var cartList = JSON.stringify(this.list);

            // Change the button state to "Loading..."
            $(button).addClass("nice_active").removeAttr("href").removeAttr("onClick").html("Loading...");

            // Submit to the back end
            $.post(Config.URL + "store/checkout", {cart: cartList, csrf_token_name: Config.CSRF}, function(data)
            {
                $("#store").fadeOut(150, function()
                {
                    // Restore the button
                    $(button).removeClass("nice_active").attr("href", "javascript:void(0)").attr("onClick", "Store.Cart.checkout(this)").html(lang("checkout", "store"));

                    // Display the content
                    $("#checkout").html(data).fadeIn(150, function()
                    {
                        Tooltip.refresh();
                    });
                });
            });
        },

        /**
         * Submit the cart and the characters to the final payment gate
         */
        pay: function()
        {
            UI.confirm(lang("want_to_buy", "store"), lang("yes", "store"), function()
            {
                $("[data-id]").attr("data-available", "1");

                for(var i in Store.Cart.list)
                {
                    if(typeof Store.Cart.list[i].character != "undefined")
                    {
                        delete Store.Cart.list[i].character;
                    }

                    $("[data-id=\"" + Store.Cart.list[i].id + "\"]").each(function()
                    {
                        if($(this).attr("data-available") == "1" &&
                            typeof Store.Cart.list[i].character == "undefined")
                        {
                            $(this).attr("data-available", "0");
                            Store.Cart.list[i].character = $(this).val();
                            console.log(Store.Cart.list[i].character);
                        }
                    });
                }

                var data = JSON.stringify(Store.Cart.list);

                $("#checkout").fadeOut(150, function()
                {
                    $("#checkout").html('<center><img src="' + Config.image_path + 'ajax.gif" /></center>').fadeIn(150, function()
                    {
                        $.post(Config.URL + "store/pay", {data: data, csrf_token_name: Config.CSRF}, function(data)
                        {
                            $("#store_wrapper").fadeOut(150, function()
                            {
                                $("#store_wrapper").html(data).fadeIn(150, function()
                                {
                                    // Reset cart after successful or failed purchase
                                    Store.Cart.items= {};
                                    Store.Cart.list = [];
                                    Store.Cart.vpCost = 0;
                                    Store.Cart.dpCost = 0;
                                    Store.Cart.count = 0;

                                    Store.updatePrice();
                                    $("#cart_item_count").html('0');
                                });
                            });
                        });
                    });
                });
            });
        },

        /**
         * Display the total price
         */
        updatePrice: function(){
            // Hide the total cost if cart is empty
            if(this.list.length == 0){
                this.vpCost = 0;
                this.dpCost = 0;

                setTimeout(function(){
                    $("#cart_price").fadeOut(300);
                }, 350);
            }

            // Show the total cost if cart is not empty
            else if(!$("#cart_price").is(":visible")){
                setTimeout(function(){
                    $("#cart_price").fadeIn(300);
                }, 350);
            }

            var vp = $("#vp_price");
            var dp = $("#dp_price");

            if(vp.html() != this.vpCost)
            {
                vp.fadeTo(300, 0, function()
                {
                    vp.html(Store.Cart.vpCost);
                    vp.fadeTo(300, 1);
                });
            }

            if(dp.html() != this.dpCost)
            {
                dp.fadeTo(300, 0, function()
                {
                    dp.html(Store.Cart.dpCost);
                    dp.fadeTo(300, 1);
                });
            }
        },

        /**
         * Return to the original store list view
         */
        back: function()
        {
            $("#checkout").fadeOut(150, function()
            {
                $("#store").fadeIn(150);
            });
        }
    };

    return Cart;
});