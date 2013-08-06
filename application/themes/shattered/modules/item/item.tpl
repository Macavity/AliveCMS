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
        <div class="title">
            <h2 class="color-q{$item.Quality}">{$item.name}</h2>
        </div>
        <div class="item-detail">
        <span class="icon-frame frame-56" style="background-image: url(/application/themes/shattered/images/icons/56/{$item.icon}.jpg);">
			{if $item.stackable > 1}
                <span class="stack">{$item.stackable}</span>
            {/if}
        </span>
            {if $item.has_counterpart}
                <div class="faction-related">
                    <a href="/item/{$realm}/{$item.counterpart}/" data-tooltip="#faction-tooltip">
                        <span class="icon-frame frame-14"><img src="/application/themes/shattered/images/icons/18/faction_{if $item.faction == 0}1{else}0{/if}.jpg" alt="" width="14" height="14" /></span>
                        <span class="icon-frame frame-14"><img src="/application/themes/shattered/images/icons/18/{$item.counterpart_icon}.jpg" alt="" width="14" height="14" /></span>
                    </a>
                    <div id="faction-tooltip" style="display: none">Dieser Gegenstand wird zu <span class="color-q{$item.Quality}">{$item.counterpart_name}</span> verwandelt, falls ihr zu der {if $item.faction == 0}Horde{else}Allianz{/if} transferiert.</div>
                </div>
            {/if}
            <span id="tooltip-data">
                {$tooltipData}
            </span>
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
        'modules/wiki',
        'modules/model_rotator',
        'modules/item',
        'modules/tooltip'
    ],
    function (static, PageController, Wiki, ModelRotator, Item, Tooltip) {

        $(function () {
            debug.debug("js/item");

            var controller = new PageController();

            Item.model = new ModelRotator("#model-{$entry}", {
                zoom: false
            });
            Wiki.pageUrl = '/item/{$realm}/{$entry}/';

            $.get(Config.URL + "tooltip/{$realm}/{$entry}", function(data){
                $("#tooltip-data").html(data);
            });

        });
    });
</script>