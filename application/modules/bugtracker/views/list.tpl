
<div class="bugtracker-actions">
    <a href="{site_url('bugtracker/index')}" class="ui-button button2 button2-previous"><span><span>Kategorienliste</span></span></a>&nbsp;
  {if hasPermission("canCreateBugs")}
    <a href="{site_url('bugtracker/create')}" class="ui-button button2"><span><span>Neuen Bug eintragen</span></span></a>&nbsp;
  {/if}
</div>


<div id="buglist" class="wiki" data-rowcount="{$rowCount}">
    <div class="related">
        <span class="clear"><!-- --></span> 

		<div class="related-content" id="related-buglist">
			<div class="filters inline">
				<div class="keyword">
                    <span class="view"></span> 
                    <span class="reset" style="display: none"></span>
					<input id="filter-name-buglist" type="text" class="input filter-name" data-filter="row" maxlength="25" title="Filter..." value="Filter..." />
				</div>
				<div class="filter-tabs"> 
					<a href="javascript:;" data-filter="column" data-column="0" data-value="" data-name="type"> Alle </a>
					<a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="1" class="tab-active"> Offen </a>
					<a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="2"> In Bearbeitung </a>
					<a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="9"> Erledigt </a>
					<a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="3"> Abgewiesen </a> 
				</div>
				<span class="clear"><!-- --></span> 
			</div>
			<div class="data-options-top">
				<div class="table-options data-options">
					<div class="option">
						<ul class="ui-pagination"></ul>
					</div>
					Zeige <strong class="results-start">{$rowMin}</strong>–<strong class="results-end">{$rowMax}</strong> von <strong class="results-total">{$rowCount}</strong> Ergebnissen
		            <span class="clear"><!-- --></span> 
				</div>
			</div>
			<div class="table full-width">
				<table>
					<thead>
						<tr>
							<th><a href="javascript:;" class="sort-link numeric"><span class="arrow">Status</span></a></th>
							<th><a href="javascript:;" class="sort-link numeric"><span class="arrow">BugID</span></a></th>
                            <th><a href="javascript:;" class="sort-link numeric"><span class="arrow">P</span></a></th>
							<th><a href="javascript:;" class="sort-link"><span class="arrow">Kategorie</span></a></th>
							<th><a href="javascript:;" class="sort-link"><span class="arrow">Titel</span></a></th>
							<th><a href="javascript:;" class="sort-link"><span class="arrow">Letzte Änderung</span></a></th>
						</tr>
					</thead>
					<tbody>
					{foreach from=$bugRows key=i item=bug}
						<tr class="{$bug.css} {cycle values="row1,row2"}">
							<td data-raw="{$bug.bug_state}">&nbsp;</td>
							<td data-raw="{$bug.id}">
                <a href="/bugtracker/bug/{$bug.id}">#{$bug.id}</a>
              </td>
              <td data-raw="{$bug.priority}">
                <i class="icon {$bug.priorityClass}" data-tooltip="{$bug.priorityLabel}"></i>
              </td>
							<td><a href="/bugtracker/buglist/{$bug.project}">{$bug.type_string}</a></td>
							<td data-raw="{$bug.title}">
                <a href="/bugtracker/bug/{$bug.id}">{$bug.title}</a>
                {if $bug.commentCount > 0}
                  <span class="comments-link">{$bug.commentCount}</span>
                {/if}
              </td>
							<td data-raw="{$bug.changedSort}">
                <span data-tooltip="{strip}
                    {if $bug.by}
                        {if $bug.by.type == "created"}
                            Eintragung am {$bug.createdDate} von {if $bug.by.gm}&lt;span class=&quot;employee&quot;/&gt;{/if}{$bug.by.name}
                        {elseif $bug.by.type == "commented"}
                            Eintragung am {$bug.createdDate},&lt;br&gt;
                            Letzter Kommentar von {if $bug.by.gm}&lt;span class=&quot;employee&quot;/&gt;{/if}{$bug.by.name}
                        {/if}

                    {else}
                    Eintragung am {$bug.createdDate}
                    {/if}
                {/strip}">{$bug.changedDate}</span>
              </td>
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

  </div> <!-- /related -->
</div>