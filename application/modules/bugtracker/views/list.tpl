<a href="{site_url('bugtracker/create')}" class="ui-button button2"><span><span>Neuen Bug eintragen</span></span></a>&nbsp;

<div class="wiki">
    <div class="related">
        <span class="clear"><!-- --></span> 

		<div class="related-content" id="related-loot">
			<div class="filters inline">
				<div class="keyword">
                    <span class="view"></span> 
                    <span class="reset" style="display: none"></span>
					<input id="filter-name-loot" type="text" class="input filter-name" data-filter="row" maxlength="25" title="Filter..." value="Filter..." />
				</div>
				<div class="filter-tabs"> 
					<a href="javascript:;" data-filter="column" data-column="0" data-value="" data-name="type" class="tab-active"> Alle </a> 
					<a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="0"> Offen </a> 
					<a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="1"> In Bearbeitung </a> 
					<a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="2"> Erledigt </a> 
					<a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="3"> Abgewiesen </a> 
				</div>
				<span class="clear"><!-- --></span> 
			</div>
			<div class="data-options-top">
				<div class="table-options data-options">
					<div class="option">
						<ul class="ui-pagination"></ul>
					</div>
					Zeige <strong class="results-start">1</strong>–<strong class="results-end">50</strong> von <strong class="results-total">{$rowCount}</strong> Ergebnissen 
		            <span class="clear"><!-- --></span> 
				</div>
			</div>
			<div class="table full-width">
				<table>
					<thead>
						<tr>
							<th><a href="javascript:;" class="sort-link numeric"><span class="arrow">Status</span></a></th>
							<th class="align-center"><a href="javascript:;" class="sort-link numeric"><span class="arrow">BugID</span></a></th>
							<th class="align-center"><a href="javascript:;" class="sort-link"><span class="arrow">Typ</span></a></th>
							<th><a href="javascript:;" class="sort-link"><span class="arrow">Titel</span></a></th>
							<th><a href="javascript:;" class="sort-link"><span class="arrow">Letzte Änderung</span></a></th>
						</tr>
					</thead>
					<tbody>
					{foreach from=$bugRows key=i item=bug}
						<tr class="{$bug.css}">
							<td data-raw="{$bug.status}">&nbsp;</td>
							<td class="align-center" data-raw="{$bug.id}"><a href="/server/bugtracker/bug/{$bug.id}">#{$bug.id}</a></td>
							<td>{$bug.class}</td>
							<td data-raw="{$bug.title}">{$bug.title}</td>
							<td data-raw="{$bug.changedSort}"><span data-tooltip="Eintragung am {$bug.createdDate}">{$bug.changedDate}</span></td>
						</tr>
					{/foreach}
					</tbody>
				</table>
			</div>
			<div class="data-options-bottom">
				<div class="table-options data-options">
					<div class="option">
						<ul class="ui-pagination">
						</ul>
					</div>
					Zeige <strong class="results-start">1</strong>–<strong class="results-end">50</strong> von <strong class="results-total">{$rowCount}</strong> Ergebnissen 
		            <span class="clear"><!-- --></span>
                </div>
			</div>
		</div>	

		<script type="text/javascript" src="{$js_path}wiki.js?v2"></script>
		<script type="text/javascript" src="{$js_path}zone.js"></script>
		<script type="text/javascript" src="{$js_path}table.js"></script>
		<script type="text/javascript" src="{$js_path}filter.js"></script>
		<script type="text/javascript" src="{$js_path}lightbox.js"></script>
		
		<script type="text/javascript">
		//<![CDATA[
		$(function() {
            Wiki.pageUrl = '/bugtracker/';
			Wiki.related['loot'] = new WikiRelated('loot', {
				paging: true,
				totalResults: {$rowCount},
					column: 4,
					method: 'date',
					type: 'desc'
			});
		});
		//]]>
		</script> 

    </div> <!-- /related -->
</div>