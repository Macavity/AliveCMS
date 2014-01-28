{if $show_breadcrumbs}
    <!-- content-trail -->
    <div class="content-trail row">
        <ol class="ui-breadcrumb col-md-12">
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