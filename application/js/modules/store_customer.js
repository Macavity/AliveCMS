
define(function(){
    var Customer = {

        vp: 0,
            dp: 0,

            initialize: function(vp, dp)
        {
            this.vp = vp;
            this.dp = dp;

            Store.Filter.initialize();
        },

        /**
         * Return points to the customer
         * @param Int price
         * @param String priceType
         */
        add: function(price, priceType)
        {
            this[priceType] += price;

            if($("#info_" + priceType).length)
            {
                $("#info_" + priceType).html(this[priceType]);
            }

            Store.Filter.updatePrices();
        },

        /**
         * Subtract to the customers money
         * @param Int price
         * @param String priceType
         * @param Function callback
         */
        subtract: function(price, priceType, callback)
        {
            var old = this[priceType];

            this[priceType] -= price;

            if(this[priceType] < 0)
            {
                this[priceType] = old;

                UI.alert(lang("cant_afford", "store"));
            }
            else
            {
                if($("#info_" + priceType).length)
                {
                    $("#info_" + priceType).html(this[priceType]);
                }

                Store.Filter.updatePrices();

                callback();
            }
        }
    };

    return Customer;
});