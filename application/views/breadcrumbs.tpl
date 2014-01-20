{if $show_breadcrumbs}
    <!-- content-trail -->
    <div class="content-trail">
        <ol class="ui-breadcrumb">
            {foreach from=$breadcrumbs item=entry name=breadcrumbs}
                <li{if $smarty.foreach.breadcrumbs.last} class="active"{/if}>
                    {if empty($entry.link)}
                        {$entry.title}
                    {else}
                        <a href="{$entry.link}">{$entry.title}</a>
                    {/if}
                </li>
            {/foreach}
        </ol>
    </div>
    <!-- /content-trail -->
{/if}