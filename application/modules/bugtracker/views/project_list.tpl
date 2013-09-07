<div class="bugtracker-actions">
    {if hasPermission("canCreateBugs")}
        <a href="{site_url('bugtracker/create')}" class="ui-button button2"><span><span>Neuen Bug eintragen</span></span></a>&nbsp;
    {/if}
</div><br>

{foreach from=$projects item=project}
<section id="bt-project-{$project.id}" class="bugtracker-project">
  <header class="row">
    <div class="span1">
      {if $project.icon}
        <img src="{$project.icon}" class="icon" valign="middle" align="left">
      {/if}
    </div>
    <div class="span10">
        <h2 data-toggle="collapse" data-parent="#bt-project-{$project.id}" href="#bt-project-list-{$project.id}">
            <i class="icon-white icon-minus"></i>
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
            <div class="bar bar-success" style="width: {max($project.counts.percentage.done,1)}%;">{$project.counts.percentage.done}%</div>
          {else}
              0%
          {/if}
          {if $project.counts.active > 0}
            <div data-tooltip="{$project.counts.active} in Arbeit" class="bar bar-warning" style="width: {max($project.counts.percentage.active,1)}%;"></div>
          {/if}
        </div>
    </div>
  {/if}

  {if $project.projects}
    <div id="bt-project-list-{$project.id}" class="project-list collapse in">
      {foreach from=$project.projects item=sub}
        <div class="project-level-1">
          <h3>
            <i class="icon-flag icon-white"></i>
            <a href="{$url}bugtracker/buglist/{$sub.id}">{$sub.title}</a>
          </h3>
          {if $sub.counts.all > 0}
              <div class="row">
                Tickets: <a href="{$url}bugtracker/buglist/{$sub.id}/">{$sub.counts.open} offen</a> (Gesamt: {$sub.counts.all})
              </div>
              <div class="row">
                <div class="progress">
                  {if $sub.counts.done > 0}
                    <div class="bar bar-success" style="width: {$sub.counts.percentage.done}%;">{$sub.counts.percentage.done}%</div>
                  {else}
                      0%
                  {/if}
                  {if $sub.counts.active > 0}
                    <div data-tooltip="{$sub.counts.active} in Arbeit" class="bar bar-warning" style="width: {max($sub.counts.percentage.active,1)}%;"></div>
                  {/if}
                </div>
              </div>
              {if $sub.projects}
                <ul class="row sub-project-list">
                  {foreach from=$sub.projects item=subsub}
                    <li class="span5">
                      <label>
                        <i class="icon-tag icon-white"></i>
                        <a href="{$url}bugtracker/buglist/{$subsub.id}" class="{$subsub.class}">{$subsub.title}</a>
                      </label>
                      {if $subsub.counts.all > 0}
                          <div class="row">
                            Tickets: <a href="{$url}bugtracker/buglist/{$subsub.id}/">{$subsub.counts.open} offen</a> (Gesamt: {$subsub.counts.all})
                          </div>
                          <div class="row">
                            <div class="progress">
                              {if $subsub.counts.done > 0}
                                <div class="bar bar-success" style="width: {$subsub.counts.percentage.done}%;">{$subsub.counts.percentage.done}%</div>
                              {else}
                                  0%
                              {/if}
                              {if $subsub.counts.active > 0}
                                <div data-tooltip="{$subsub.counts.active} in Arbeit" class="bar bar-warning" style="width: {max($subsub.counts.percentage.active,1)}%;"></div>
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