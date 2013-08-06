<div id="wiki" class="wiki wiki-item">
    <div class="sidebar">
        <div class="snippet">
            <div class="model-viewer">
                <div class="model can-drag" id="model-{$entry}">
                    <div class="loading">
                        <div class="viewer" style="background-image: url(/application/themes/shattered/images/items/item{$entry}.jpg); background-position: 0px 0px; "></div>
                    </div>
                    <a href="javascript:;" class="rotate"></a>
                </div>
            </div>
        </div>
        {if false}
            <div class="snippet">
                <h3>Schnellinfos</h3>
                <ul class="fact-list">
                    <!--<li> <span class="term">Entzaubern:</span> Erfordert Verzauberkunst (375) </li>-->
                </ul>
            </div>
        {/if}
        <div class="snippet">
            <h3>Erfahrt mehr</h3>
			<span id="fansite-links" class="fansite-group">
				<a href="http://de.wowhead.com/item={$entry}" target="_blank">Wowhead</a> 
				<a href="http://de.wow.wikia.com/wiki/<?=$item->name?>" target="_blank">Wowpedia</a> 
				<a href="http://wowdata.buffed.de/?i={$entry}" target="_blank">Buffed.de</a>
			</span>
        </div>
    </div>
    <div class="info">
        <div class="item-detail">
		    {$item}
        </div>
    </div>
    <span class="clear"><!-- --></span>
    <div class="related">
        <div class="tabs ">
            <ul id="related-tabs">
                {if count($source.creature) > 0}
                    <li>
                        <a href="/item/{$realm}/{$entry}/#dropCreatures" data-key="dropCreatures" id="tab-dropCreatures" class="tab-active">
                            <span><span>Erhältlich von ({count($source.creature)})</span></span>
                        </a>
                    </li>
                {/if}
                {if count($source.vendor) > 0}
                    <li>
                        <a href="/item/{$realm}/{$entry}/#vendors" data-key="vendors" id="tab-vendors" class="tab-active">
                            <span><span>H&auml;ndler ({count($source.vendor)})</span></span>
                        </a>
                    </li>
                {/if}
            </ul>
            <span class="clear"><!-- --></span>
        </div>
        <div id="related-content" class="">
            <!-- -->
        </div>
    </div>
</div>
<script type="text/javascript">
    require([
        'static',
        'controller/PageController',
        'tooltip'
    ],
            function (static, PageController, Tooltip) {

                $(function () {
                    debug.debug("js/item");

                    var controller = new PageController();

                    Item.model = new ModelRotator("#model-{$entry}", {
                        zoom: false
                    });
                    Wiki.pageUrl = '/item/{$realm}/{$entry}/';

                    controller.initWiki('migrations', {
                        paging: true,
                        results: 100,
                        column: 1,
                        method: 'numeric',
                        type: 'desc'
                    });

                });
            });
</script>