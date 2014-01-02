
<style type = "text/css">
	.data-options {
		color: #855B47;
		padding: 0 10px;
		height: 40px;
		line-height: 40px;
		background: url("http://eu.battle.net/wow/static/images/content/table-options-bg.jpg") 50% 0 no-repeat;
	}
	.table thead th {
		padding: 0;
		background: #4D1A08 url("{$theme_path}images/table-header.gif") 0 100% repeat-x;
		border-bottom: 1px solid #1A0F08;
		border-left: 0px solid #7C2804;
		border-right: 0px solid #391303;
		border-top: 0px solid #7C2804;
		white-space: nowrap;
	}
	.table thead th span { padding-left: 10px; }
	.wiki .related { background: none; }
</style>

<div class="top-banner">
    <div class="section-title">
        <span>Spieler Online</span>
    </div>
    <span class="clear"><!-- --></span>     
</div>

<div class="wiki">
  {foreach item=realm from=$realms name=onlinerealms}
    <div class="title">
      <h2>Realm: {$realm.name}</h2>
    </div>
    <div class="related">
      <span class="clear"><!-- --></span>
			
      <div class="related-content" id="related-realm-{$realm.id}">

        <div class="filters inline">
          <div class="keyword"> <span class="view"></span> <span class="reset" style="display: none"></span>
            <input id="filter-name-loot" type="text" class="input filter-name" data-filter="row" maxlength="25" title="Filter..." value="Filter..." />
          </div>
          <div class="filter">
            <label for="filter-class">Zeige Klasse</label>
            <select class="input select filter-class" data-filter="class" data-name="class">
              <option value="">Alle Klassen</option>
              <option value="class-11">Druide</option>
              <option value="class-9">Hexenmeister</option>
              <option value="class-3">Jäger</option>
              <option value="class-1">Krieger</option>
              <option value="class-8">Magier</option>
              <option value="class-2">Paladin</option>
              <option value="class-5">Priester</option>
              <option value="class-7">Schamane</option>
              <option value="class-4">Schurke</option>
              <option value="class-6">Todesritter</option>
            </select>
          </div>
          <div class="filter" style="padding-top: 3px;">
            <label for="filter-is80">
              <input id="filter-is80" type="checkbox" class="input checkbox filter-is80" data-name="is80" data-filter="class" data-value="is-80" />
              <span class="help-inline">nur 80er </span>
            </label>
          </div>
          <span class="clear"><!-- --></span>
        </div>
        <div class="data-options-top">
          <div class="table-options data-options ">
            <div class="option">
              <ul class="ui-pagination"></ul>
            </div>
            Zeige <strong class="results-start">1</strong>–<strong class="results-end">{$realm.shownCount}</strong> von <strong class="results-total">{$realm.count}</strong> Ergebnissen <span class="clear"><!-- --></span>
          </div>
        </div>
        <div class="table full-width">
        <table cellpadding="3" cellspacing="0" width='100%'>
          <thead>
            <tr>
              <th> <a href="javascript:;" class="sort-link numeric"> <span class="arrow">#</span> </a> </th>
              <th> <a href="javascript:;" class="sort-link default"> <span class="arrow">Name</span> </a> </th>
              <th class="align-center"> <a href="javascript:;" class="sort-link numeric"> <span class="arrow">Rasse</span> </a> </th>
              <th class="align-center"> <a href="javascript:;" class="sort-link numeric"> <span class="arrow">Klasse</span> </a> </th>
              <th> <a href="javascript:;" class="sort-link numeric"> <span class="arrow">Level</span> </a> </th>
              <th> <a href="javascript:;" class="sort-link"> <span class="arrow">Standort</span> </a> </th>
            </tr>
          </thead>
          <tbody>
          {foreach item=row from=$realm.characters name=onlinelist}
          <tr class="{$row.css} {cycle values="row1,row2"}">
            <td align="center" data-raw="{$smarty.foreach.onlinelist.index+1}">{$smarty.foreach.onlinelist.index+1}</td>
            <td align="center" data-raw="{strtolower($row.name)}">
              <a href="/character/Norgannon/{$row.name}/">
                {$row.name}
              </a>
            </td>
            <td align="center" data-raw="{$row.race}">
                      <span class="icon-frame frame-18" data-tooltip="{$row.class_name}">
                          <img src="{$theme_path}images/icons/18/race_{$row.race}_{$row.gender}.jpg" height="18" width="18">
                      </span>
                  </td>
            <td align="center" data-raw="{$row.class}">
                      <span class="icon-frame frame-18" data-tooltip="{$row.class_name}">
                          <img src="{$theme_path}images/icons/18/class_{$row.class}.jpg" height="18" width="18">
                      </span>
                  </td>
            <td align="center" data-raw="{$row.level}">{$row.level}</td>
            <td align="center" data-raw="{strtolower($row.zone)}">{$row.zone}</td>
          </tr>
              {foreachelse}
              <tr class="no-results">
                  <td colspan="7" class="align-center"> Keine Ergebnisse gefunden. </td>
              </tr>
              {/foreach}
          </tbody>
        </table>
        </div>
        <div class="data-options-bottom">
          <div class="table-options data-options ">
            <div class="option">
              <ul class="ui-pagination">
              </ul>
            </div>
            Zeige <strong class="results-start">1</strong>–<strong class="results-end">{$realm.shownCount}</strong> von <strong class="results-total">{$realm.count}</strong> Ergebnissen <span class="clear"><!-- --></span> </div>
        </div>
      </div>

		  <span class="clear"><!-- --></span>
	  </div>
  {/foreach}
</div>
<script type="text/javascript" language="javascript">
  require([
    'static',
    'modules/wiki',
    'modules/wiki_related',
    'modules/table',
    'modules/filter',
    'modules/zone'
  ],
  function (static, Wiki, WikiRelated, Table, Filter, Zone) {

    $(function () {
      debug.debug("/server/playersonline");

      Zone.initialize();

      Wiki.pageUrl = '/server/playersonline/';
      {foreach item=realm from=$realms}
      Wiki.related['realm-{$realm.id}'] = new WikiRelated('realm-{$realm.id}', {
        paging: true,
        totalResults: {$realm.count},
        column: 0,
        method: 'numeric',
        type: 'asc'
      });
      {/foreach}

    });
  });


</script>

