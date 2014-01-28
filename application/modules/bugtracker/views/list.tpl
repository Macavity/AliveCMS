
<div class="bugtracker-actions row">
    <div class="col-md-12">
        <a href="{site_url('bugtracker/index')}" class="btn btn-default btn-sm">
            <i class="glyphicon glyphicon-chevron-left"></i>
            Kategorienliste
        </a>
        {if hasPermission("canCreateBugs")}
            <a href="{site_url('bugtracker/create')}" class="btn btn-default btn-sm">
                Neuen Bug eintragen
            </a>&nbsp;
        {/if}
    </div>
</div>

<div id="recentChanges" class="row">
    <div id="recentCreations" class="col-md-6">
        {if $recentCreations}
            <h3>Neue Bugs</h3>
            <ul>
                {foreach from=$recentCreations key=i item=bug}
                    <li class="{cycle values="row1,row2"}">
                        <span class="{$bug.css}"></span>
                        {$bug.date}
                        <a href="/bugtracker/bug/{$bug.id}"><i class="icon {$bug.priorityClass}" data-tooltip="{$bug.priorityLabel}"></i> #{$bug.id} {$bug.title}</a>
                        {if $bug.by.gm}<span class="employee"/></span>{/if}{$bug.by.name}
                    </li>
                {/foreach}
            </ul>
        {/if}
    </div>
    <div id="recentComments" class="col-md-6">
        {if $recentComments}
            <h3>Neue Kommentare</h3>
            <ul>
                {foreach from=$recentComments key=i item=comment}
                    <li class="{cycle values="row1,row2"}">
                        <span class="{$bug.css}"></span>
                        {$comment.date}
                        <a href="/bugtracker/bug/{$comment.bug_entry}">#{$comment.bug_entry} {$comment.title}</a>
                        {if $comment.by.gm}<span class="employee"></span>{/if}{$comment.by.name}
                    </li>
                {/foreach}
            </ul>
        {/if}
    </div>
</div>

<div id="buglist" class="wiki" data-rowcount="{$rowCount}">
    <div class="related">
		<div class="related-content" id="related-buglist">
			<div class="filters inline row">
                <div class="filter-tabs col-md-8">
                    <a href="javascript:;" data-filter="column" data-column="0" data-value="" data-name="type"> Alle </a>
                    <a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="1" class="tab-active"> Offen </a>
                    <a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="2"> In Bearbeitung </a>
                    <a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="4"> Workaround </a>
                    <a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="9"> Erledigt </a>
                    <a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="3"> Abgewiesen </a>
                </div>
				<div class="keyword col-md-4">
                    <span class="view"><i class="glyphicon glyphicon-search"></i></span>
                    <span class="reset" style="display: none"><i class="glyphicon glyphicon-remove"></i></span>
					<input id="filter-name-buglist" type="text" class="input filter-name" data-filter="row" title="Filter..." value="Filter..." />
				</div>
			</div>
			<div class="data-options-top row">
				<div class="table-options data-options col-md-12">
					<div class="option">
						<ul class="ui-pagination"></ul>
					</div>
					Zeige <strong class="results-start">{$rowMin}</strong>–<strong class="results-end">{$rowMax}</strong> von <strong class="results-total">{$rowCount}</strong> Ergebnissen
		            <span class="clear"><!-- --></span> 
				</div>
			</div>
			<div class="full-width row">
				<table class="table table-striped">
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
							<td data-raw="{$bug.title},{$bug.search_id}">
                                <a href="/bugtracker/bug/{$bug.id}">{$bug.title}</a>
                                {if $bug.commentCount > 0}
                                    <span class="comments-link">{$bug.commentCount}</span>
                                {/if}
                                <p style="display:none">{$bug.search_id}</p>
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