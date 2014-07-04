<div class="related-content" id="related-{$realm_id}">
    <div class="filters inline">
        <div class="keyword">
            <span class="view"></span>
            <span class="reset" style="display: none"></span>
            <input id="filter-name-loot" type="text" class="input filter-name" data-filter="row" maxlength="25" title="Filter..." value="Filter..." /><br/>
        </div>
        <div class="filter-tabs">
            <a href="javascript:;" data-filter="column" data-column="2" data-value="" data-name="type" class="tab-active"> Alle </a>
            {foreach from=$groups item=group}
                <a href="javascript:;" data-filter="column" data-column="2" data-name="type" data-value="{$group.id}">{$group.name}</a>
            {/foreach}
        </div>
        <span class="clear"><!-- --></span>
    </div>
    <div class="data-options-top">
        <div class="table-options data-options ">
            <div class="option">
                <ul class="ui-pagination"></ul>
            </div>
            Zeige <strong class="results-start">{min(1,$count)}</strong>–<strong class="results-end">{min(50,$count)}</strong> von <strong class="results-total">{$count}</strong> Ergebnissen <span class="clear"><!-- --></span>
        </div>
    </div>
    <div class="table full-width">
        <table>
            <thead>
                <tr>
                    <th class="align-center">&nbsp;</th>
                    <th> <a href="javascript:;" class="sort-link default"><span class="arrow">Name</span></a></th>
                    <th> <a href="javascript:;" class="sort-link numeric"><span class="arrow">Gruppe</span></a></th>
                    <th style="width:90px"> <a href="javascript:;" class="sort-link numeric"><span class="arrow">Preis</span></a></th>
                    <th> <a href="javascript:;" class="sort-link numeric">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            {foreach from=$groups item=group}
                {foreach from=$group.items item=item}
                <tr class="{cycle values="row1,row2"}">
                    <td data-raw="{$item.itemid}"><a href="/item/{$realm_id}/{$item.itemid}" class="item-link" target="_blank"><span class="icon-frame frame-36" style="background-image: url(/application/images/icons/36/{$item.icon}.jpg);"></span></a></td>
                    <td class="align-center color-q{$item.quality}" data-raw="{$item.name}"><strong>{$item.name}</strong></td>
                    <td data-raw="{$group.id}">{$group.name}</td>
                    <td data-raw="{$item.vp_price}">
                        <img src="{$url}application/images/icons/lightning.png" /> {$item.vp_price}
                    </td>
                    <td>
                        <img src="{$url}application/images/icons/cart_put.png" class="jsPutToCart storeIntoCartButton" data-realm="{$realm_id}" data-price="{$item.vp_price}" data-name="{$item.name}" data-icon="{$item.icon}" data-id="{$item.id}" data-itemid="{$item.itemid}">
                    </td>
                </tr>
                {/foreach}
            {/foreach}
            <tr class="no-results">
                <td colspan="7" class="align-center"> Keine Ergebnisse gefunden. </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="data-options-bottom">
        <div class="table-options data-options ">
            <div class="option">
                <ul class="ui-pagination">
                </ul>
            </div>
            Zeige <strong class="results-start">{min(1,$count)}</strong>–<strong class="results-end">{min(50,$count)}</strong> von <strong class="results-total">{$count}</strong> Ergebnissen <span class="clear"><!-- --></span> </div>
    </div>
</div>
