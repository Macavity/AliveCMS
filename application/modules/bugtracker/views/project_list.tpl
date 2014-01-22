<div class="bugtracker-actions row">
    <div class="col-md">
        {if $permCanCreateBugs}
            <a href="{site_url('bugtracker/create')}" class="btn btn-sm btn-default">Neuen Bug eintragen</a>&nbsp;
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
                        <span class="{$comment.css}"></span>
                        {$comment.date}
                        <a href="/bugtracker/bug/{$comment.bug_entry}">#{$comment.bug_entry} {$comment.title}</a>
                        {if $comment.by.gm}<span class="employee"></span>{/if}{$comment.by.name}
                    </li>
                {/foreach}
            </ul>
        {/if}
    </div>
</div><br>

{foreach from=$projects item=project}
<section id="bt-project-{$project.id}" class="bugtracker-project container">
  <header class="row">
    <div class="col-md-1">
      {if $project.icon}
        <img src="{$project.icon}" class="icon" valign="middle" align="left">
      {/if}
    </div>
    <div class="col-md-11">
        <h2 data-toggle="collapse" data-parent="#bt-project-{$project.id}" href="#bt-project-list-{$project.id}" class="collapsed">
            <i class="glyphicon glyphicon-plus"></i>
            {$project.title}
        </h2>
      {if $project.counts.all > 0}
        <span>Tickets: <a href="{$url}bugtracker/buglist/{$project.id}/">{$project.counts.open} offen</a> (Gesamt: {$project.counts.all})</span>
      {/if}
    </div>
  </header>
  {if $project.counts.all > 0}
    <div class="row">
        <div class="progress">
          {if $project.counts.done > 0}
            <div data-tooltip="{$project.counts.done} erledigt" class="progress-bar progress-bar-success" role="progressbar" style="width: {max($project.counts.percentage.done,1)}%;">{$project.counts.percentage.done}%</div>
          {else}
              0%
          {/if}
          {*if $project.counts.workaround > 0}
            <div data-tooltip="{$project.counts.workaround} Workarounds" class="progress-bar progress-bar-info" style="width: {max($project.counts.percentage.workaround,1)}%;"></div>
          {/if*}
          {if $project.counts.active > 0}
            <div data-tooltip="{$project.counts.active} in Arbeit" class="progress-bar progress-bar-warning" style="width: {max($project.counts.percentage.active,1)}%;"></div>
          {/if}
        </div>
    </div>
  {/if}

  {if $project.projects}
    <div id="bt-project-list-{$project.id}" class="project-list collapse">
      {foreach from=$project.projects item=sub}
        <div class="project-level-1">
          <h3>
            <i class="glyphicon glyphicon-flag"></i>
            <a href="{$url}bugtracker/buglist/{$sub.id}">{$sub.title}</a>
          </h3>
          {if $sub.counts.all > 0}
              <div class="row">
                Tickets: <a href="{$url}bugtracker/buglist/{$sub.id}/">{$sub.counts.open} offen</a> (Gesamt: {$sub.counts.all})
              </div>
              <div class="row">
                <div class="progress">
                  {if $sub.counts.done > 0}
                    <div data-tooltip="{$sub.counts.done} erledigt"
                         class="progress-bar progress-bar-success"
                         role="progressbar"
                         style="width: {$sub.counts.percentage.done}%;">{$sub.counts.percentage.done}%</div>
                  {else}
                      0%
                  {/if}
                  {if $sub.counts.active > 0}
                    <div data-tooltip="{$sub.counts.active} in Arbeit"
                         class="progress-bar progress-bar-warning"
                         role="progressbar"
                         style="width: {max($sub.counts.percentage.active,1)}%;"></div>
                  {/if}
                </div>
              </div>
              {if $sub.projects}
                <ul class="row sub-project-list">
                  {foreach from=$sub.projects item=subsub}
                    <li class="col-md-6">
                      <label>
                        <i class="glyphicon glyphicon-tag"></i>
                        <a href="{$url}bugtracker/buglist/{$subsub.id}" class="{$subsub.class}">{$subsub.title}</a>
                      </label>
                      {if $subsub.counts.all > 0}
                          <div class="row">
                            Tickets: <a href="{$url}bugtracker/buglist/{$subsub.id}/">{$subsub.counts.open} offen</a> (Gesamt: {$subsub.counts.all})
                          </div>
                          <div class="row">
                            <div class="progress">
                              {if $subsub.counts.done > 0}
                                <div data-tooltip="{$subsub.counts.done} erledigt"
                                     class="progress-bar progress-bar-success"
                                     role="progressbar"
                                     style="width: {$subsub.counts.percentage.done}%;">{$subsub.counts.percentage.done}%</div>
                              {else}
                                  0%
                              {/if}
                              {if $subsub.counts.active > 0}
                                <div data-tooltip="{$subsub.counts.active} in Arbeit"
                                     class="progress-bar progress-bar-warning"
                                     role="progressbar"
                                     style="width: {max($subsub.counts.percentage.active,1)}%;"></div>
                              {/if}
                            </div>
                          </div>
                      {/if}
                    </li>
                  {/foreach}
                </ul>
              {/if}
          {/if}
        </div>
      {/foreach}
    </div>
  {/if}
</section>
{/foreach}