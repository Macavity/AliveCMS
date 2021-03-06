<script type="text/javascript">
	function Initialize()
	{
		if (typeof Auctionhouse != "undefined")
		{
			Auctionhouse.Initialize({$realmId}, {$CurrentPage}, {$CurrentFaction}, {if $CurrentSearch_Encoded}'{$CurrentSearch_Encoded}'{else}'null'{/if}, {$CurrentSort});
		}
		else
		{
			setTimeout(Initialize, 100);
		}
	}

	$(document).ready(function()
	{
		Initialize();
	});
</script>
<section id="auction_house">

	<div id="ah-top">
    	<div id="ah-filters">
        
        	<div id="realms">
            {lang("Realm", 'auctionhouse')}:
                <select style="width: 200px;" id="realm-changer" onchange="return Auctionhouse.ChangeRealm();">
                    {foreach from=$realms item=realm}
                        <option value="{$realm.id}" {if $realmId == $realm.id}selected="selected"{/if}>{$realm.name}</option>
                    {/foreach}
                </select>
         	</div>
            
            <div id="factions">
            	{lang("Auction House", 'auctionhouse')}:
                <select style="width: 200px;" id="faction-changer" onchange="return Auctionhouse.ChangeFaction();">
                	<option value="0" {if $CurrentFaction == 0}selected="selected"{/if}>{lang("All", 'auctionhouse')}</option>
                 	<option value="1" {if $CurrentFaction == 1}selected="selected"{/if}>{lang("Alliance", 'auctionhouse')}</option>
                   	<option value="2" {if $CurrentFaction == 2}selected="selected"{/if}>{lang("Horde", 'auctionhouse')}</option>
                    <option value="3" {if $CurrentFaction == 3}selected="selected"{/if}>{lang("Neutral", 'auctionhouse')}</option>
                </select>
            </div>
            
            <div class="clear"></div>
       	</div>
        <div id="ah-search">
        	<form onSubmit="Auctionhouse.Search(); return false;">
                <input type="text" placeholder="Search for specific items" {if $CurrentSearch}value="{$CurrentSearch}"{/if} id="search_field" />
                <input type="submit" value="{lang("Search", 'auctionhouse')}" />
            </form>
        </div>
    </div>
    
    <div id="ah-auctions">
        <table class="nice_table nice_table_mod" cellspacing="0" cellpadding="0">
          <thead>
            <tr id="ah-sortable">
              <td width="40%">{lang("Item", 'auctionhouse')}</td>
              <td width="10%" align="center" class="ah-column-timeleft"><a href="#" data-sort-id="0">{lang("Time Left", 'auctionhouse')}</a></td>
              <td width="15%" align="center"><a href="#" data-sort-id="2">{lang("Seller", 'auctionhouse')}</a></td>
              <td width="20%" align="center"><a href="#" data-sort-id="4">{lang("Current Bid", 'auctionhouse')}</a></td>
              <td width="15%" align="center">{lang("AH", 'auctionhouse')}</td>
            </tr>
          </thead>
            <tr><td colspan="5" style="height:0px; padding:0; margin: 0;"></td></tr>
            
            {if count($auctions) > 0}
                {foreach from=$auctions item=auction key=key}
                    <tr class="tr-{if (($key + 2) % 2) == 0}odd{else}even{/if}{if $auction.UserOwned} ah-user-owned-auction{/if}" {if $auction.UserOwned}{/if}>
                        <td width="40%" class="ah-column-item">
                        	<div class="item-icon">{$auction.itemIcon}{if $auction.stack}<span class="item-stack">{$auction.stack}</span>{/if}</div>
                            <div class="item-name">
                            	<span><a class="q{$auction.itemQuality}" href="{$url}item/{$realmId}/{$auction.itemEntry}" data-realm="{$realmId}" rel="item={$auction.itemEntry}">{$auction.itemName}</a></span>
                            </div>
                            <div class="clear"></div>
                        </td>
                        <td width="10%" align="center" class="ah-column-timeleft">{$auction.timeLeft}</td>
                        <td width="15%" align="center" class="ah-column-owner"><a href="{$url}character/{$realmId}/{$auction.owner}">{$auction.owner}</a></td>
                        <td width="20%" align="center" class="ah-column-prices">
                        	<div class="ah-bid-price" data-tip="{lang("Bid Price", 'auctionhouse')}">{$auction.bidPrice}</div>
                            {if $auction.buyPrice}
                            <div class="ah-buy-price" data-tip="{lang("Buyout Price", 'auctionhouse')}">{$auction.buyPrice}</div>
                            {/if}
                        </td>
                        <td width="15%" align="center">{$auction.auctioneer}</td>
                    </tr>
                {/foreach}
         	{else}
            	<tr><td colspan="5" align="center">{lang("There are no auctions.", 'auctionhouse')}</td></tr>
        	{/if}
        </table>
    </div>
    
    {if $pagination != ''}
        <div id="ah-pagination">
            <center>{$pagination}</center>
        </div>
    {/if}
    
</section>

<!-- Load wowhead tooltip -->
{if !$fcms_tooltip}
	<script type="text/javascript" src="https://static.wowhead.com/widgets/power.js"></script>
    <script>var wowhead_tooltips = { "colorlinks": false, "iconizelinks": false, "renamelinks": false }</script>
{/if}
