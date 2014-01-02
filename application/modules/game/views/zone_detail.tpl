<style type="text/css">
  #content .content-top { background: url("{$image_path}zone/bgs/{$zone.label}.jpg") 0 0 no-repeat; }

  .table thead th {
    padding: 0;
    background: #4D1A08 url("../images/table-header.gif") 0 100% repeat-x;
    border-bottom: 1px solid #1A0F08;
    border-left: 0px solid #7C2804;
    border-right: 0px solid #391303;
    border-top: 0px solid #7C2804;
    white-space: nowrap;
  }

</style>

<div id="wiki" class="wiki wiki-zone">
  <div class="sidebar">
    <table class="media-frame">
      <tr>
        <td class="tl"></td>
        <td class="tm"></td>
        <td class="tr"></td>
      </tr>
      <tr>
        <td class="ml"></td>
        <td class="mm">
          <a href="javascript:;" class="thumbnail" onClick="Lightbox.loadImage([{ src: '{$image_path}zone/screenshots/{$zone.label}.jpg' }]);">
            <span class="view"></span>
            <img src="{$image_path}zone/thumbnails/{$zone.label}.jpg" width="265" alt="" />
          </a>
        </td>
        <td class="mr"></td>
      </tr>
      <tr>
        <td class="bl"></td>
        <td class="bm"></td>
        <td class="br"></td>
      </tr>
    </table>
    <div class="snippet">
      <h3>Schnellinfos</h3>
      <ul class="fact-list">
        <li> <span class="term">Typ:</span> {$zone.type}</li>
        <li> <span class="term">Spieler:</span> {$zone.size}</li>
        <li> <span class="term">Stufe:</span> {$zone.level}</li>
        {if $zone.location}
          <li> <span class="term">Ort:</span> {$zone.location}</li>
        {/if}
        {if $zone.patch}
          <li> <span class="term">Eingeführt mit Patch:</span> {$zone.patch}</li>
        {/if}
        {if $zone.isHeroic && $zone.heroicClosed}
          <li class="color-tooltip-red">Heroischer Modus geschlossen <span class="icon-heroic-skull"></span></li>
        {elseif $zone.isHeroic}
          <li>Heroischer Modus verfügbar <span class="icon-heroic-skull"></span></li>
        {/if}
      </ul>
    </div>
    <div class="snippet">
      <h3>Karte</h3>
      <table class="media-frame">
        <tr>
          <td class="tl"></td>
          <td class="tm"></td>
          <td class="tr"></td>
        </tr>
        <tr>
          <td class="ml"></td>
          <td class="mm">
            <a href="javascript:;" id="map-floors" class="thumbnail" style="background: url({$url}/images/zone/maps/{$zone.label}.jpg) 0 0 no-repeat;">
              <span class="view"></span>
            </a>
          </td>
          <td class="mr"></td>
        </tr>
        <tr>
          <td class="bl"></td>
          <td class="bm"></td>
          <td class="br"></td>
        </tr>
      </table>
      <script type="text/javascript">
        $(function() {
          Zone.floors = [
            {foreach from=$zone.floors key=key item=title}
              { title: "{$title}", src: "../images/zone/maps-large/{$zone.label}.{$key}-large.jpg" },
            {/foreach}
          ];
        });
      </script>
      <div class="radio-buttons" id="map-radios">
        {foreach from=$zone.floors key=key item=title}
          <a href="javascript:;" id="map-radio-{$key}" data-id="{$key}" data-tooltip="{$title}"> </a>
        {/foreach}
      </div>
    </div>
    <div class="snippet">
      <h3>Erfahrt mehr</h3>
			<span id="fansite-links" class="fansite-group">
				<a href="http://de.wowhead.com/zone={$zone.id}" target="_blank">Wowhead</a>
				<a href="http://de.wow.wikia.com/wiki/{str_replace(" ","_",html_entity_decode($zone.name))}" target="_blank">Wowpedia</a>
        <a href="http://wowdata.buffed.de/?zone={$zone.id}" target="_blank">Buffed.de</a>
			</span>
    </div>
  </div>
  <div class="info">
    <div class="title">
      <h2>{$zone.name}</h2>
      {if $zone.expansion == 2}
        <span class="expansion-name color-ex2"> Benötigt Wrath of the Lich King </span>
      {elseif $zone.expansion == 1}
        <span class="expansion-name color-ex1"> Benötigt The Burning Crusade </span>
      {/if}
    </div>
    <p class="intro"> {$zone.intro} </p>
    <div class="lore">
      <p>{$zone.lore} </p>
    </div>
    <div class="panel">
      <div class="panel-title">Bosse</div>
      <div class="zone-bosses">
        {if $zone.hasWings}
          {foreach from=$zone.wings item=wing name=wings}
            {if $smarty.foreach.wings.index % 2 == 0}
              <div class="boss-column-portrait">
            {/if}
                <span class="wing-name">{$wing.name}</span>

                {foreach from=$wing.bosses item=boss}
                <div class="boss-avatar">
                  <a href="/game/zone/{$zone.label}/{$boss.label}" data-npc="{$boss.id}">
                    <span class="boss-portrait" style="background-image: url('../images/npcs/creature{$boss.id}.jpg');"> </span>
                    <span class="boss-details">
                      <div class="boss-name">{$boss.name}</div>
                      {if $boss.closed}
                        <div class="color-tooltip-red">Dieser Kampf ist geschlossen.</div>
                      {/if}
                    </span>
                  </a>
                </div>
                {/foreach}
            {if $smarty.foreach.wings.index % 2 == 1 || $smarty.foreach.wings.last}
              </div>
              <span class="clear"><!-- --></span>
            {/if}

          {/foreach}
        {else}
          {foreach from=$zone.bosses item=boss}
            {if $smarty.foreach.wings.index % 2 == 0}
              <div class="boss-column-portrait">
            {/if}
            <div class="boss-avatar">
              <a href="/game/zone/{$zone.label}/{$boss.label}" data-npc="{$boss.id}">
              <span class="boss-portrait" style="background-image: url('/templates/Shattered-World/images/npcs/creature{$boss.id}.jpg');"> </span>
                  <span class="boss-details">
                    <div class="boss-name"> {$boss.name} </div>
                    {if $boss.closed}
                      <div class="color-tooltip-red">Dieser Kampf ist geschlossen.</div>{/if}
                  </span>
              </a>
            </div>
            {if $smarty.foreach.wings.index % 2 == 1 || $smarty.foreach.wings.last}
              </div>
              <span class="clear"><!-- --></span>
            {/if}
          {/foreach}
        {/if}
      </div> <!-- /zone-bosses -->
      <span class="clear"><!-- --></span>
    </div>
  </div> <!-- /info -->
  <span class="clear"><!-- --></span>

  <div class="related">
    <div class="tabs ">
      <ul id="related-tabs">
        <li>
          <a href="/game/zone/{$zone.label}/#loot" data-key="loot" id="tab-loot" class="tab-active">
            <span><span> Beute </span></span>
          </a>
        </li>
        <li>
          <a href="/game/zone/{$zone.label}/#bugs" data-key="bugs" id="tab-bugs">
            <span><span> Bekannte Bugs </span></span>
          </a>
        </li>
      </ul>
      <span class="clear"><!-- --></span>
    </div>
    <div id="related-content" class="loading">

    </div>
  </div>
</div>

<script type="text/javascript">
  $(function() {
    Wiki.pageUrl = '/game/zone/{$zone.label}/';
  });
</script>
