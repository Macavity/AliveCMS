<section id="store_wrapper">


    <section id="store">
        <section id="store_content" class="wiki row">
            <div class="title col-md-12">
                <h2>Vote Shop</h2>
            </div>

            <section id="checkout" class="col-md-12"></section>

            <div class="alert alert-info col-md-12">
                Der Shop orientiert sich an deinem ausgewählten Charakter. Du kannst den Charakter wechseln und so auf einen Schlag mehrere Gegenstände für verschiedene Charaktere einkaufen.
            </div>

            <div class="row">

                <!-- Left Side -->
                <section id="store_realms" class="related col-md-9">

                    <div class="tabs">
                        <ul id="related-tabs">
                            {foreach from=$data item=realm key=realmId}
                                <li>
                                    <a href="/store/" data-key="{$realmId}">
                                        <span><span>{$realm.name}</span></span>
                                    </a>
                                </li>
                            {/foreach}
                        </ul>
                        <span class="clear"><!-- --></span>
                    </div>
                    <div id="related-content" class="loading">
                        <!-- -->
                    </div>

                </section>

                <!-- Right Side -->
                <section id="cart" class="sidebar col-md-3">

                    <div class="snippet">
                        Dein Guthaben: <img src="{$url}application/images/icons/lightning.png" align="absmiddle" /> {$vp} VP
                    </div>

                    <div class="online_realm_button snippet">
                        {lang("cart", "store")} (<span id="cart_item_count">0</span> {lang("items", "store")})
                    </div>
                    <div id="empty_cart" class="snippet">
                        {lang("empty_cart", "store")}
                    </div>

                    <section id="cart_items" class="snippet">

                    </section>

                    <div id="cart_price" class="snippet">
                        <div id="cart_price_divider"></div>

                        <a href="#" class="ui-button button1 button1-next jsStoreCheckout"><span><span>{lang("checkout", "store")}</span></span></a>
                        <div id="vp_price_full">
                            <img src="{$url}application/images/icons/lightning.png" align="absmiddle" /> <span id="vp_price">0</span> VP
                        </div>

                        <div class="clear"></div>
                    </div>
                </section>

            </div>



        </section>

    </section>

    <div id="modalCheckout" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">

    </div>

</section>
<script type="text/javascript">
    require([
        'static',
        'controller/StoreController'
    ],
    function (static, StoreController) {

        $(function () {
            var controller = new StoreController({$vp});

        });
    });
</script>

