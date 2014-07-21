<div class="sidebox-eqdkp container">
    {if $hasEvents}
        {foreach $events as $eventId => $event}
            <div class="row">
                <div class="col-sm-5">
                    {$event.date}
                </div>
                <div class="col-sm-7">
                    {if $event.url}
                        <a href="{$eqdkpUrl}{$event.url}" data-tooltip="{$event.note}">{$event.title}</a>
                    {else}
                        {$event.title}
                    {/if}
                    {if $event.type == "raid"}
                        <span class="badge pull-right eqdkp-raid">Raid</span>
                    {else}
                        <span class="badge pull-right eqdkp-event">Event</span>
                    {/if}
                </div>
            </div>
        {/foreach}
    {else}
        <div class="row">
            <div class="col-sm-12">
                <p>Demnächst stehen keine Events oder Raids an.</p>
            </div>
        </div>
    {/if}
    <br/>
    <div class="row">
        <div class="col-sm-12">
            <p><a href="{$eqdkpUrl}calendar.php">Zum Raidkalender</a></p>
        </div>
    </div>
</div>
